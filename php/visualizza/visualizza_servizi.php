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
        <title>Visualizza servizi</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            echo "<div class=head><H1>Servizi<br></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href= ../dashboard.php class='Redirect'>Indietro</a>";
            pulsante_inserimento("../inserisci/inserisci_servizio.php", "Nuovo Servizio");
            echo "</div><br>";

            $bottoni_aggiuntivi = array();
            $campi_nascosti = array('id_servizio');
    
            $query = "SELECT * FROM servizi";
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi ,$campi_nascosti);
        ?>

    </body>
</html>