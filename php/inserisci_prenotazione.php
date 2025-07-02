<!DOCTYPE html>
<html>
    <head>
        <title>Nuova Prenotazione</title>
        <link rel="stylesheet" href="../css/style.css">
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
        </script>
    </head>
    <body>    
        <?php
            include "db.php";
            include "funzioni.php";

            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            
            // Carica gli ospiti per il select
            $query_ospiti = "SELECT codice_fiscale, nome, cognome FROM ospiti ORDER BY cognome, nome";
            $result_ospiti = $connessione->query($query_ospiti);
            
            // Query corretta per le camere con join tra camere e edifici
            $query_camere = "SELECT c.numero_camera 
                            FROM camere c
                            JOIN edifici e ON c.id_edificio = e.id_edificio
                            WHERE e.id_hotel = $id_hotel
                            ORDER BY c.numero_camera";
            $result_camere = $connessione->query($query_camere);

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
                
                // Verifica disponibilità camera - VERSIONE CORRETTA
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
                            $check_out, $check_in,    // Per prima condizione
                            $check_in, $check_out,    // Per seconda condizione
                            $check_in, $check_out,    // Per terza condizione
                            $check_in, $check_out     // Per quarta condizione
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
                    $risultato = inserisci($connessione, 'prenotazioni', $dati_prenotazione, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Prenotazione aggiunta con successo!</div>";
                        // Resetta i campi
                        $_POST = [];
                    } else {
                        foreach ($risultato['errori'] as $errore) {
                            echo "<div class='messaggio errore'>$errore</div>";
                        }
                    }
                } else {
                    foreach ($errori as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                }
            }
        ?>

        <!-- Resto del codice HTML rimane invariato -->
        <center><h1>Nuova Prenotazione</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='visualizza_prenotazioni.php?id_hotel=<?php echo $id_hotel; ?>' class='Redirect'>Indietro</a>
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
                        <?php while($camera = $result_camere->fetch_assoc()): ?>
                            <option value='<?php echo $camera['numero_camera']; ?>'
                                <?php echo (isset($_POST['numero_camera']) && $_POST['numero_camera'] == $camera['numero_camera']) ? 'selected' : ''; ?>>
                                <?php echo $camera['numero_camera']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class='form-group'>
                    <label for='codice_fiscale_cliente'>Ospite:</label>
                    <select id='codice_fiscale_cliente' name='codice_fiscale_cliente' required>
                        <option value=''>Seleziona ospite</option>
                        <?php while($ospite = $result_ospiti->fetch_assoc()): ?>
                            <option value='<?php echo $ospite['codice_fiscale']; ?>'
                                <?php echo (isset($_POST['codice_fiscale_cliente']) && $_POST['codice_fiscale_cliente'] == $ospite['codice_fiscale']) ? 'selected' : ''; ?>>
                                <?php echo $ospite['cognome'] . ' ' . $ospite['nome'] . ' (' . $ospite['codice_fiscale'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Prenotazione' class='pulsante-invio'>
                </div>
            </form>
        </div>

    </body>
</html>