<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Nuova Prenotazione</title>
        <link rel="stylesheet" href="../../css/style.css">
        <script>
            function validaDate() {
                const checkIn = new Date(document.getElementById('check_in').value);
                const checkOut = new Date(document.getElementById('check_out').value);
                
                if (checkIn >= checkOut) {
                    alert("La data di check-in deve essere precedente al check-out");
                    return false;
                }
                return true;
            }

            function mostraCampiPagamento() {
                const tipoPagamento = document.getElementById('id_tipo_pagamento').value;
                const cartaCreditoDiv = document.getElementById('carta_credito_fields');
                const bonificoDiv = document.getElementById('bonifico_fields');
                const contantiDiv = document.getElementById('contanti_fields');
                
                cartaCreditoDiv.style.display = 'none';
                bonificoDiv.style.display = 'none';
                contantiDiv.style.display = 'none';
                
                if (tipoPagamento == 1) { // Carta di credito
                    cartaCreditoDiv.style.display = 'block';
                } else if (tipoPagamento == 2) { // Bonifico
                    bonificoDiv.style.display = 'block';
                } else if (tipoPagamento == 3) { // Contanti
                    contantiDiv.style.display = 'block';
                }
            }
        </script>
    </head>
    <body>    
        <?php
            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            
            // Carica gli ospiti per il select
            $query_ospiti = "SELECT codice_fiscale, nome, cognome FROM ospiti ORDER BY cognome, nome";
            $result_ospiti = $connessione->query($query_ospiti);
            
            // Query per le camere
            $query_camere = "SELECT c.numero_camera, c.prezzo_notte 
                            FROM camere c
                            JOIN edifici e ON c.id_edificio = e.id_edificio
                            WHERE e.id_hotel = $id_hotel
                            ORDER BY c.numero_camera";
            $result_camere = $connessione->query($query_camere);

            // Carica i tipi di pagamento
            $query_tipi_pagamento = "SELECT id_tipo_pagamento, metodo_pagamento FROM tipi_pagamento";
            $result_tipi_pagamento = $connessione->query($query_tipi_pagamento);

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $dati_prenotazione = [
                    'check_in' => $_POST['check_in'],
                    'check_out' => $_POST['check_out'],
                    'attiva' => 1,
                    'id_hotel' => $id_hotel,
                    'numero_camera' => $_POST['numero_camera'],
                    'codice_fiscale_cliente' => $_POST['codice_fiscale_cliente']
                ];
                
                // Regole di validazione
                $regole_validazione = [
                    'check_in' => ['required' => true],
                    'check_out' => ['required' => true],
                    'numero_camera' => ['required' => true],
                    'codice_fiscale_cliente' => ['required' => true, 'max_length' => 16]
                ];
                
                // Validazione date lato server
                $errori = [];
                
                if (!strtotime($dati_prenotazione['check_in']) || !strtotime($dati_prenotazione['check_out'])) {
                    $errori[] = "Le date inserite non sono valide";
                }
                elseif (strtotime($dati_prenotazione['check_in']) >= strtotime($dati_prenotazione['check_out'])) {
                    $errori[] = "La data di check-in deve essere precedente al check-out";
                }
                
                // Verifica disponibilità camera
                if (empty($errori)) {
                    $check_in = $dati_prenotazione['check_in'];
                    $check_out = $dati_prenotazione['check_out'];
                    $numero_camera = $dati_prenotazione['numero_camera'];

                    $query_disponibilita = "SELECT id_prenotazione, check_in, check_out 
                                          FROM prenotazioni 
                                          WHERE numero_camera = ?
                                            AND attiva = 1
                                            AND (
                                                (check_in < ? AND check_out > ?) OR
                                                (check_in >= ? AND check_in < ?) OR
                                                (check_out > ? AND check_out <= ?) OR
                                                (check_in <= ? AND check_out >= ?)
                                            )";

                    $stmt = $connessione->prepare($query_disponibilita);
                    if ($stmt) {
                        $stmt->bind_param("sssssssss", 
                            $numero_camera,
                            $check_out, $check_in,
                            $check_in, $check_out,
                            $check_in, $check_out,
                            $check_in, $check_out
                        );
                        $stmt->execute();
                        $risultato_disponibilita = $stmt->get_result();

                        if ($risultato_disponibilita->num_rows > 0) {
                            $prenotazione_esistente = $risultato_disponibilita->fetch_assoc();
                            $errori[] = "La camera #".$numero_camera." è già prenotata dal ".$prenotazione_esistente['check_in']." al ".$prenotazione_esistente['check_out']." (ID: ".$prenotazione_esistente['id_prenotazione'].")";
                        }
                        $stmt->close();
                    } else {
                        $errori[] = "Errore nella verifica della disponibilità";
                    }
                }

                if (empty($errori)) {
                    // Inserimento prenotazione con transazione
                    $connessione->begin_transaction();
                    
                    try {
                        // Inserimento prenotazione
                        $query_prenotazione = "INSERT INTO prenotazioni (check_in, check_out, attiva, id_hotel, numero_camera, codice_fiscale_cliente) 
                                             VALUES (?, ?, ?, ?, ?, ?)";
                        $stmt_prenotazione = $connessione->prepare($query_prenotazione);
                        $stmt_prenotazione->bind_param("ssiiss", 
                            $dati_prenotazione['check_in'],
                            $dati_prenotazione['check_out'],
                            $dati_prenotazione['attiva'],
                            $dati_prenotazione['id_hotel'],
                            $dati_prenotazione['numero_camera'],
                            $dati_prenotazione['codice_fiscale_cliente']
                        );
                        
                        if (!$stmt_prenotazione->execute()) {
                            throw new Exception("Errore nell'inserimento della prenotazione: " . $stmt_prenotazione->error);
                        }
                        
                        $id_prenotazione = $connessione->insert_id;
                        $stmt_prenotazione->close();
                        
                        // INSERIMENTO DI TUTTI GLI OSPITI (CLIENTE PRINCIPALE + AGGIUNTIVI)
                        $query_associazione = "INSERT INTO ospiti_prenotazione (id_prenotazione, codice_fiscale) VALUES (?, ?)";
                        $stmt_associazione = $connessione->prepare($query_associazione);
                        
                        // 1. Inseriamo prima il cliente principale
                        $stmt_associazione->bind_param("is", $id_prenotazione, $_POST['codice_fiscale_cliente']);
                        if (!$stmt_associazione->execute()) {
                            throw new Exception("Errore nell'associazione del cliente principale: " . $stmt_associazione->error);
                        }
                        
                        // 2. Poi inseriamo gli ospiti aggiuntivi (se presenti)
                        if (!empty($_POST['ospiti_aggiuntivi'])) {
                            foreach ($_POST['ospiti_aggiuntivi'] as $codice_fiscale) {
                                // Evitiamo di inserire duplicati (nel caso il cliente principale fosse anche selezionato come ospite aggiuntivo)
                                if ($codice_fiscale != $_POST['codice_fiscale_cliente']) {
                                    $stmt_associazione->bind_param("is", $id_prenotazione, $codice_fiscale);
                                    if (!$stmt_associazione->execute()) {
                                        throw new Exception("Errore nell'associazione dell'ospite aggiuntivo: " . $stmt_associazione->error);
                                    }
                                }
                            }
                        }
                        $stmt_associazione->close();
                        
                        // Calcola il numero di notti
                        $check_in = new DateTime($dati_prenotazione['check_in']);
                        $check_out = new DateTime($dati_prenotazione['check_out']);
                        $differenza = $check_in->diff($check_out);
                        $notti = $differenza->days;
                        
                        // Ottieni il prezzo per notte della camera
                        $query_prezzo = "SELECT prezzo_notte FROM camere WHERE numero_camera = ?";
                        $stmt = $connessione->prepare($query_prezzo);
                        $stmt->bind_param("s", $dati_prenotazione['numero_camera']);
                        $stmt->execute();
                        $result_prezzo = $stmt->get_result();
                        $prezzo_notte = $result_prezzo->fetch_assoc()['prezzo_notte'];
                        $stmt->close();
                        
                        // Calcola l'importo totale
                        $importo_totale = $notti * $prezzo_notte;
                        
                        // Prepara i dati della fattura
                        $dati_fattura = [
                            'data_emissione' => date('Y-m-d'),
                            'importo_totale' => $importo_totale,
                            'id_tipo_pagamento' => $_POST['id_tipo_pagamento'],
                            'id_prenotazione' => $id_prenotazione
                        ];
                        
                        // Aggiungi i campi specifici in base al tipo di pagamento
                        if ($_POST['id_tipo_pagamento'] == 1) { // Carta di credito
                            $dati_fattura['numero_carta'] = $_POST['numero_carta'];
                            $dati_fattura['cvc_carta'] = $_POST['cvc_carta'];
                        } elseif ($_POST['id_tipo_pagamento'] == 2) { // Bonifico
                            $dati_fattura['numero_conto_corrente'] = $_POST['numero_conto_corrente'];
                        }
                        
                        // Inserimento fattura
                        $campi_fattura = [];
                        $valori_fattura = [];
                        $tipi_fattura = '';
                        $parametri_fattura = [];
                        
                        foreach ($dati_fattura as $campo => $valore) {
                            $campi_fattura[] = $campo;
                            $valori_fattura[] = '?';
                            $parametri_fattura[] = $valore;
                            
                            if (is_int($valore)) {
                                $tipi_fattura .= 'i';
                            } elseif (is_float($valore)) {
                                $tipi_fattura .= 'd';
                            } else {
                                $tipi_fattura .= 's';
                            }
                        }
                        
                        $query_fattura = "INSERT INTO fatture (" . implode(', ', $campi_fattura) . ") 
                                         VALUES (" . implode(', ', $valori_fattura) . ")";
                        $stmt_fattura = $connessione->prepare($query_fattura);
                        
                        if ($stmt_fattura) {
                            $stmt_fattura->bind_param($tipi_fattura, ...$parametri_fattura);
                            
                            if (!$stmt_fattura->execute()) {
                                throw new Exception("Errore nell'inserimento della fattura: " . $stmt_fattura->error);
                            }
                            
                            $stmt_fattura->close();
                        } else {
                            throw new Exception("Errore nella preparazione della query per la fattura: " . $connessione->error);
                        }
                        
                        // Se tutto è andato bene, conferma la transazione
                        $connessione->commit();
                        
                        // Messaggio di successo migliorato
                        $messaggio_successo = "Prenotazione creata con successo!<br>";
                        $messaggio_successo .= "ID Prenotazione: $id_prenotazione<br>";
                        $messaggio_successo .= "Cliente principale: " . htmlspecialchars($_POST['codice_fiscale_cliente']) . "<br>";
                        
                        // Contiamo gli ospiti totali (cliente principale + aggiuntivi)
                        $totale_ospiti = 1; // Il cliente principale
                        if (!empty($_POST['ospiti_aggiuntivi'])) {
                            $totale_ospiti += count($_POST['ospiti_aggiuntivi']);
                        }
                        $messaggio_successo .= "Ospiti totali associati: $totale_ospiti<br>";
                        $messaggio_successo .= "Importo totale: €" . number_format($importo_totale, 2);
                        
                        echo "<div class='messaggio successo'>$messaggio_successo</div>";
                        
                        // Resetta i campi
                        $_POST = [];
                        
                    } catch (Exception $e) {
                        // In caso di errore, annulla tutto
                        $connessione->rollback();
                        echo "<div class='messaggio errore'>" . $e->getMessage() . "</div>";
                    }
                } else {
                    foreach ($errori as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                }
            }
        ?>

        <center><h1>Nuova Prenotazione</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_prenotazioni.php?id_hotel=<?php echo $id_hotel; ?>' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_prenotazione.php' onsubmit="return validaDate()">
                <input type='hidden' name='id_hotel' value='<?php echo $id_hotel; ?>'>
                
                <div class='form-group'>
                    <label for='check_in'>Check-in:</label>
                    <input type='date' id='check_in' name='check_in' 
                           value="<?php echo $_POST['check_in'] ?? ''; ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='check_out'>Check-out:</label>
                    <input type='date' id='check_out' name='check_out' 
                           value="<?php echo $_POST['check_out'] ?? ''; ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='numero_camera'>Camera:</label>
                    <select id='numero_camera' name='numero_camera' required>
                        <option value=''>Seleziona camera</option>
                        <?php 
                        $result_camere->data_seek(0); // Resetta il puntatore del risultato
                        while($camera = $result_camere->fetch_assoc()): ?>
                            <option value='<?php echo $camera['numero_camera']; ?>'
                                <?php echo (isset($_POST['numero_camera']) && $_POST['numero_camera'] == $camera['numero_camera']) ? 'selected' : ''; ?>>
                                <?php echo $camera['numero_camera']; ?> (€<?php echo $camera['prezzo_notte']; ?>/notte)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class='form-group'>
                    <label for='codice_fiscale_cliente'>Cliente principale:</label>
                    <input list='lista_ospiti' id='codice_fiscale_cliente' name='codice_fiscale_cliente' 
                        placeholder="Cerca per nome, cognome o CF..." required>
                    <datalist id='lista_ospiti'>
                        <?php 
                        $result_ospiti->data_seek(0);
                        while($ospite = $result_ospiti->fetch_assoc()): ?>
                            <option value='<?php echo $ospite['codice_fiscale']; ?>'>
                                <?php echo $ospite['cognome'] . ' ' . $ospite['nome'] . ' (' . $ospite['codice_fiscale'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </datalist>
                </div>
                
                <div class='form-group'>
                    <label for='ospiti_aggiuntivi'>Ospiti aggiuntivi:</label>
                    <select id='ospiti_aggiuntivi' name='ospiti_aggiuntivi[]' multiple size="4">
                        <?php 
                        $result_ospiti->data_seek(0); // Resetta il puntatore del risultato
                        while($ospite = $result_ospiti->fetch_assoc()): ?>
                            <option value='<?php echo $ospite['codice_fiscale']; ?>'
                                <?php echo (isset($_POST['ospiti_aggiuntivi']) && in_array($ospite['codice_fiscale'], $_POST['ospiti_aggiuntivi'])) ? 'selected' : ''; ?>>
                                <?php echo $ospite['cognome'] . ' ' . $ospite['nome'] . ' (' . $ospite['codice_fiscale'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <small>Tenere premuto Ctrl per selezionare più ospiti</small>
                </div>
                
                <div class='form-group'>
                    <label for='id_tipo_pagamento'>Metodo di pagamento:</label>
                    <select id='id_tipo_pagamento' name='id_tipo_pagamento' required onchange="mostraCampiPagamento()">
                        <option value=''>Seleziona metodo di pagamento</option>
                        <?php 
                        $result_tipi_pagamento->data_seek(0); // Resetta il puntatore del risultato
                        while($tipo = $result_tipi_pagamento->fetch_assoc()): ?>
                            <option value='<?php echo $tipo['id_tipo_pagamento']; ?>'
                                <?php echo (isset($_POST['id_tipo_pagamento']) && $_POST['id_tipo_pagamento'] == $tipo['id_tipo_pagamento']) ? 'selected' : ''; ?>>
                                <?php echo $tipo['metodo_pagamento']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <!-- Campi per carta di credito -->
                <div id='carta_credito_fields' style='display: none;'>
                    <div class='form-group'>
                        <label for='numero_carta'>Numero carta:</label>
                        <input type='text' id='numero_carta' name='numero_carta' 
                               value="<?php echo $_POST['numero_carta'] ?? ''; ?>"
                               maxlength="16" pattern="[0-9]{16}" title="Inserire 16 cifre">
                    </div>
                    
                    <div class='form-group'>
                        <label for='cvc_carta'>CVC:</label>
                        <input type='text' id='cvc_carta' name='cvc_carta' 
                               value="<?php echo $_POST['cvc_carta'] ?? ''; ?>"
                               maxlength="3" pattern="[0-9]{3}" title="Inserire 3 cifre">
                    </div>
                </div>
                
                <!-- Campi per bonifico -->
                <div id='bonifico_fields' style='display: none;'>
                    <div class='form-group'>
                        <label for='numero_conto_corrente'>Numero conto corrente:</label>
                        <input type='text' id='numero_conto_corrente' name='numero_conto_corrente' 
                               value="<?php echo $_POST['numero_conto_corrente'] ?? ''; ?>"
                               maxlength="12" pattern="[0-9]{12}" title="Inserire 12 cifre">
                    </div>
                </div>
                
                <!-- Messaggio per contanti -->
                <div id='contanti_fields' style='display: none;'>
                    <div class='form-group'>
                        <p>Pagamento in contanti alla reception al momento del check-in.</p>
                    </div>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Prenotazione' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>