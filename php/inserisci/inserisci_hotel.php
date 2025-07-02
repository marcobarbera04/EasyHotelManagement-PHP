<!DOCTYPE html>
<html>
    <head>
        <title>Inserisci Hotel</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            // Inizializza le variabili
            $nome = '';
            $via = '';

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $dati_hotel = [
                    'nome' => trim($_POST['nome']),
                    'via' => trim($_POST['via'])
                ];
                
                // Regole di validazione
                $regole_validazione = [
                    'nome' => ['required' => true, 'max_length' => 45],
                    'via' => ['required' => true, 'max_length' => 128]
                ];
                
                // Utilizzo della funzione inserisci
                $risultato = inserisci($connessione, 'hotel', $dati_hotel, $regole_validazione);
                
                if ($risultato['successo']) {
                    echo "<div class='messaggio successo'>Hotel aggiunto con successo! ID: ".$risultato['id']."</div>";
                    // Resetta i campi dopo l'inserimento
                    $nome = $via = '';
                } else {
                    foreach ($risultato['errori'] as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                    // Mantieni i valori inseriti in caso di errore
                    $nome = $dati_hotel['nome'];
                    $via = $dati_hotel['via'];
                }
            }
        ?>

        <center><h1>Nuovo Hotel</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_hotel.php' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_hotel.php'>
                <div class='form-group'>
                    <label for='nome'>Nome Hotel:</label>
                    <input type='text' id='nome' name='nome' maxlength='45' 
                           value="<?php echo htmlspecialchars($nome); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='via'>Indirizzo:</label>
                    <input type='text' id='via' name='via' maxlength='128'
                           value="<?php echo htmlspecialchars($via); ?>" required>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Hotel' class='pulsante-invio'>
                </div>
            </form>
        </div>

    </body>
</html>