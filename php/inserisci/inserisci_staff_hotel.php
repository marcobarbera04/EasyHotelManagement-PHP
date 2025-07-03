<!DOCTYPE html>
<html>
    <head>
        <title>Assegna Staff all'Hotel</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            
            if (!$id_hotel) {
                die("ID hotel non specificato");
            }

            // Ottieni il nome dell'hotel
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = ? LIMIT 1";
            $stmt = $connessione->prepare($query_nome_hotel);
            $stmt->bind_param("i", $id_hotel);
            $stmt->execute();
            $nome_hotel = $stmt->get_result()->fetch_assoc()['nome'] ?? '';

            // Inizializza array errori
            $errori = [];
            $mansione_selezionata = $_POST['mansione'] ?? null;

            // Carica tutte le mansioni disponibili
            $query_mansioni = "SELECT DISTINCT m.mansione, m.descrizione 
                              FROM mansioni m
                              JOIN mansioni_staff ms ON m.mansione = ms.mansione
                              ORDER BY m.mansione";
            $result_mansioni = $connessione->query($query_mansioni);

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['codice_fiscale'])) {
                $codice_fiscale = trim($_POST['codice_fiscale']);
                
                // Verifica se lo staff è già assegnato a questo hotel
                $query_verifica = "SELECT codice_fiscale FROM impieghi_hotel 
                                 WHERE codice_fiscale = ? AND id_hotel = ?";
                $stmt = $connessione->prepare($query_verifica);
                $stmt->bind_param("si", $codice_fiscale, $id_hotel);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $errori[] = "Questo membro dello staff è già assegnato a questo hotel";
                } else {
                    $dati_impiego = [
                        'codice_fiscale' => $codice_fiscale,
                        'id_hotel' => $id_hotel
                    ];
                    
                    $regole_validazione = [
                        'codice_fiscale' => ['required' => true, 'max_length' => 16, 'exact_length' => 16],
                        'id_hotel' => ['required' => true, 'numeric' => true]
                    ];
                    
                    $risultato = inserisci($connessione, 'impieghi_hotel', $dati_impiego, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Staff assegnato all'hotel con successo!</div>";
                    } else {
                        $errori = array_merge($errori, $risultato['errori']);
                    }
                }
            }
        ?>

        <center><h1>Assegna Staff a <?php echo htmlspecialchars($nome_hotel); ?></h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_staff_hotel.php?id_hotel=<?php echo $id_hotel; ?>' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_staff_hotel.php'>
                <input type='hidden' name='id_hotel' value='<?php echo $id_hotel; ?>'>
                
                <div class='form-group'>
                    <label for='mansione'>Seleziona Mansione:</label>
                    <select id='mansione' name='mansione' required onchange="this.form.submit()">
                        <option value=''>Seleziona una mansione</option>
                        <?php while($mansione = $result_mansioni->fetch_assoc()): ?>
                            <option value='<?php echo $mansione['mansione']; ?>'
                                <?php echo ($mansione_selezionata == $mansione['mansione']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mansione['mansione']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <?php if ($mansione_selezionata): ?>
                    <?php
                    // Query per ottenere i membri dello staff con la mansione selezionata e non ancora assegnati a questo hotel
                    $query_staff = "SELECT s.codice_fiscale, s.nome, s.cognome 
                                  FROM staff s
                                  JOIN mansioni_staff ms ON s.codice_fiscale = ms.codice_fiscale
                                  WHERE ms.mansione = ? 
                                  AND s.codice_fiscale NOT IN (
                                      SELECT codice_fiscale FROM impieghi_hotel WHERE id_hotel = ?
                                  )";
                    $stmt = $connessione->prepare($query_staff);
                    $stmt->bind_param("si", $mansione_selezionata, $id_hotel);
                    $stmt->execute();
                    $staff_disponibile = $stmt->get_result();
                    ?>
                    
                    <div class='form-group'>
                        <label for='codice_fiscale'>Seleziona Membro dello Staff:</label>
                        <select id='codice_fiscale' name='codice_fiscale' required>
                            <option value=''>Seleziona staff</option>
                            <?php while($row = $staff_disponibile->fetch_assoc()): ?>
                                <option value='<?php echo htmlspecialchars($row['codice_fiscale']); ?>'>
                                    <?php echo htmlspecialchars($row['cognome'] . " " . $row['nome'] . " (" . $row['codice_fiscale'] . ")"); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class='form-group'>
                        <input type='submit' value='Assegna Staff' class='pulsante-invio'>
                    </div>
                <?php endif; ?>
                
                <?php 
                if (!empty($errori)) {
                    foreach ($errori as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                }
                ?>
            </form>
        </div>
    </body>
</html>