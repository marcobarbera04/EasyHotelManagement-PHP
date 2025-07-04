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
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Visualizza account</title>
        <link rel="stylesheet" href="../../css/style.css">
        <style>
            .checkbox-hotel {
                display: flex;
                align-items: center;
                margin: 5px 0;
            }
            .checkbox-hotel input[type="checkbox"] {
                margin-right: 10px;
            }
        </style>
    </head>
    <body>    
        <?php
            echo "<div class=head><H1>Account del sistema<br></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href=../dashboard.php class='Redirect'>Indietro</a>";
            pulsante_inserimento("../inserisci/inserisci_account.php", "Crea account");
            echo "</div><br>";

            $query = "SELECT a.id_account, a.codice_fiscale, a.email, r.nome_ruolo as ruolo 
                      FROM accounts a
                      JOIN ruoli_account r ON a.id_ruolo = r.id_ruolo";
            
            $bottoni_aggiuntivi = array(
                array(
                    'name' => 'Hotel gestiti', 
                    'file' => 'visualizza_hotel_gestiti.php', 
                    'label' => '&#x1F3E8',
                    'parametro' => 'id_account'
                )
            );
            
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi, array('id_account'));
        ?>
    </body>
</html>