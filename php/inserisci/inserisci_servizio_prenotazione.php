<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<?php
// Ottieni ID prenotazione
$id_prenotazione = $_GET['id_prenotazione'] ?? null;
if (!$id_prenotazione) {
    die("ID prenotazione non specificato");
}

// Recupera informazioni sulla prenotazione
$query_prenotazione = "SELECT p.*, h.nome as nome_hotel, 
                      CONCAT(o.cognome, ' ', o.nome) as nome_cliente
                      FROM prenotazioni p
                      JOIN hotel h ON p.id_hotel = h.id_hotel
                      JOIN ospiti o ON p.codice_fiscale_cliente = o.codice_fiscale
                      WHERE p.id_prenotazione = ?";
$stmt = $connessione->prepare($query_prenotazione);
$stmt->bind_param("i", $id_prenotazione);
$stmt->execute();
$prenotazione = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$prenotazione) {
    die("Prenotazione non trovata");
}

// Recupera TUTTI i servizi offerti dall'hotel (rimosso il filtro che escludeva quelli già associati)
$query_servizi = "SELECT s.id_servizio, s.nome_servizio, s.categoria_servizio, s.prezzo
                 FROM servizi_offerti so
                 JOIN servizi s ON so.id_servizio = s.id_servizio
                 WHERE so.id_hotel = ?
                 ORDER BY s.categoria_servizio, s.nome_servizio";
$stmt = $connessione->prepare($query_servizi);
$stmt->bind_param("i", $prenotazione['id_hotel']);
$stmt->execute();
$servizi_disponibili = $stmt->get_result();
$stmt->close();

// Gestione del form
$errori = [];
$successo = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_servizio = $_POST['id_servizio'] ?? null;
    
    // Validazione
    if (empty($id_servizio)) {
        $errori[] = "Seleziona un servizio";
    } else {
        // Verifica che il servizio sia effettivamente disponibile per l'hotel
        $query_verifica = "SELECT 1 FROM servizi_offerti 
                          WHERE id_hotel = ? AND id_servizio = ?";
        $stmt = $connessione->prepare($query_verifica);
        $stmt->bind_param("ii", $prenotazione['id_hotel'], $id_servizio);
        $stmt->execute();
        $esiste = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        
        if (!$esiste) {
            $errori[] = "Il servizio selezionato non è disponibile per questo hotel";
        }
    }
    
    // Se non ci sono errori, procedi con l'inserimento
    if (empty($errori)) {
        $dati = [
            'id_prenotazione' => $id_prenotazione,
            'id_servizio' => $id_servizio
        ];
        
        $risultato = inserisci($connessione, 'servizi_prenotazioni', $dati);
        
        if ($risultato['successo']) {
            $successo = true;
            // Ricarica i servizi disponibili dopo l'inserimento
            $stmt = $connessione->prepare($query_servizi);
            $stmt->bind_param("i", $prenotazione['id_hotel']);
            $stmt->execute();
            $servizi_disponibili = $stmt->get_result();
            $stmt->close();
        } else {
            $errori = array_merge($errori, $risultato['errori']);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aggiungi Servizio alla Prenotazione</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>    
    <div class="head">
        <h1>Aggiungi Servizio alla Prenotazione #<?php echo $id_prenotazione; ?></h1>
        <h3>Hotel: <?php echo htmlspecialchars($prenotazione['nome_hotel']); ?></h3>
        <h3>Cliente: <?php echo htmlspecialchars($prenotazione['nome_cliente']); ?></h3>
        <h3>Periodo: <?php echo $prenotazione['check_in'] . " - " . $prenotazione['check_out']; ?></h3>
    </div>

    <div class='contenitore-pulsanti'>
        <a href='../visualizza/visualizza_servizi_prenotazione.php?id_prenotazione=<?php echo $id_prenotazione; ?>' class='Redirect'>Indietro</a>
    </div>

    <?php if ($successo): ?>
        <div class='messaggio successo'>Servizio aggiunto con successo!</div>
    <?php endif; ?>

    <?php if (!empty($errori)): ?>
        <?php foreach ($errori as $errore): ?>
            <div class='messaggio errore'><?php echo $errore; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class='contenitore-form'>
        <form method='post' action='inserisci_servizio_prenotazione.php?id_prenotazione=<?php echo $id_prenotazione; ?>'>
            <div class='form-group'>
                <label for='id_servizio'>Servizio disponibile:</label>
                <select id='id_servizio' name='id_servizio' required>
                    <option value=''>Seleziona un servizio</option>
                    <?php while($servizio = $servizi_disponibili->fetch_assoc()): ?>
                        <option value='<?php echo $servizio['id_servizio']; ?>'
                            <?php echo (isset($_POST['id_servizio']) && $_POST['id_servizio'] == $servizio['id_servizio']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($servizio['categoria_servizio'] . ' - ' . $servizio['nome_servizio'] . ' (' . $servizio['prezzo'] . '€)'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class='form-group'>
                <input type='submit' value='Aggiungi Servizio' class='pulsante-invio'>
            </div>
        </form>
    </div>
</body>
</html>