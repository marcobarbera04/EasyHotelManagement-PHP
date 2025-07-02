<!DOCTYPE html>
<html>
    <head>
        <title>Servizi Offerti</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            // Ottieni id_hotel da GET o POST
            $id_hotel = $_GET['id_hotel'] ?? $_POST['id_hotel'] ?? null;
            
            if (!$id_hotel) {
                die("ID hotel non specificato");
            }

            // Query per il nome dell'hotel
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = ? LIMIT 1";
            $stmt = $connessione->prepare($query_nome_hotel);
            $stmt->bind_param("i", $id_hotel);
            $stmt->execute();
            $nome_hotel = $stmt->get_result()->fetch_assoc()['nome'] ?? '';
            
            echo "<div class=head><h1>Servizi Offerti - $nome_hotel</h1></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_hotel.php' class='Redirect'>Indietro</a>";
            echo "<a href='../inserisci/inserisci_servizio_offerto.php?id_hotel=$id_hotel' class='Redirect aggiungi'>Aggiungi Servizio</a>";
            echo "</div><br>";

            // Query per visualizzare i servizi offerti dall'hotel
            $query = "SELECT s.id_servizio, s.nome_servizio, s.categoria_servizio, s.prezzo 
                      FROM servizi_offerti so
                      JOIN servizi s ON so.id_servizio = s.id_servizio
                      WHERE so.id_hotel = $id_hotel";
            
            $bottoni_aggiuntivi = array();
            
            $campi_nascosti = array('id_servizio', 'id_hotel');
            
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi, $campi_nascosti);
        ?>
    </body>
</html>