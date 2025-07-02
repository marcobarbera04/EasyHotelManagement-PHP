<!DOCTYPE html>
<html>
    <head>
        <title>Aggiungi Email</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            
            // Inizializza l'array degli errori
            $errori = [];
            
            // Ottieni il nome dell'hotel
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = ? LIMIT 1";
            $stmt = $connessione->prepare($query_nome_hotel);
            $stmt->bind_param("i", $id_hotel);
            $stmt->execute();
            $nome_hotel = $stmt->get_result()->fetch_assoc()['nome'] ?? '';

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = trim($_POST['email'] ?? '');
                $descrizione = trim($_POST['descrizione'] ?? '');
                
                // Verifica se l'email esiste già
                $query_verifica = "SELECT email FROM email WHERE email = ?";
                $stmt = $connessione->prepare($query_verifica);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                
                if ($stmt->get_result()->num_rows > 0) {
                    $errori[] = "Questa email è già registrata nel sistema";
                } else {
                    $dati_email = [
                        'email' => $email,
                        'descrizione' => $descrizione,
                        'id_hotel' => $id_hotel
                    ];
                    
                    $regole_validazione = [
                        'email' => ['required' => true, 'max_length' => 100, 'email' => true],
                        'descrizione' => ['required' => true, 'max_length' => 45],
                        'id_hotel' => ['required' => true]
                    ];
                    
                    $risultato = inserisci($connessione, 'email', $dati_email, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Email aggiunta con successo!</div>";
                        $_POST = []; // Resetta i campi
                    } else {
                        foreach ($risultato['errori'] as $errore) {
                            echo "<div class='messaggio errore'>$errore</div>";
                        }
                    }
                }
            }
        ?>

        <center><h1>Aggiungi Email - <?php echo htmlspecialchars($nome_hotel); ?></h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_email.php?id_hotel=<?php echo $id_hotel; ?>' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_email.php'>
                <input type='hidden' name='id_hotel' value='<?php echo $id_hotel; ?>'>
                
                <div class='form-group'>
                    <label for='email'>Email:</label>
                    <input type='text' id='email' name='email' maxlength='100' 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    <?php if (isset($errori) && in_array("Questa email è già registrata nel sistema", $errori)): ?>
                        <div class='messaggio errore'>Questa email è già registrata</div>
                    <?php endif; ?>
                </div>
                
                <div class='form-group'>
                    <label for='descrizione'>Descrizione:</label>
                    <input type='text' id='descrizione' name='descrizione' maxlength='45'
                           value="<?php echo htmlspecialchars($_POST['descrizione'] ?? ''); ?>" required
                           placeholder="Es: Prenotazioni, Info, Direzione...">
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Email' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>