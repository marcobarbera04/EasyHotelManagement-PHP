<?php
session_start();
include "php/login/db.php";

// Controlla se l'utente è già loggato
if (isset($_SESSION['id_account'])) {
    // Verifica se dashboard.php esiste nella cartella php/
    if (file_exists('php/dashboard.php')) {
        header("Location: php/dashboard.php");
    } else {
        // Alternativa se il file non esiste
        header("Location: dashboard.php");
    }
    exit();
} 

if (isset($_SESSION['id_account'])) {
    header("Location: php/dashboard.php");
    exit();
}

$messaggio_errore = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $connessione->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Password in chiaro
    
    $query = "SELECT a.id_account, a.password, a.id_ruolo, r.nome_ruolo, 
                     GROUP_CONCAT(hga.id_hotel) AS hotel_gestiti
              FROM accounts a 
              JOIN ruoli_account r ON a.id_ruolo = r.id_ruolo
              LEFT JOIN hotel_gestiti_account hga ON a.id_account = hga.id_account
              WHERE a.email = ?
              GROUP BY a.id_account";
    
    $stmt = $connessione->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $account = $result->fetch_assoc();
        
        // Verifica la password in chiaro
        if ($password === $account['password']) {
            $_SESSION['id_account'] = $account['id_account'];
            $_SESSION['id_ruolo'] = $account['id_ruolo'];
            $_SESSION['nome_ruolo'] = $account['nome_ruolo'];
            
            // Memorizza gli hotel gestiti (solo per gestori)
            if ($account['id_ruolo'] == 2 && !empty($account['hotel_gestiti'])) {
                $_SESSION['hotel_gestiti'] = explode(',', $account['hotel_gestiti']);
            }
            
            // Reindirizza in base al ruolo
            header("Location: php/dashboard.php");
            exit();
        } else {
            $messaggio_errore = "Password non valida";
        }
    } else {
        $messaggio_errore = "Account non trovato";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EHM - Accesso al Sistema</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="head">
        <H1>EHM - Easy Hotel Management</H1>
    </div>
    
    <div class="contenitore-form">
        <h2 style="text-align: center; margin-top: 0;">Accesso al sistema</h2>
        
        <?php if ($messaggio_errore): ?>
            <div class="errore messaggio"><?php echo $messaggio_errore; ?></div>
        <?php endif; ?>
        
        <form method="post" action="index.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div style="text-align: center;">
                <button type="submit" class="pulsante-invio">Accedi</button>
            </div>
        </form>
    </div>
</body>
</html>