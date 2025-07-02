<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza tipi pagamento</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            echo "<div class=head><H1>Tipi pagamento<br></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href= ../dashboard.php class='Redirect'>Indietro</a>";
            echo "</div><br>";

            $bottoni_aggiuntivi = array();
            $campi_nascosti = array('id_tipo_pagamento');
    
            $query = "SELECT * FROM tipi_pagamento";
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi ,$campi_nascosti);
        ?>

    </body>
</html>