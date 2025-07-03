<!DOCTYPE html>
<html>
    <head>
        <title>Servizi della Prenotazione</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            // Ottieni id_prenotazione da GET
            $id_prenotazione = $_GET['id_prenotazione'] ?? null;
            
            if (!$id_prenotazione) {
                die("ID prenotazione non specificato");
            }

            // Recupera informazioni sulla prenotazione (incluso id_hotel)
            $query_prenotazione = "SELECT p.*, h.nome as nome_hotel, h.id_hotel,
                                 CONCAT(o.cognome, ' ', o.nome) as nome_cliente
                                 FROM prenotazioni p
                                 JOIN hotel h ON p.id_hotel = h.id_hotel
                                 JOIN ospiti o ON p.codice_fiscale_cliente = o.codice_fiscale
                                 WHERE p.id_prenotazione = ?";
            $stmt = $connessione->prepare($query_prenotazione);
            $stmt->bind_param("i", $id_prenotazione);
            $stmt->execute();
            $prenotazione = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            if (!$prenotazione) {
                die("Prenotazione non trovata");
            }

            $id_hotel = $prenotazione['id_hotel'];
            
            echo '<div class="head">';
            echo '<h1>Servizi della Prenotazione #' . $id_prenotazione . '</h1>';
            echo '<h3>Hotel: ' . htmlspecialchars($prenotazione['nome_hotel']) . '</h3>';
            echo '<h3>Cliente: ' . htmlspecialchars($prenotazione['nome_cliente']) . '</h3>';
            echo '<h3>Dal ' . htmlspecialchars($prenotazione['check_in']) . ' al ' . htmlspecialchars($prenotazione['check_out']) . '</h3>';
            echo '</div>';

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='../visualizza/visualizza_prenotazioni.php?id_hotel=" . $id_hotel . "' class='Redirect'>Indietro</a>";
            echo "<a href='../inserisci/inserisci_servizio_prenotazione.php?id_prenotazione=".$id_prenotazione."' class='Redirect aggiungi'>Aggiungi Servizio</a>";
            echo "</div><br>";

            // Query per i servizi (senza parametri per la visualizzazione tabella)
            $query_servizi = "SELECT sp.id_servizio_prenotazione, sp.id_servizio, 
                             s.nome_servizio, s.categoria_servizio, s.prezzo 
                             FROM servizi_prenotazioni sp
                             JOIN servizi s ON sp.id_servizio = s.id_servizio
                             WHERE sp.id_prenotazione = $id_prenotazione";
            
            // Bottoni aggiuntivi per eliminazione
            $bottoni_aggiuntivi = array(
                array(
                    'name' => 'Elimina',
                    'file' => '../elimina/elimina_servizio_prenotazione.php',
                    'label' => 'Elimina',
                    'class' => 'pulsante-elimina',
                    'parametri_extra' => array(
                        'id_prenotazione' => $id_prenotazione,
                        'id_hotel' => $id_hotel
                    )
                )
            );
            
            // Campi da nascondere
            $campi_nascosti = array('id_servizio_prenotazione', 'id_servizio');
            
            // Calcola il totale
            $query_totale = "SELECT SUM(s.prezzo) as totale
                            FROM servizi_prenotazioni sp
                            JOIN servizi s ON sp.id_servizio = s.id_servizio
                            WHERE sp.id_prenotazione = $id_prenotazione";
            $totale = $connessione->query($query_totale)->fetch_assoc()['totale'];
            
            // Visualizza la tabella usando la funzione (con query senza parametri)
            visualizza_tabella(
                $connessione, 
                $query_servizi, 
                "", // Nessuna modifica diretta
                $bottoni_aggiuntivi,
                $campi_nascosti,
                "servizi_prenotazioni", // Tabella per eliminazione
                "id_servizio_prenotazione", // Campo chiave primaria
                array(
                    'id_prenotazione' => $id_prenotazione,
                    'id_hotel' => $id_hotel
                ) // Parametri extra
            );
            
            // Mostra il totale
            echo "<div class='totale-servizi'>";
            echo "<strong>TOTALE SERVIZI: " . ($totale ?? '0') . " €</strong>";
            echo "</div>";
        ?>
    </body>
</html>