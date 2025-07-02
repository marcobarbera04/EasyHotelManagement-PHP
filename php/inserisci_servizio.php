<!DOCTYPE html>
<html>
    <head>
        <title>Inserisci Servizio</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            include "db.php";
            include "funzioni.php";

            // Inizializza le variabili
            $nome_servizio = '';
            $categoria_servizio = '';
            $prezzo = '';

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $dati_servizio = [
                    'nome_servizio' => trim($_POST['nome_servizio']),
                    'categoria_servizio' => trim($_POST['categoria_servizio']),
                    'prezzo' => trim($_POST['prezzo'])
                ];
                
                // Regole di validazione
                $regole_validazione = [
                    'nome_servizio' => ['required' => true, 'max_length' => 45],
                    'categoria_servizio' => ['required' => true, 'max_length' => 45],
                    'prezzo' => ['required' => true, 'numeric' => true]
                ];
                
                // Utilizzo della funzione inserisci
                $risultato = inserisci($connessione, 'servizi', $dati_servizio, $regole_validazione);
                
                if ($risultato['successo']) {
                    echo "<div class='messaggio successo'>Servizio aggiunto con successo! ID: ".$risultato['id']."</div>";
                    // Resetta i campi dopo l'inserimento
                    $nome_servizio = $categoria_servizio = $prezzo = '';
                } else {
                    foreach ($risultato['errori'] as $errore) {
                        echo "<div class='messaggio errore'>$errore</div>";
                    }
                    // Mantieni i valori inseriti in caso di errore
                    $nome_servizio = $dati_servizio['nome_servizio'];
                    $categoria_servizio = $dati_servizio['categoria_servizio'];
                    $prezzo = $dati_servizio['prezzo'];
                }
            }
        ?>

        <center><h1>Nuovo Servizio</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='visualizza_servizi.php' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_servizio.php'>
                <div class='form-group'>
                    <label for='nome_servizio'>Nome Servizio:</label>
                    <input type='text' id='nome_servizio' name='nome_servizio' maxlength='45' 
                           value="<?php echo htmlspecialchars($nome_servizio); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='categoria_servizio'>Categoria:</label>
                    <input type='text' id='categoria_servizio' name='categoria_servizio' maxlength='45'
                           value="<?php echo htmlspecialchars($categoria_servizio); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='prezzo'>Prezzo (€):</label>
                    <input type='number' id='prezzo' name='prezzo' step='0.01' min='0'
                           value="<?php echo htmlspecialchars($prezzo); ?>" required>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Servizio' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>