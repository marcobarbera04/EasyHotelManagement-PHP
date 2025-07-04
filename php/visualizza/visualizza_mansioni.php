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
        <title>Visualizza mansioni</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            echo "<div class=head><H1>Mansioni<br></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href= ../dashboard.php class='Redirect'>Indietro</a>";
            pulsante_inserimento("../inserisci/inserisci_mansione.php", "Nuova mansione");
            echo "</div><br>";

            $query = "SELECT mansione, descrizione FROM mansioni";
            visualizza_tabella($connessione, $query, "");
        ?>

    </body>
</html>