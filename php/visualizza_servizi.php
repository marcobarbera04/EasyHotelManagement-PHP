<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza servizi</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            //includere script per connettersi al DB
            include "db.php";

            //includere script con le funzioni
            include "funzioni.php";

            echo "<center><H1>Servizi<br></center>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href= ../index.html class='Redirect'>Indietro</a>";
            pulsante_inserimento("inserisci_servizio.php", "Aggiungi");
            echo "</div><br>";

            $bottoni_aggiuntivi = array();
            $campi_nascosti = array('id_servizio');
    
            $query = "SELECT * FROM servizi";
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi ,$campi_nascosti);
        ?>

    </body>
</html>