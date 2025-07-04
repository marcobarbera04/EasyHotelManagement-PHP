<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Aggiungi Camera</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            $id_edificio = $_GET['id_edificio'] ?? $_POST['id_edificio'] ?? null;
            
            if (!$id_edificio) {
                die("ID edificio non specificato");
            }

            // Ottieni i dettagli dell'edificio e dell'hotel
            $query_dettagli = "SELECT e.nome as nome_edificio, h.nome as nome_hotel, h.id_hotel 
                              FROM edifici e 
                              JOIN hotel h ON e.id_hotel = h.id_hotel 
                              WHERE e.id_edificio = ? LIMIT 1";
            $stmt = $connessione->prepare($query_dettagli);
            $stmt->bind_param("i", $id_edificio);
            $stmt->execute();
            $dettagli = $stmt->get_result()->fetch_assoc();
            
            $nome_edificio = $dettagli['nome_edificio'] ?? '';
            $nome_hotel = $dettagli['nome_hotel'] ?? '';
            $id_hotel = $dettagli['id_hotel'] ?? '';

            // Inizializza array errori
            $errori = [];

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $numero_camera = trim($_POST['numero_camera']);
                $posti_letto = trim($_POST['posti_letto']);
                $prezzo_notte = trim($_POST['prezzo_notte']);
                
                // Verifica se la camera esiste già in questo edificio
                $query_verifica = "SELECT numero_camera FROM camere WHERE id_edificio = ? AND numero_camera = ?";
                $stmt = $connessione->prepare($query_verifica);
                $stmt->bind_param("is", $id_edificio, $numero_camera);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $errori[] = "Una camera con questo numero camera esiste già in questo edificio";
                } else {
                    $dati_camera = [
                        'numero_camera' => $numero_camera,
                        'posti_letto' => $posti_letto,
                        'prezzo_notte' => $prezzo_notte,
                        'id_edificio' => $id_edificio
                    ];
                    
                    $regole_validazione = [
                        'numero_camera' => ['required' => true, 'max_length' => 10],
                        'posti_letto' => ['required' => true, 'numeric' => true],
                        'prezzo_notte' => ['required' => true, 'numeric' => true],
                        'id_edificio' => ['required' => true, 'numeric' => true]
                    ];
                    
                    $risultato = inserisci($connessione, 'camere', $dati_camera, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Camera aggiunta con successo!</div>";
                        $_POST = []; // Resetta i campi
                    } else {
                        $errori = array_merge($errori, $risultato['errori']);
                    }
                }
            }
        ?>

        <center><h1>Aggiungi Camera - <?php echo htmlspecialchars($nome_hotel); ?> (<?php echo htmlspecialchars($nome_edificio); ?>)</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_camere.php?id_edificio=<?php echo $id_edificio; ?>&id_hotel=<?php echo $id_hotel; ?>' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_camera.php'>
                <input type='hidden' name='id_edificio' value='<?php echo $id_edificio; ?>'>
                
                <div class='form-group'>
                    <label for='numero_camera'>Numero Camera:</label>
                    <input type='text' id='numero_camera' name='numero_camera' maxlength='10'
                           value="<?php echo htmlspecialchars($_POST['numero_camera'] ?? ''); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='posti_letto'>Posti letto:</label>
                    <input type='number' id='posti_letto' name='posti_letto' min='1'
                           value="<?php echo htmlspecialchars($_POST['posti_letto'] ?? ''); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='prezzo_notte'>Prezzo per notte (€):</label>
                    <input type='number' id='prezzo_notte' name='prezzo_notte' min='0' step='0.01'
                           value="<?php echo htmlspecialchars($_POST['prezzo_notte'] ?? ''); ?>" required>
                </div>
                
                <?php 
                if (!empty($errori)) {
                    foreach ($errori as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                }
                ?>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Camera' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>