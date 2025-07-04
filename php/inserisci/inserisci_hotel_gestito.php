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

$id_account = $_GET['id_account'] ?? null;

if (!$id_account) {
    header("Location: visualizza_accounts.php");
    exit();
}

// Verifica che l'account sia un gestore (ruolo 2)
$query_ruolo = "SELECT id_ruolo FROM accounts WHERE id_account = ?";
$stmt = $connessione->prepare($query_ruolo);
$stmt->bind_param("i", $id_account);
$stmt->execute();
$ruolo = $stmt->get_result()->fetch_assoc()['id_ruolo'];
$stmt->close();

if ($ruolo != 2) {
    header("Location: visualizza_hotel_gestiti.php?id_account=".$id_account);
    exit();
}

// Gestione dell'invio del form
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['hotel_selezionati'])) {
    $connessione->begin_transaction();
    
    try {
        $query_inserimento = "INSERT INTO hotel_gestiti_account (id_account, id_hotel) VALUES (?, ?)";
        $stmt = $connessione->prepare($query_inserimento);
        
        foreach ($_POST['hotel_selezionati'] as $id_hotel) {
            $stmt->bind_param("ii", $id_account, $id_hotel);
            $stmt->execute();
        }
        
        $connessione->commit();
        header("Location: ../visualizza/visualizza_hotel_gestiti.php?id_account=".$id_account);
        exit();
    } catch (Exception $e) {
        $connessione->rollback();
        $errore = "Errore durante l'assegnazione degli hotel: " . $e->getMessage();
    }
}

// Recupera i dettagli dell'account
$query_account = "SELECT email FROM accounts WHERE id_account = ?";
$stmt = $connessione->prepare($query_account);
$stmt->bind_param("i", $id_account);
$stmt->execute();
$email_account = $stmt->get_result()->fetch_assoc()['email'];
$stmt->close();

// Recupera tutti gli hotel non ancora assegnati a questo account
$query_hotel = "SELECT h.id_hotel, h.nome 
               FROM hotel h
               WHERE h.id_hotel NOT IN (
                   SELECT id_hotel FROM hotel_gestiti_account WHERE id_account = ?
               )";
$stmt = $connessione->prepare($query_hotel);
$stmt->bind_param("i", $id_account);
$stmt->execute();
$hotel_disponibili = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Assegna hotel</title>
        <link rel="stylesheet" href="../../css/style.css">
        <style>
            .checkbox-hotel {
                display: flex;
                align-items: center;
                margin: 5px 0;
                padding: 8px;
                background: #f5f5f5;
                border-radius: 4px;
            }
            .checkbox-hotel:hover {
                background: #e9e9e9;
            }
            .contenitore-checkbox {
                max-height: 400px;
                overflow-y: auto;
                margin: 20px 0;
            }
        </style>
    </head>
    <body>    
        <div class="head">
            <h1>Assegna hotel a: <?php echo htmlspecialchars($email_account); ?></h1>
        </div>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_hotel_gestiti.php?id_account=<?php echo $id_account; ?>' class='Redirect'>Indietro</a>
        </div>

        <?php if (isset($errore)): ?>
            <div class='messaggio errore'><?php echo $errore; ?></div>
        <?php endif; ?>

        <div class='contenitore-form'>
            <form method='post' action='inserisci_hotel_gestito.php?id_account=<?php echo $id_account; ?>'>
                <input type='hidden' name='id_account' value='<?php echo $id_account; ?>'>
                
                <div class='form-group'>
                    <label>Seleziona gli hotel da assegnare:</label>
                    
                    <?php if ($hotel_disponibili->num_rows > 0): ?>
                        <div class='contenitore-checkbox'>
                            <?php while($hotel = $hotel_disponibili->fetch_assoc()): ?>
                                <div class='checkbox-hotel'>
                                    <input type='checkbox' id='hotel_<?php echo $hotel['id_hotel']; ?>' 
                                        name='hotel_selezionati[]' value='<?php echo $hotel['id_hotel']; ?>'>
                                    <label for='hotel_<?php echo $hotel['id_hotel']; ?>'>
                                        <?php echo htmlspecialchars($hotel['nome']); ?> (ID: <?php echo $hotel['id_hotel']; ?>)
                                    </label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class='messaggio'>Nessun hotel disponibile da assegnare</div>
                    <?php endif; ?>
                </div>
                
                <?php if ($hotel_disponibili->num_rows > 0): ?>
                    <div class='form-group'>
                        <input type='submit' value='Assegna hotel selezionati' class='pulsante-invio'>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </body>
</html>