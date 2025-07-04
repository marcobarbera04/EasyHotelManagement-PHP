<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza ospiti</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            echo "<div class=head><H1>Ospiti<br></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href= ../dashboard.php class='Redirect'>Indietro</a>";
            pulsante_inserimento("../inserisci/inserisci_ospite.php", "Aggiungi");
            echo "</div><br>";

            $query = "SELECT * FROM ospiti";
            visualizza_tabella($connessione, $query, "");
        ?>

    </body>
</html>