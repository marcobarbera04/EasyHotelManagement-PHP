<!DOCTYPE html>
<html>
    <head>
        <title>Visualizza telefono</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>    
        <?php
            include "db.php";
            include "funzioni.php";

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
            
            echo "<center><h1>Telefoni - $nome_hotel</h1></center>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_hotel.php' class='Redirect'>Indietro</a>";
            echo "<a href='inserisci_telefono.php?id_hotel=$id_hotel' class='Redirect aggiungi'>Aggiungi</a>";
            echo "</div><br>";

            // Query diretta con l'ID già inserito (sicura perché abbiamo già validato $id_hotel)
            $query = "SELECT numero, descrizione FROM telefono WHERE id_hotel = $id_hotel";
            $bottoni_aggiuntivi = array();
            $campi_nascosti = array();
            visualizza_tabella($connessione, $query, "modifica_telefono.php", $bottoni_aggiuntivi, $campi_nascosti);
        ?>
    </body>
</html>