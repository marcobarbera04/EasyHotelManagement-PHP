<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Aggiungi Edificio</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
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
                $nome = trim($_POST['nome']);
                
                // Verifica se l'edificio esiste già per questo hotel
                $query_verifica = "SELECT nome FROM edifici WHERE id_hotel = ? AND nome = ?";
                $stmt = $connessione->prepare($query_verifica);
                $stmt->bind_param("is", $id_hotel, $nome);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $errori[] = "Un edificio con questo nome esiste già per questo hotel";
                } else {
                    $dati_edificio = [
                        'nome' => $nome,
                        'id_hotel' => $id_hotel
                    ];
                    
                    $regole_validazione = [
                        'nome' => ['required' => true, 'max_length' => 45],
                        'id_hotel' => ['required' => true, 'numeric' => true]
                    ];
                    
                    $risultato = inserisci($connessione, 'edifici', $dati_edificio, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Edificio aggiunto con successo!</div>";
                        $_POST = []; // Resetta i campi
                    } else {
                        $errori = array_merge($errori, $risultato['errori']);
                    }
                }
            }
        ?>

        <center><h1>Aggiungi Edificio - <?php echo htmlspecialchars($nome_hotel); ?></h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_edifici.php?id_hotel=<?php echo $id_hotel; ?>' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_edificio.php'>
                <input type='hidden' name='id_hotel' value='<?php echo $id_hotel; ?>'>
                
                <div class='form-group'>
                    <label for='nome'>Nome Edificio:</label>
                    <input type='text' id='nome' name='nome' maxlength='45'
                           value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
                    <?php 
                    if (!empty($errori)) {
                        foreach ($errori as $errore) {
                            echo "<div class='messaggio errore'>$errore</div>";
                        }
                    }
                    ?>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Edificio' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>