<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza prenotazioni</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            //includere script per connettersi al DB
            include "db.php";

            //includere script con le funzioni
            include "funzioni.php";

            $id_hotel = $_POST['id_hotel'];
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = 1 LIMIT 1";
            $nome_hotel = salva_primo_campo($connessione, $query_nome_hotel);
            echo "<center><H1>Prenotazioni $nome_hotel<br></center>";

            echo "<div class = 'contenitore-redirect'><a href= visualizza_hotel.php class='Redirect'>Indietro</a></div><br>";

            $query = "SELECT id_prenotazione, check_in, check_out, attiva, numero_camera, codice_fiscale_cliente FROM prenotazioni WHERE id_hotel = $id_hotel";
            visualizza_tabella($connessione, $query, "");
        ?>

    </body>
</html>