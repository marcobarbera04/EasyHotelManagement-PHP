<!DOCTYPE html>
<html>
    <head>
        <title>Aggiungi Servizio Offerto</title>
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

            // Carica tutti i servizi disponibili
            $query_servizi = "SELECT id_servizio, nome_servizio, categoria_servizio FROM servizi ORDER BY categoria_servizio, nome_servizio";
            $result_servizi = $connessione->query($query_servizi);

            // Inizializza array errori e variabile servizio_esistente
            $errori = [];
            $servizio_esistente = null;

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id_servizio = $_POST['id_servizio'];
                
                // Verifica se il servizio è già offerto dall'hotel
                $query_verifica = "SELECT s.nome_servizio 
                                 FROM servizi_offerti so
                                 JOIN servizi s ON so.id_servizio = s.id_servizio
                                 WHERE so.id_hotel = ? AND so.id_servizio = ?";
                $stmt = $connessione->prepare($query_verifica);
                $stmt->bind_param("ii", $id_hotel, $id_servizio);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $servizio_esistente = $result->fetch_assoc();
                    $errori[] = "Questo servizio (" . $servizio_esistente['nome_servizio'] . ") è già offerto da questo hotel";
                } else {
                    $dati_servizio_offerto = [
                        'id_servizio' => $id_servizio,
                        'id_hotel' => $id_hotel
                    ];
                    
                    $regole_validazione = [
                        'id_servizio' => ['required' => true, 'numeric' => true],
                        'id_hotel' => ['required' => true, 'numeric' => true]
                    ];
                    
                    $risultato = inserisci($connessione, 'servizi_offerti', $dati_servizio_offerto, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Servizio aggiunto con successo!</div>";
                        $_POST = []; // Resetta i campi
                    } else {
                        $errori = array_merge($errori, $risultato['errori']);
                    }
                }
            }
        ?>

        <center><h1>Aggiungi Servizio Offerto - <?php echo htmlspecialchars($nome_hotel); ?></h1></center>

        <div class='contenitore-pulsanti'>
            <a href='visualizza_servizi_offerti.php?id_hotel=<?php echo $id_hotel; ?>' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_servizio_offerto.php'>
                <input type='hidden' name='id_hotel' value='<?php echo $id_hotel; ?>'>
                
                <div class='form-group'>
                    <label for='id_servizio'>Servizio:</label>
                    <select id='id_servizio' name='id_servizio' required>
                        <option value=''>Seleziona un servizio</option>
                        <?php while($servizio = $result_servizi->fetch_assoc()): ?>
                            <option value='<?php echo $servizio['id_servizio']; ?>'
                                <?php echo (isset($_POST['id_servizio']) && $_POST['id_servizio'] == $servizio['id_servizio']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($servizio['categoria_servizio'] . ' - ' . $servizio['nome_servizio']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <?php 
                    if (!empty($errori)) {
                        foreach ($errori as $errore) {
                            echo "<div class='messaggio errore'>$errore</div>";
                        }
                    }
                    ?>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Aggiungi Servizio' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>