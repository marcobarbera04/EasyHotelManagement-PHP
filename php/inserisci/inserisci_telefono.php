<!DOCTYPE html>
<html>
    <head>
        <title>Aggiungi Telefono</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            
            // Ottieni il nome dell'hotel
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = ? LIMIT 1";
            $stmt = $connessione->prepare($query_nome_hotel);
            $stmt->bind_param("i", $id_hotel);
            $stmt->execute();
            $nome_hotel = $stmt->get_result()->fetch_assoc()['nome'] ?? '';

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $numero = trim($_POST['numero']);
                $descrizione = trim($_POST['descrizione']);
                
                // Verifica se il numero esiste già
                $query_verifica = "SELECT numero FROM telefono WHERE numero = ?";
                $stmt = $connessione->prepare($query_verifica);
                $stmt->bind_param("s", $numero);
                $stmt->execute();
                
                if ($stmt->get_result()->num_rows > 0) {
                    $errori[] = "Questo numero di telefono è già registrato nel sistema";
                } else {
                    $dati_telefono = [
                        'numero' => $numero,
                        'descrizione' => $descrizione,
                        'id_hotel' => $id_hotel
                    ];
                    
                    $regole_validazione = [
                        'numero' => ['required' => true, 'max_length' => 10],
                        'descrizione' => ['required' => true, 'max_length' => 255],
                        'id_hotel' => ['required' => true]
                    ];
                    
                    $risultato = inserisci($connessione, 'telefono', $dati_telefono, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Telefono aggiunto con successo!</div>";
                        $_POST = []; // Resetta i campi
                    } else {
                        foreach ($risultato['errori'] as $errore) {
                            echo "<div class='messaggio errore'>$errore</div>";
                        }
                    }
                }
            }
        ?>

        <center><h1>Aggiungi Telefono - <?php echo htmlspecialchars($nome_hotel); ?></h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_telefono.php?id_hotel=<?php echo $id_hotel; ?>' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_telefono.php'>
                <input type='hidden' name='id_hotel' value='<?php echo $id_hotel; ?>'>
                
                <div class='form-group'>
                    <label for='numero'>Numero di telefono:</label>
                    <input type='text' id='numero' name='numero' maxlength='10' 
                           value="<?php echo htmlspecialchars($_POST['numero'] ?? ''); ?>" required>
                    <?php if (isset($errori) && in_array("Questo numero di telefono è già registrato nel sistema", $errori)): ?>
                        <div class='messaggio errore'>Questo numero è già registrato</div>
                    <?php endif; ?>
                </div>
                
                <div class='form-group'>
                    <label for='descrizione'>Descrizione:</label>
                    <input type='text' id='descrizione' name='descrizione' maxlength='255'
                           value="<?php echo htmlspecialchars($_POST['descrizione'] ?? ''); ?>" required
                           placeholder="Es: Segreteria, Reception, Direzione...">
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Telefono' class='pulsante-invio'>
                </div>
            </form>
        </div>

    </body>
</html>