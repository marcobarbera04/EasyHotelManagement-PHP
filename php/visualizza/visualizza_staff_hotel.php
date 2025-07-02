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
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = $id_hotel LIMIT 1";
            $nome_hotel = salva_primo_campo($connessione, $query_nome_hotel);

            echo "<div class=head><H1>Staff $nome_hotel<br></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_hotel.php' class='Redirect'>Indietro</a>";
            echo "<a href='../inserisci/inserisci_staff_hotel.php?id_hotel=$id_hotel' class='Redirect aggiungi'>Aggiungi</a>";
            echo "</div><br>";

            $query = "SELECT staff.nome, staff.cognome, staff.eta FROM staff JOIN impieghi_hotel ON impieghi_hotel.codice_fiscale = staff.codice_fiscale WHERE impieghi_hotel.id_hotel = $id_hotel";
            visualizza_tabella($connessione, $query, "modifica_staff.php");
        ?>

    </body>
</html>