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

// Recupera i dettagli completi dell'account
$query_account = "SELECT a.codice_fiscale, a.email, r.id_ruolo, r.nome_ruolo 
                 FROM accounts a
                 JOIN ruoli_account r ON a.id_ruolo = r.id_ruolo
                 WHERE a.id_account = ?";
$stmt = $connessione->prepare($query_account);
$stmt->bind_param("i", $id_account);
$stmt->execute();
$account = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Determina se l'account è un amministratore
$is_admin = ($account['id_ruolo'] == 1);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Hotel gestiti</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            echo "<div class=head><H1>Hotel gestiti da: ".htmlspecialchars($account['email'])."</H1></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_accounts.php' class='Redirect'>Indietro</a>";
            
            // Mostra il pulsante "Aggiungi hotel" solo per i gestori
            if (!$is_admin) {
                echo "<a href='../inserisci/inserisci_hotel_gestito.php?id_account=".$id_account."' class='Redirect aggiungi'>Assegna Hotel in Gestione</a>";
            }
            echo "</div><br>";

            if ($is_admin) {
                echo "<div class='messaggio info'>";
                echo "Questo account è un <strong>Amministratore</strong> e può gestire tutti gli hotel del sistema.";
                echo "</div>";
                
                $query_all_hotels = "SELECT id_hotel, nome FROM hotel";
                $all_hotels = $connessione->query($query_all_hotels);
                
                if ($all_hotels->num_rows > 0) {
                    visualizza_tabella($connessione, $query_all_hotels, "", array(), array());
                }
            } else {
                // Query per gli hotel gestiti
                $query = "SELECT h.id_hotel, h.nome 
                         FROM hotel h
                         JOIN hotel_gestiti_account hga ON h.id_hotel = hga.id_hotel
                         WHERE hga.id_account = $id_account";
                
                $result = $connessione->query($query);
                
                if ($result->num_rows > 0) {
                    visualizza_tabella(
                        $connessione, 
                        $query,
                        "", 
                        array(), 
                        array('id_hotel'), 
                        "hotel_gestiti_account", 
                        "id_hotel", 
                        array('id_account' => $id_account)
                    );
                } else {
                    echo "<div class='messaggio'>Nessun hotel gestito da questo account</div>";
                }
            }
        ?>
    </body>
</html>