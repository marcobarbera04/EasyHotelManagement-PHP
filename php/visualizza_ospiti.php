<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza ospiti</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            //includere script per connettersi al DB
            include "db.php";

            //includere script con le funzioni
            include "funzioni.php";

            echo "<center><H1>Ospiti<br></center>";

            echo "<div class = 'contenitore-redirect'><a href= ../index.html class='Redirect'>Indietro</a></div><br>";

            $query = "SELECT * FROM ospiti";
            visualizza_tabella($connessione, $query, "modifica_hotel.php");
        ?>

    </body>
</html>