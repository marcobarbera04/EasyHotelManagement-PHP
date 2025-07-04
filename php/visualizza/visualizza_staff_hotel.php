<?php
require_once "../login/db.php";
require_once "../login/funzioni_autorizzazione.php";
require_once "../funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Visualizza staff</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            // Ottieni id_hotel da GET o POST
            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            
            if (!$id_hotel) {
                die("ID hotel non specificato");
            }

            // Query per il nome dell'hotel con prepared statement
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = ? LIMIT 1";
            $stmt = $connessione->prepare($query_nome_hotel);
            $stmt->bind_param("i", $id_hotel);
            $stmt->execute();
            $nome_hotel = $stmt->get_result()->fetch_assoc()['nome'] ?? '';
            
            echo "<div class=head><h1>Staff $nome_hotel</h1></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_hotel.php' class='Redirect'>Indietro</a>";
            echo "<a href='../inserisci/inserisci_staff_hotel.php?id_hotel=$id_hotel' class='Redirect aggiungi'>Aggiungi</a>";
            echo "</div><br>";

            // Query per visualizzare lo staff
            $query = "SELECT impieghi_hotel.id_impiego, staff.nome, staff.cognome, staff.eta 
                      FROM staff 
                      JOIN impieghi_hotel ON impieghi_hotel.codice_fiscale = staff.codice_fiscale 
                      WHERE impieghi_hotel.id_hotel = $id_hotel";
            
            $campi_nascosti = array('id_impiego');
            visualizza_tabella($connessione, $query, "", array(), $campi_nascosti, "impieghi_hotel", "id_impiego", array('id_hotel' => $id_hotel));
        ?>
    </body>
</html>