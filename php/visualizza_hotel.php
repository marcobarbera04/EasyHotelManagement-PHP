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

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='../index.html' class='Redirect'>Indietro</a>";
            pulsante_inserimento("inserisci_hotel.php", "Aggiungi");
            echo "</div><br>";

            $query = "SELECT id_hotel, nome, via FROM hotel";
            $bottoni_aggiuntivi = array(
                array('name' => 'Visulizza Prenotazioni', 'file' => 'visualizza_prenotazioni.php', 'label' => '&#128269;'),
                array('name' => 'Visualizza Telefono', 'file' => 'visualizza_telefono.php', 'label' => '&#128269'),
                array('name' => 'Visualizza Email', 'file' => 'visualizza_email.php', 'label' => '&#128269'),
                array('name' => 'Visulizza Edifici', 'file' => 'visualizza_edifici.php', 'label' => '&#128269;'),
                array('name' => 'Visulizza Staff', 'file' => 'visualizza_staff_hotel.php', 'label' => '&#128269'),
                array('name' => 'Visulizza Servizi offerti', 'file' => 'visualizza_servizi_offerti.php', 'label' => '&#128269')
            );
            $campi_nascosti = array('id_hotel');
            visualizza_tabella($connessione, $query, "modifica_hotel.php", $bottoni_aggiuntivi, $campi_nascosti);
        ?>

    </body>
</html>