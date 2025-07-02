<!DOCTYPE html>
<html>
    <head>
        <title>Assegna Staff all'Hotel</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            include "db.php";
            include "funzioni.php";

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

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

            // Query per ottenere tutti i membri dello staff non ancora assegnati a questo hotel
            $query_staff = "SELECT s.codice_fiscale, s.nome, s.cognome 
                           FROM staff s
                           WHERE s.codice_fiscale NOT IN (
                               SELECT codice_fiscale FROM impieghi_hotel WHERE id_hotel = ?
                           )";
            $stmt = $connessione->prepare($query_staff);
            $stmt->bind_param("i", $id_hotel);
            $stmt->execute();
            $staff_disponibile = $stmt->get_result();
        ?>

        <center><h1>Assegna Staff a <?php echo htmlspecialchars($nome_hotel); ?></h1></center>

        <div class='contenitore-pulsanti'>
            <a href='visualizza_staff_hotel.php' class='Redirect' onclick='return goBack()'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_staff_hotel.php'>
                <input type='hidden' name='id_hotel' value='<?php echo $id_hotel; ?>'>
                
                <div class='form-group'>
                    <label for='codice_fiscale'>Seleziona Membro dello Staff:</label>
                    <select id='codice_fiscale' name='codice_fiscale' required>
                        <option value=''>Seleziona membro staff</option>
                        <?php
                        if ($staff_disponibile->num_rows > 0) {
                            while($row = $staff_disponibile->fetch_assoc()) {
                                echo "<option value='".htmlspecialchars($row['codice_fiscale'])."'>";
                                echo htmlspecialchars($row['cognome'])." ".htmlspecialchars($row['nome']);
                                echo "</option>";
                            }
                        } else {
                            echo "<option value='' disabled>Nessun membro dello staff disponibile</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <?php 
                if (!empty($errori)) {
                    foreach ($errori as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                }
                ?>
                
                <div class='form-group'>
                    <input type='submit' value='Assegna Staff' class='pulsante-invio'>
                </div>
            </form>
        </div>

        <script>
            function goBack() {
                // Creiamo un form temporaneo per inviare l'id_hotel via POST
                const form = document.createElement('form');
                form.method = 'post';
                form.action = 'visualizza_staff_hotel.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_hotel';
                input.value = '<?php echo $id_hotel; ?>';
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
                
                return false; // Previene il comportamento normale del link
            }
        </script>
    </body>
</html>