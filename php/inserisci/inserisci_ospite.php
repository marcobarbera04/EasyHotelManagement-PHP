<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Inserisci Ospite</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            // Inizializza le variabili
            $codice_fiscale = '';
            $nome = '';
            $cognome = '';
            $eta = '';
            $telefono = '';
            $indirizzo = '';

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $dati_ospite = [
                    'codice_fiscale' => trim($_POST['codice_fiscale']),
                    'nome' => trim($_POST['nome']),
                    'cognome' => trim($_POST['cognome']),
                    'eta' => trim($_POST['eta']),
                    'telefono' => trim($_POST['telefono']),
                    'indirizzo' => trim($_POST['indirizzo'])
                ];
                
                // Regole di validazione
                $regole_validazione = [
                    'codice_fiscale' => ['required' => true, 'max_length' => 16, 'exact_length' => 16],
                    'nome' => ['required' => true, 'max_length' => 45],
                    'cognome' => ['required' => true, 'max_length' => 45],
                    'eta' => ['required' => true, 'numeric' => true, 'min_value' => 0, 'max_value' => 120],
                    'telefono' => ['required' => true, 'max_length' => 16],
                    'indirizzo' => ['required' => true, 'max_length' => 255]
                ];
                
                // Utilizzo della funzione inserisci
                $risultato = inserisci($connessione, 'ospiti', $dati_ospite, $regole_validazione);
                
                if ($risultato['successo']) {
                    echo "<div class='messaggio successo'>Ospite aggiunto con successo! ID: ".$risultato['id']."</div>";
                    // Resetta i campi dopo l'inserimento
                    $codice_fiscale = $nome = $cognome = $eta = $telefono = $indirizzo = '';
                } else {
                    foreach ($risultato['errori'] as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                    // Mantieni i valori inseriti in caso di errore
                    $codice_fiscale = $dati_ospite['codice_fiscale'];
                    $nome = $dati_ospite['nome'];
                    $cognome = $dati_ospite['cognome'];
                    $eta = $dati_ospite['eta'];
                    $telefono = $dati_ospite['telefono'];
                    $indirizzo = $dati_ospite['indirizzo'];
                }
            }
        ?>

        <center><h1>Nuovo Ospite</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_ospiti.php' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_ospite.php'>
                <div class='form-group'>
                    <label for='codice_fiscale'>Codice Fiscale:</label>
                    <input type='text' id='codice_fiscale' name='codice_fiscale' maxlength='16'
                           value="<?php echo htmlspecialchars($codice_fiscale); ?>" required
                           placeholder="Inserisci 16 caratteri">
                </div>
                
                <div class='form-group'>
                    <label for='nome'>Nome:</label>
                    <input type='text' id='nome' name='nome' maxlength='45'
                           value="<?php echo htmlspecialchars($nome); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='cognome'>Cognome:</label>
                    <input type='text' id='cognome' name='cognome' maxlength='45'
                           value="<?php echo htmlspecialchars($cognome); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='eta'>Età:</label>
                    <input type='number' id='eta' name='eta' min='0' max='120'
                           value="<?php echo htmlspecialchars($eta); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='telefono'>Telefono:</label>
                    <input type='text' id='telefono' name='telefono' maxlength='16'
                           value="<?php echo htmlspecialchars($telefono); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='indirizzo'>Indirizzo:</label>
                    <input type='text' id='indirizzo' name='indirizzo' maxlength='255'
                           value="<?php echo htmlspecialchars($indirizzo); ?>" required>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Ospite' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>