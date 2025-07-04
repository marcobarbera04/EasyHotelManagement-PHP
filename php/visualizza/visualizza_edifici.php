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
        <title>Visualizza edifici</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = $id_hotel LIMIT 1";
            $nome_hotel = salva_primo_campo($connessione, $query_nome_hotel);

            echo "<div class=head><H1>Edifici $nome_hotel<br></div>";
            
            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_hotel.php' class='Redirect'>Indietro</a>";
            echo "<a href='../inserisci/inserisci_edificio.php?id_hotel=$id_hotel' class='Redirect aggiungi'>Aggiungi</a>";
            echo "</div><br>";

            $query = "SELECT id_hotel, id_edificio, nome FROM edifici WHERE id_hotel = $id_hotel";
            $bottoni_aggiuntivi = array(
                array('name' => 'Visulizza Camere', 'file' => 'visualizza_camere.php', 'label' => '&#128716;')
            );
            $campi_nascosti = array('id_edificio', 'id_hotel');
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi, $campi_nascosti);
        ?>
    </body>
</html>