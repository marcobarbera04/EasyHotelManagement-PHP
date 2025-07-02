<!DOCTYPE html>
<html>
    <head>
        <title>Inserisci Staff</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            include "db.php";
            include "funzioni.php";

            // Inizializza le variabili
            $codice_fiscale = '';
            $nome = '';
            $cognome = '';
            $eta = '';

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $dati_staff = [
                    'codice_fiscale' => trim($_POST['codice_fiscale']),
                    'nome' => trim($_POST['nome']),
                    'cognome' => trim($_POST['cognome']),
                    'eta' => trim($_POST['eta'])
                ];
                
                // Verifica se il codice fiscale esiste già
                $query_verifica = "SELECT codice_fiscale FROM staff WHERE codice_fiscale = ?";
                $stmt = $connessione->prepare($query_verifica);
                $stmt->bind_param("s", $dati_staff['codice_fiscale']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    echo "<div class='messaggio errore'>Un membro dello staff con questo codice fiscale esiste già!</div>";
                } else {
                    // Regole di validazione
                    $regole_validazione = [
                        'codice_fiscale' => ['required' => true, 'max_length' => 16, 'exact_length' => 16],
                        'nome' => ['required' => true, 'max_length' => 45],
                        'cognome' => ['required' => true, 'max_length' => 45],
                        'eta' => ['required' => true, 'numeric' => true, 'min_value' => 18, 'max_value' => 70]
                    ];
                    
                    // Utilizzo della funzione inserisci
                    $risultato = inserisci($connessione, 'staff', $dati_staff, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Membro dello staff aggiunto con successo! ID: ".$risultato['id']."</div>";
                        // Resetta i campi dopo l'inserimento
                        $codice_fiscale = $nome = $cognome = $eta = '';
                    } else {
                        foreach ($risultato['errori'] as $errore) {
                            echo "<div class='messaggio errore'>$errore</div>";
                        }
                        // Mantieni i valori inseriti in caso di errore
                        $codice_fiscale = $dati_staff['codice_fiscale'];
                        $nome = $dati_staff['nome'];
                        $cognome = $dati_staff['cognome'];
                        $eta = $dati_staff['eta'];
                    }
                }
            }
        ?>

        <center><h1>Nuovo Membro dello Staff</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='visualizza_staff.php' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_staff.php'>
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
                    <input type='number' id='eta' name='eta' min='18' max='70'
                           value="<?php echo htmlspecialchars($eta); ?>" required>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Staff' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>