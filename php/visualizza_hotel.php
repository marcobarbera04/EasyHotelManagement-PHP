<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza hotel</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            //includere script per connettersi al DB
            include "db.php";

            //includere script con le funzioni
            include "funzioni.php";

            echo "<center><H1>Tutti hotel<br></center>";

            echo "<div class = 'contenitore-redirect'><a href= ../index.html class='Redirect'>Indietro</a></div><br>";

            $query = "SELECT id_hotel, nome, via FROM hotel";
            $bottoni_aggiuntivi = array(
                array('name' => 'Visulizza Prenotazioni', 'file' => 'visualizza_prenotazioni.php', 'label' => 'Visualizza Prenotazioni'),
                array('name' => 'Visualizza Telefono', 'file' => 'visualizza_telefono.php', 'label' => 'Visualizza Telefono'),
                array('name' => 'Visualizza Email', 'file' => 'visualizza_email.php', 'label' => 'Visualizza Email'),
                array('name' => 'Visulizza Staff', 'file' => 'visualizza_staff.php', 'label' => 'Visualizza Staff')
            );
            visualizza_tabella($connessione, $query, "modifica_hotel.php", $bottoni_aggiuntivi);
        ?>

    </body>
</html>