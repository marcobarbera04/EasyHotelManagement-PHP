<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza tipi pagamento</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            //includere script per connettersi al DB
            include "db.php";

            //includere script con le funzioni
            include "funzioni.php";

            echo "<center><H1>Tipi pagamento<br></center>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href= ../index.html class='Redirect'>Indietro</a>";
            pulsante_inserimento("inserisci_tipo_pagamento.php", "Aggiungi");
            echo "</div><br>";

            $bottoni_aggiuntivi = array();
            $campi_nascosti = array('id_tipo_pagamento');
    
            $query = "SELECT * FROM tipi_pagamento";
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi ,$campi_nascosti);
        ?>

    </body>
</html>