<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza camere</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
        //includere script per connettersi al DB
        include "db.php";

        //includere script con le funzioni
        include "funzioni.php";

        $id_edificio = $_POST['id_edificio'];

        $id_hotel = $_POST['id_hotel'];
        $query_nome_edificio = "SELECT nome FROM edifici WHERE id_edificio = $id_edificio LIMIT 1";
        $nome_hotel = salva_primo_campo($connessione, $query_nome_edificio);
        echo "<center><H1>Camere $nome_hotel<br></center>";

        //bottone per andare indietro creato con un form e con post perche' dobbiamo mandare alla pagina precedente $id_hotel e a href lo puo fare solo con il get
        echo "<div class = 'contenitore-redirect'>
                <form action='visualizza_edifici.php' method='post'>
                    <input type='hidden' name='id_hotel' value='$id_hotel'>
                    <input type='submit' class='Indietro' value='Indietro'>
                </form>
              </div><br>";

        $query = "SELECT numero_camera FROM camere WHERE id_edificio = $id_edificio";
        visualizza_tabella($connessione, $query, "");
        ?>
    </body>
</html>