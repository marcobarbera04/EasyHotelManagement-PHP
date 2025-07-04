<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Aggiungi Mansione a Staff</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            $codice_fiscale = $_GET['codice_fiscale'] ?? $_POST['codice_fiscale'] ?? null;
            
            if (!$codice_fiscale) {
                die("Codice fiscale non specificato");
            }

            // Ottieni i dati del membro dello staff
            $query_staff = "SELECT nome, cognome FROM staff WHERE codice_fiscale = ? LIMIT 1";
            $stmt = $connessione->prepare($query_staff);
            $stmt->bind_param("s", $codice_fiscale);
            $stmt->execute();
            $staff = $stmt->get_result()->fetch_assoc();

            // Carica tutte le mansioni disponibili
            $query_mansioni = "SELECT mansione, descrizione FROM mansioni ORDER BY mansione";
            $result_mansioni = $connessione->query($query_mansioni);

            // Inizializza array errori e variabile mansione_esistente
            $errori = [];
            $mansione_esistente = null;

            // Gestione dell'invio del form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $mansione = trim($_POST['mansione']);
                
                // Verifica se la mansione è già assegnata al membro dello staff
                $query_verifica = "SELECT m.descrizione 
                                 FROM mansioni_staff ms
                                 JOIN mansioni m ON ms.mansione = m.mansione
                                 WHERE ms.codice_fiscale = ? AND ms.mansione = ?";
                $stmt = $connessione->prepare($query_verifica);
                $stmt->bind_param("ss", $codice_fiscale, $mansione);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $mansione_esistente = $result->fetch_assoc();
                    $errori[] = "Questa mansione (" . $mansione_esistente['descrizione'] . ") è già assegnata a questo membro dello staff";
                } else {
                    $dati_mansione = [
                        'codice_fiscale' => $codice_fiscale,
                        'mansione' => $mansione
                    ];
                    
                    $regole_validazione = [
                        'codice_fiscale' => ['required' => true, 'max_length' => 16, 'exact_length' => 16],
                        'mansione' => ['required' => true, 'max_length' => 45]
                    ];
                    
                    $risultato = inserisci($connessione, 'mansioni_staff', $dati_mansione, $regole_validazione);
                    
                    if ($risultato['successo']) {
                        echo "<div class='messaggio successo'>Mansione aggiunta con successo!</div>";
                        $_POST = []; // Resetta i campi
                    } else {
                        $errori = array_merge($errori, $risultato['errori']);
                    }
                }
            }
        ?>

        <center><h1>Aggiungi Mansione a <?php echo htmlspecialchars($staff['nome'] . ' ' . $staff['cognome']); ?></h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_mansioni_staff.php?codice_fiscale=<?php echo $codice_fiscale; ?>' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_mansione_staff.php'>
                <input type='hidden' name='codice_fiscale' value='<?php echo $codice_fiscale; ?>'>
                
                <div class='form-group'>
                    <label for='mansione'>Mansione:</label>
                    <select id='mansione' name='mansione' required>
                        <option value=''>Seleziona una mansione</option>
                        <?php while($mansione = $result_mansioni->fetch_assoc()): ?>
                            <option value='<?php echo $mansione['mansione']; ?>'
                                <?php echo (isset($_POST['mansione']) && $_POST['mansione'] == $mansione['mansione']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mansione['mansione']); // Solo il nome della mansione ?>
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
                    <input type='submit' value='Aggiungi Mansione' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>