<!DOCTYPE html>
<html>
    <head>
        <title>Ospiti della Prenotazione</title>
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

            // Recupera informazioni sulla prenotazione
            $query_prenotazione = "SELECT p.*, h.nome as nome_hotel 
                                 FROM prenotazioni p
                                 JOIN hotel h ON p.id_hotel = h.id_hotel
                                 WHERE p.id_prenotazione = ?";
            $stmt = $connessione->prepare($query_prenotazione);
            $stmt->bind_param("i", $id_prenotazione);
            $stmt->execute();
            $prenotazione = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            if (!$prenotazione) {
                die("Prenotazione non trovata");
            }
            
            echo '<div class="head">';
            echo '<h1>Ospiti della Prenotazione #' . $id_prenotazione . '</h1>';
            echo '<h3>Hotel: ' . $prenotazione['nome_hotel'] . '</h3>';
            echo '<h3>Dal ' . $prenotazione['check_in'] . ' al ' . $prenotazione['check_out'] . '</h3>';
            echo '</div>';

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='javascript:history.back()' class='Redirect'>Indietro</a>";
            echo "</div><br>";

            // Query per ottenere tutti gli ospiti associati alla prenotazione
            $query_ospiti = "SELECT o.* 
                            FROM ospiti o
                            JOIN ospiti_prenotazione ao ON o.codice_fiscale = ao.codice_fiscale
                            WHERE ao.id_prenotazione = ?
                            UNION
                            SELECT o.* 
                            FROM ospiti o
                            WHERE o.codice_fiscale = ?";
            
            $stmt = $connessione->prepare($query_ospiti);
            $stmt->bind_param("is", $id_prenotazione, $prenotazione['codice_fiscale_cliente']);
            $stmt->execute();
            $result_ospiti = $stmt->get_result();
            
            // Mostra la tabella degli ospiti
            echo "<div class='contenitore-tabella'>";
            echo "<table><thead><tr>
                    <th>Codice Fiscale</th>
                    <th>Cognome</th>
                    <th>Nome</th>
                    <th>Età</th>
                    <th>Telefono</th>
                    <th>Indirizzo</th>
                  </tr></thead><tbody>";
            
            while($ospite = $result_ospiti->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($ospite['codice_fiscale']) . "</td>";
                echo "<td>" . htmlspecialchars($ospite['cognome']) . "</td>";
                echo "<td>" . htmlspecialchars($ospite['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($ospite['eta']) . "</td>";
                echo "<td>" . htmlspecialchars($ospite['telefono'] ?? '-') . "</td>";
                echo "<td>" . htmlspecialchars($ospite['indirizzo']) . "</td>";
                echo "</tr>";
                
                // Marca il cliente principale
                if ($ospite['codice_fiscale'] === $prenotazione['codice_fiscale_cliente']) {
                    $cliente_principale = $ospite;
                }
            }
            
            echo "</tbody></table>";
            echo "</div>";
            
            // Mostra informazioni aggiuntive sul cliente principale
            if (isset($cliente_principale)) {
                echo "<div class='contenitore-form' style='margin-top: 20px;'>";
                echo "<h3 style='margin-top: 0;'>Cliente Principale</h3>";
                echo "<div class='form-group'>";
                echo "<p><strong>Nome:</strong> " . htmlspecialchars($cliente_principale['cognome']) . " " . htmlspecialchars($cliente_principale['nome']) . "</p>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<p><strong>Codice Fiscale:</strong> " . htmlspecialchars($cliente_principale['codice_fiscale']) . "</p>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<p><strong>Contatto:</strong> " . htmlspecialchars($cliente_principale['telefono'] ?? 'Non specificato') . "</p>";
                echo "</div>";
                echo "</div>";
            }
            
            $stmt->close();
            $connessione->close();
        ?>
    </body>
</html>