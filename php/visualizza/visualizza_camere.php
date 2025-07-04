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
        <title>Visualizza camere</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
        $id_edificio = $_GET['id_edificio'] ?? $_POST['id_edificio'] ?? null;
        $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;

        $query_nome_edificio = "SELECT nome FROM edifici WHERE id_edificio = $id_edificio LIMIT 1";
        $nome_hotel = salva_primo_campo($connessione, $query_nome_edificio);
        echo "<div class=head><H1>Camere $nome_hotel<br></div>";

        //bottone per andare indietro creato con un form e con post perche' dobbiamo mandare alla pagina precedente $id_hotel e a href lo puo fare solo con il get
        echo "<div class='contenitore-pulsanti'>";
        echo "<form action='visualizza_edifici.php' method='post'>
                    <input type='hidden' name='id_hotel' value='$id_hotel'>
                    <input type='submit' class='Indietro' value='Indietro'>
                </form>";
        echo "<a href='../inserisci/inserisci_camera.php?id_edificio=$id_edificio' class='Redirect aggiungi'>Nuova Camera</a>";
        echo "</div><br>";

        $query = "SELECT numero_camera, posti_letto, prezzo_notte FROM camere WHERE id_edificio = $id_edificio";
        visualizza_tabella($connessione, $query, "");
        ?>
    </body>
</html>