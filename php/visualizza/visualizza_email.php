<!DOCTYPE html>
<html>
    <head>
        <title>Visualizza email</title>
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

            // Query per il nome dell'hotel con prepared statement
            $query_nome_hotel = "SELECT nome FROM hotel WHERE id_hotel = ? LIMIT 1";
            $stmt = $connessione->prepare($query_nome_hotel);
            $stmt->bind_param("i", $id_hotel);
            $stmt->execute();
            $nome_hotel = $stmt->get_result()->fetch_assoc()['nome'] ?? '';
            
            echo "<div class=head><h1>Email - $nome_hotel</h1></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_hotel.php' class='Redirect'>Indietro</a>";
            echo "<a href='../inserisci/inserisci_email.php?id_hotel=$id_hotel' class='Redirect aggiungi'>Aggiungi</a>";
            echo "</div><br>";

            // Query diretta con l'ID già inserito (sicura perché abbiamo già validato $id_hotel)
            $query = "SELECT email, descrizione FROM email WHERE id_hotel = $id_hotel";
            $bottoni_aggiuntivi = array();
            $campi_nascosti = array();
            visualizza_tabella($connessione, $query, "", $bottoni_aggiuntivi, $campi_nascosti, "email", "email", array('id_hotel' => $id_hotel));
        ?>
    </body>
</html>