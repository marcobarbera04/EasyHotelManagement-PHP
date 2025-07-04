<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();

// Verifica se è stato passato l'ID prenotazione
$id_prenotazione = $_GET['id_prenotazione'] ?? $_POST['id_prenotazione'] ?? null;
if (!$id_prenotazione) {
    die("ID prenotazione non specificato");
}

// Ottieni i dati della prenotazione
$query_prenotazione = "SELECT * FROM prenotazioni WHERE id_prenotazione = ?";
$stmt = $connessione->prepare($query_prenotazione);
$stmt->bind_param("i", $id_prenotazione);
$stmt->execute();
$result = $stmt->get_result();
$prenotazione = $result->fetch_assoc();
$stmt->close();

if (!$prenotazione) {
    die("Prenotazione non trovata");
}

// Verifica che l'utente abbia i permessi per questa prenotazione
if ($_SESSION['id_ruolo'] == 2) { // Se è un gestore
    $hotel_gestiti = ottieni_hotel_gestiti();
    if (!in_array($prenotazione['id_hotel'], $hotel_gestiti)) {
        die("Non hai i permessi per modificare questa prenotazione");
    }
}

// Gestione del form di modifica
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attiva'])) {
    $nuovo_stato = (int)$_POST['attiva'];
    
    // Query di aggiornamento
    $query_update = "UPDATE prenotazioni SET attiva = ? WHERE id_prenotazione = ?";
    $stmt = $connessione->prepare($query_update);
    $stmt->bind_param("ii", $nuovo_stato, $id_prenotazione);
    
    if ($stmt->execute()) {
        // Ricarica i dati aggiornati
        $stmt->close();
        $stmt = $connessione->prepare($query_prenotazione);
        $stmt->bind_param("i", $id_prenotazione);
        $stmt->execute();
        $result = $stmt->get_result();
        $prenotazione = $result->fetch_assoc();
        
        $messaggio_successo = "Stato prenotazione aggiornato con successo";
    } else {
        $messaggio_errore = "Errore durante l'aggiornamento: " . $connessione->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifica Stato Prenotazione</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="head">
        <h1>Modifica Stato Prenotazione</h1>
    </div>

    <div class="contenitore-pulsanti">
        <a href="../visualizza/visualizza_prenotazioni.php?id_hotel=<?= $prenotazione['id_hotel'] ?>" class="Redirect">Indietro</a>
    </div>

    <?php if (isset($messaggio_errore)): ?>
        <div class="errore"><?= $messaggio_errore ?></div>
    <?php endif; ?>

    <div class="contenitore-form">
        <form method="post">
            <input type="hidden" name="id_prenotazione" value="<?= $id_prenotazione ?>">
            
            <div class="campo-form">
                <label>ID Prenotazione:</label>
                <span><?= htmlspecialchars($prenotazione['id_prenotazione']) ?></span>
            </div>
            
            <div class="campo-form">
                <label>Check-in:</label>
                <span><?= htmlspecialchars($prenotazione['check_in']) ?></span>
            </div>
            
            <div class="campo-form">
                <label>Check-out:</label>
                <span><?= htmlspecialchars($prenotazione['check_out']) ?></span>
            </div>
            
            <div class="campo-form">
                <label for="attiva">Stato Prenotazione:</label>
                <select name="attiva" id="attiva" required>
                    <option value="1" <?= $prenotazione['attiva'] ? 'selected' : '' ?>>Attiva</option>
                    <option value="0" <?= !$prenotazione['attiva'] ? 'selected' : '' ?>>Non attiva</option>
                </select>
            </div>
            
            <div class="campo-form">
                <input type="submit" value="Salva Modifiche" class="pulsante-invio">
            </div>
        </form>
    </div>
</body>
</html>