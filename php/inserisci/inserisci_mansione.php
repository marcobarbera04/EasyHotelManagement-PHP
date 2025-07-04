<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Inserisci Mansione</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            // Inizializza le variabili
            $mansione = '';
            $descrizione = '';

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $dati_mansione = [
                    'mansione' => trim($_POST['mansione']),
                    'descrizione' => trim($_POST['descrizione'])
                ];
                
                // Regole di validazione
                $regole_validazione = [
                    'mansione' => ['required' => true, 'max_length' => 45],
                    'descrizione' => ['required' => true, 'max_length' => 255]
                ];
                
                // Utilizzo della funzione inserisci
                $risultato = inserisci($connessione, 'mansioni', $dati_mansione, $regole_validazione);
                
                if ($risultato['successo']) {
                    echo "<div class='messaggio successo'>Mansione aggiunta con successo! ID: ".$risultato['id']."</div>";
                    // Resetta i campi dopo l'inserimento
                    $mansione = $descrizione = '';
                } else {
                    foreach ($risultato['errori'] as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                    // Mantieni i valori inseriti in caso di errore
                    $mansione = $dati_mansione['mansione'];
                    $descrizione = $dati_mansione['descrizione'];
                }
            }
        ?>

        <center><h1>Nuova Mansione</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_mansioni.php' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_mansione.php'>
                <div class='form-group'>
                    <label for='mansione'>Mansione:</label>
                    <input type='text' id='mansione' name='mansione' maxlength='45' 
                           value="<?php echo htmlspecialchars($mansione); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='descrizione'>Descrizione:</label>
                    <input type='text' id='descrizione' name='descrizione' maxlength='255'
                           value="<?php echo htmlspecialchars($descrizione); ?>" required>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Mansione' class='pulsante-invio'>
                </div>
            </form>
        </div>

    </body>
</html>