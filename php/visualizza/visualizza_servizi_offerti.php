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

            // Query modificata per includere id_servizio_offerto
            $query = "SELECT so.id_servizio_offerto, s.nome_servizio, s.categoria_servizio, s.prezzo 
                      FROM servizi_offerti so
                      JOIN servizi s ON so.id_servizio = s.id_servizio
                      WHERE so.id_hotel = $id_hotel";
            
            $bottoni_aggiuntivi = array();
            
            // Nascondi solo id_servizio_offerto (lo usiamo come PK per l'eliminazione)
            $campi_nascosti = array('id_servizio_offerto');
            
            // Specifica la tabella servizi_offerti e la PK id_servizio_offerto
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi, $campi_nascosti, "servizi_offerti", "id_servizio_offerto", array('id_hotel' => $id_hotel));
        ?>
    </body>
</html>