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
        <title>Visualizza staff</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            $query_nome_hotel = "SELECT * FROM hotel";

            echo "<div class=head><H1>Staff completo<br></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href= ../dashboard.php class='Redirect'>Indietro</a>";
            pulsante_inserimento("../inserisci/inserisci_staff.php", "Nuovo Membro Staff");
            echo "</div><br>";

            $query = "SELECT * FROM staff";
            $bottoni_aggiuntivi = array(
                array(
                    'name' => 'Visualizza Mansioni', 
                    'file' => 'visualizza_mansioni_staff.php', 
                    'label' => '&#x1F4BC',
                    'parametro' => 'codice_fiscale'  // Specifica quale campo passare come parametro
                )
            );
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi);
        ?>

    </body>
</html>