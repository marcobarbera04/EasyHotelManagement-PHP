<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza edifici</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            //includere script per connettersi al DB
            include "db.php";

            //includere script con le funzioni
            include "funzioni.php";

            $id_hotel = $_POST['id_hotel'];
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = $id_hotel LIMIT 1";
            $nome_hotel = salva_primo_campo($connessione, $query_nome_hotel);
            echo "<center><H1>Edifici $nome_hotel<br></center>";
            
            echo "<div class = 'contenitore-redirect'><a href= visualizza_hotel.php class='Redirect'>Indietro</a></div><br>";

            $query = "SELECT id_hotel, id_edificio, nome FROM edifici WHERE id_hotel = $id_hotel";
            $bottoni_aggiuntivi = array(
                array('name' => 'Visulizza Camere', 'file' => 'visualizza_camere.php', 'label' => '&#128269;')
            );
            visualizza_edifici($connessione, $query, "modifica_staff.php", $bottoni_aggiuntivi);
        ?>

    </body>
</html>