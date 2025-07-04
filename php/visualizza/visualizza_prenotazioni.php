<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Visualizza prenotazioni</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            // Ottieni id_hotel da GET o POST
            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            
            if (!$id_hotel) {
                die("ID hotel non specificato");
            }

            // Query corretta per il nome dell'hotel
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = '$id_hotel' LIMIT 1";
            $nome_hotel = salva_primo_campo($connessione, $query_nome_hotel);
            
            if (!$nome_hotel) {
                die("Hotel non trovato");
            }
            
            echo '<div class="head">';
            echo '<h1>Prenotazioni ' . $nome_hotel . '</h1>';
            echo '</div>';

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_hotel.php' class='Redirect'>Indietro</a>";
            echo "<a href='../inserisci/inserisci_prenotazione.php?id_hotel=$id_hotel' class='Redirect aggiungi'>Aggiungi</a>";
            echo "</div><br>";

            $bottoni_aggiuntivi = array(
                array(
                    'name' => 'Visualizza Fattura', 
                    'file' => 'visualizza_fattura.php', 
                    'label' => '&#x1F9FE',
                    'parametro' => 'id_prenotazione' // Specifica quale campo usare come parametro GET
                ),
                array(
                    'name' => 'Visualizza ospiti',
                    'file' => 'visualizza_ospiti_prenotazione.php',
                    'label' => '&#x1F465',
                    'parametro' => 'id_prenotazione'
                ),
                array(
                    'name' => 'Visualizza Servizi', 
                    'file' => 'visualizza_servizi_prenotazione.php', 
                    'label' => '&#128718',
                    'parametro' => 'id_prenotazione' // Specifica quale campo usare come parametro GET
                )
            );
            
            $campi_nascosti = array('id_prenotazione', 'id_hotel');
            $query = "SELECT * FROM prenotazioni WHERE id_hotel = '$id_hotel'";
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi, $campi_nascosti);
        ?>
    </body>
</html>