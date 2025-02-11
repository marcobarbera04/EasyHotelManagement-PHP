<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza staff</title>
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
            echo "<center><H1>Staff $nome_hotel<br></center>";

            echo "<div class = 'contenitore-redirect'><a href= visualizza_hotel.php class='Redirect'>Indietro</a></div><br>";

            $query = "SELECT staff.nome, staff.cognome, staff.eta FROM staff JOIN impieghi_hotel ON impieghi_hotel.codice_fiscale = staff.codice_fiscale WHERE impieghi_hotel.id_hotel = $id_hotel";
            visualizza_tabella($connessione, $query, "modifica_staff.php");
        ?>

    </body>
</html>