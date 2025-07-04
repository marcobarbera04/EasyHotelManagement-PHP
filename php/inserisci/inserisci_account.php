<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();

// Solo l'amministratore (ruolo 1) può accedere
if ($_SESSION['id_ruolo'] != 1) {
    header("Location: ../login/accesso_negato.php");
    exit();
}

// Carica i membri dello staff per il datalist
$query_staff = "SELECT codice_fiscale, nome, cognome FROM staff ORDER BY cognome, nome";
$result_staff = $connessione->query($query_staff);

// Carica i ruoli disponibili
$query_ruoli = "SELECT id_ruolo, nome_ruolo FROM ruoli_account";
$result_ruoli = $connessione->query($query_ruoli);

// Gestione dell'invio del form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dati_account = [
        'codice_fiscale' => $_POST['codice_fiscale'],
        'email' => $_POST['email'],
        'password' => $_POST['password'], // Attenzione: password in chiaro
        'id_ruolo' => $_POST['id_ruolo']
    ];
    
    // Regole di validazione
    $regole_validazione = [
        'codice_fiscale' => ['required' => true, 'max_length' => 16],
        'email' => ['required' => true, 'max_length' => 255],
        'password' => ['required' => true, 'max_length' => 255],
        'id_ruolo' => ['required' => true]
    ];
    
    $errori = [];
    
    // Validazione CF
    if (!preg_match('/^[A-Z0-9]{16}$/', $dati_account['codice_fiscale'])) {
        $errori[] = "Codice Fiscale non valido";
    }
    
    // Validazione email
    if (!filter_var($dati_account['email'], FILTER_VALIDATE_EMAIL)) {
        $errori[] = "Email non valida";
    }
    
    // Verifica se il CF esiste nella tabella staff
    $query_verifica_cf = "SELECT codice_fiscale FROM staff WHERE codice_fiscale = ?";
    $stmt = $connessione->prepare($query_verifica_cf);
    $stmt->bind_param("s", $dati_account['codice_fiscale']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 0) {
        $errori[] = "Il codice fiscale non corrisponde a nessun membro dello staff";
    }
    $stmt->close();
    
    // Verifica se l'email è già usata
    $query_verifica_email = "SELECT id_account FROM accounts WHERE email = ?";
    $stmt = $connessione->prepare($query_verifica_email);
    $stmt->bind_param("s", $dati_account['email']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $errori[] = "Email già utilizzata da un altro account";
    }
    $stmt->close();
    
    if (empty($errori)) {
        $result = inserisci($connessione, 'accounts', $dati_account, $regole_validazione);
        
        if ($result['successo']) {
            echo "<div class='messaggio successo'>Account creato con successo!</div>";
            
            // Se vuoi associare hotel al gestore (ruolo 2)
            if ($dati_account['id_ruolo'] == 2 && !empty($_POST['hotel_gestiti'])) {
                foreach ($_POST['hotel_gestiti'] as $id_hotel) {
                    $query_associazione = "INSERT INTO hotel_gestiti_account (id_account, id_hotel) VALUES (?, ?)";
                    $stmt = $connessione->prepare($query_associazione);
                    $stmt->bind_param("ii", $result['id'], $id_hotel);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            // Resetta i campi
            $_POST = [];
        } else {
            echo "<div class='messaggio errore'>Errore durante la creazione dell'account: " . implode("<br>", $result['errori']) . "</div>";
        }
    } else {
        foreach ($errori as $errore) {
            echo "<div class='messaggio errore'>$errore</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Crea nuovo account</title>
        <link rel="stylesheet" href="../../css/style.css">
        <script>
            function mostraCampiGestore() {
                const ruolo = document.getElementById('id_ruolo').value;
                const campiGestore = document.getElementById('gestore_fields');
                
                if (ruolo == 2) { // Ruolo gestore
                    campiGestore.style.display = 'block';
                } else {
                    campiGestore.style.display = 'none';
                }
            }
        </script>
    </head>
    <body>    
        <h1>Crea nuovo account</h1>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_accounts.php' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_account.php'>
                <div class='form-group'>
                    <label for='codice_fiscale'>Membro dello staff:</label>
                    <input list='lista_staff' id='codice_fiscale' name='codice_fiscale' 
                        placeholder="Cerca per nome, cognome o CF..." required
                        value="<?php echo $_POST['codice_fiscale'] ?? ''; ?>">
                    <datalist id='lista_staff'>
                        <?php while($staff = $result_staff->fetch_assoc()): ?>
                            <option value='<?php echo $staff['codice_fiscale']; ?>'>
                                <?php echo $staff['cognome'] . ' ' . $staff['nome'] . ' (' . $staff['codice_fiscale'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </datalist>
                </div>
                
                <div class='form-group'>
                    <label for='email'>Email:</label>
                    <input type='email' id='email' name='email' required
                        value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>
                
                <div class='form-group'>
                    <label for='password'>Password:</label>
                    <input type='password' id='password' name='password' required
                        value="<?php echo $_POST['password'] ?? ''; ?>">
                </div>
                
                <div class='form-group'>
                    <label for='id_ruolo'>Ruolo:</label>
                    <select id='id_ruolo' name='id_ruolo' required onchange="mostraCampiGestore()">
                        <option value=''>Seleziona un ruolo</option>
                        <?php 
                        $result_ruoli->data_seek(0);
                        while($ruolo = $result_ruoli->fetch_assoc()): ?>
                            <option value='<?php echo $ruolo['id_ruolo']; ?>'
                                <?php echo (isset($_POST['id_ruolo']) && $_POST['id_ruolo'] == $ruolo['id_ruolo']) ? 'selected' : ''; ?>>
                                <?php echo $ruolo['nome_ruolo']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <!-- Campi aggiuntivi per gestori (ruolo 2) -->
                <div id='gestore_fields' style='display: none;'>
                    <div class='form-group'>
                        <label>Hotel gestiti:</label>
                        <?php
                        $query_hotel = "SELECT id_hotel, nome FROM hotel";
                        $result_hotel = $connessione->query($query_hotel);
                        
                        while($hotel = $result_hotel->fetch_assoc()): ?>
                            <div class="checkbox-hotel">
                                <input type='checkbox' id='hotel_<?php echo $hotel['id_hotel']; ?>' 
                                    name='hotel_gestiti[]' value='<?php echo $hotel['id_hotel']; ?>'
                                    <?php echo (isset($_POST['hotel_gestiti'])) && in_array($hotel['id_hotel'], $_POST['hotel_gestiti']) ? 'checked' : ''; ?>>
                                <label for='hotel_<?php echo $hotel['id_hotel']; ?>'><?php echo $hotel['nome']; ?></label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Crea account' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>