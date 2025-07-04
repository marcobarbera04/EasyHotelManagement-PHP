<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();

// Solo admin o gestori degli hotel possono modificare
if ($_SESSION['id_ruolo'] != 1 && $_SESSION['id_ruolo'] != 2) {
    header("Location: ../../login/accesso_negato.php");
    exit();
}

$id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;

if (!$id_hotel) {
    header("Location: ../visualizza_hotel.php");
    exit();
}

// Verifica se il gestore può modificare questo hotel (solo per ruolo 2)
if ($_SESSION['id_ruolo'] == 2 && !in_array($id_hotel, $_SESSION['hotel_gestiti'])) {
    header("Location: ../../login/accesso_negato.php");
    exit();
}

// Carica i dati attuali dell'hotel
$query = "SELECT nome, via FROM hotel WHERE id_hotel = ?";
$stmt = $connessione->prepare($query);
$stmt->bind_param("i", $id_hotel);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: ../visualizza_hotel.php");
    exit();
}

$hotel = $result->fetch_assoc();
$stmt->close();

// Inizializza le variabili con i valori attuali
$nome = $hotel['nome'];
$via = $hotel['via'];

// Gestione dell'invio del form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dati_hotel = [
        'nome' => trim($_POST['nome']),
        'via' => trim($_POST['via'])
    ];
    
    // Regole di validazione
    $regole_validazione = [
        'nome' => ['required' => true, 'max_length' => 45],
        'via' => ['required' => true, 'max_length' => 128]
    ];
    
    $errori = [];
    
    // Validazione
    foreach ($regole_validazione as $campo => $regole) {
        if ($regole['required'] && empty($dati_hotel[$campo])) {
            $errori[] = "Il campo $campo è obbligatorio";
        }
        
        if (isset($regole['max_length']) && strlen($dati_hotel[$campo]) > $regole['max_length']) {
            $errori[] = "Il campo $campo non può superare {$regole['max_length']} caratteri";
        }
    }
    
    if (empty($errori)) {
        $query = "UPDATE hotel SET nome = ?, via = ? WHERE id_hotel = ?";
        $stmt = $connessione->prepare($query);
        $stmt->bind_param("ssi", $dati_hotel['nome'], $dati_hotel['via'], $id_hotel);
        
        if ($stmt->execute()) {
            // Simple refresh to show updated data
            header("Location: modifica_hotel.php?id_hotel=" . $id_hotel);
            exit();
        } else {
            $errori[] = "Errore durante l'aggiornamento: " . $connessione->error;
        }
        
        $stmt->close();
    }

    // Reimposta i valori da mostrare nel form in caso di errore
    $nome = $dati_hotel['nome'];
    $via = $dati_hotel['via'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Modifica Hotel</title>
        <link rel="stylesheet" href="../../../css/style.css">
    </head>
    <body>    
        <?php
        // Mostra solo errori se presenti
        if (isset($errori)) {
            foreach ($errori as $errore) {
                echo "<div class='messaggio errore'>$errore</div>";
            }
        }
        ?>
        
        <center><h1>Modifica Hotel</h1></center>

        <div class='contenitore-pulsanti'>
            <a href='../visualizza/visualizza_hotel.php' class='Redirect'>Indietro</a>
        </div>

        <div class='contenitore-form'>
            <form method='post' action='modifica_hotel.php'>
                <input type='hidden' name='id_hotel' value='<?php echo htmlspecialchars($id_hotel); ?>'>
                
                <div class='form-group'>
                    <label for='nome'>Nome Hotel:</label>
                    <input type='text' id='nome' name='nome' maxlength='45' 
                           value="<?php echo htmlspecialchars($nome); ?>" required>
                </div>
                
                <div class='form-group'>
                    <label for='via'>Indirizzo:</label>
                    <input type='text' id='via' name='via' maxlength='128'
                           value="<?php echo htmlspecialchars($via); ?>" required>
                </div>
                
                <div class='form-group'>
                    <input type='submit' value='Salva Modifiche' class='pulsante-invio'>
                </div>
            </form>
        </div>
    </body>
</html>