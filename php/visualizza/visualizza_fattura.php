<!DOCTYPE html>
<html>
    <head>
        <title>Dettagli Fattura</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            // Ottieni l'ID della prenotazione dalla query string
            $id_prenotazione = $_GET['id_prenotazione'] ?? null;
            
            if (!$id_prenotazione) {
                die("<div class='messaggio errore'>ID prenotazione non specificato</div>");
            }

            // Query per ottenere i dettagli della fattura
            $query_fattura = "SELECT f.*, tp.metodo_pagamento, p.check_in, p.check_out, 
                            CONCAT(o.nome, ' ', o.cognome) AS nome_ospite,
                            c.numero_camera, c.prezzo_notte,
                            h.nome AS nome_hotel,
                            p.id_hotel /* Aggiungi questo campo */
                            FROM fatture f
                            JOIN tipi_pagamento tp ON f.id_tipo_pagamento = tp.id_tipo_pagamento
                            JOIN prenotazioni p ON f.id_prenotazione = p.id_prenotazione
                            JOIN ospiti o ON p.codice_fiscale_cliente = o.codice_fiscale
                            JOIN camere c ON p.numero_camera = c.numero_camera
                            JOIN edifici e ON c.id_edificio = e.id_edificio
                            JOIN hotel h ON e.id_hotel = h.id_hotel
                            WHERE f.id_prenotazione = ?";
            
            $stmt = $connessione->prepare($query_fattura);
            $stmt->bind_param("i", $id_prenotazione);
            $stmt->execute();
            $result_fattura = $stmt->get_result();
            
            if ($result_fattura->num_rows === 0) {
                die("<div class='messaggio errore'>Nessuna fattura trovata per questa prenotazione</div>");
            }
            
            $fattura = $result_fattura->fetch_assoc();
            $stmt->close();
            
            // Calcola il numero di notti
            $check_in = new DateTime($fattura['check_in']);
            $check_out = new DateTime($fattura['check_out']);
            $notti = $check_in->diff($check_out)->days;
            
            // Stile per la fattura
            echo "<style>
                .fattura-container {
                    max-width: 800px;
                    margin: 20px auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    background-color: white;
                }
                .intestazione-fattura {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 30px;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 20px;
                }
                .dettagli-fattura {
                    margin-bottom: 30px;
                }
                .riga-fattura {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 10px;
                }
                .totale-fattura {
                    font-weight: bold;
                    font-size: 1.2em;
                    border-top: 2px solid #000;
                    padding-top: 10px;
                    margin-top: 20px;
                }
                .metodo-pagamento {
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #eee;
                }
            </style>";
            
            // Pulsante per tornare indietro
            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_prenotazioni.php?id_hotel=" . $fattura['id_hotel'] . "' class='Redirect'>Torna alle prenotazioni</a>";
            echo "</div>";

            // Contenuto della fattura
            echo "<div class='fattura-container'>";
            
            // Intestazione
            echo "<div class='intestazione-fattura'>";
            echo "<div>";
            echo "<h2>" . htmlspecialchars($fattura['nome_hotel']) . "</h2>";
            echo "<p>Fattura #" . htmlspecialchars($fattura['id_fattura']) . "</p>";
            echo "<p>Data emissione: " . date('d/m/Y', strtotime($fattura['data_emissione'])) . "</p>";
            echo "</div>";
            echo "<div>";
            echo "<h3>Dettagli Prenotazione</h3>";
            echo "<p>Prenotazione #" . htmlspecialchars($fattura['id_prenotazione']) . "</p>";
            echo "<p>Camera: " . htmlspecialchars($fattura['numero_camera']) . "</p>";
            echo "<p>Check-in: " . date('d/m/Y', strtotime($fattura['check_in'])) . "</p>";
            echo "<p>Check-out: " . date('d/m/Y', strtotime($fattura['check_out'])) . "</p>";
            echo "</div>";
            echo "</div>";
            
            // Dettagli cliente
            echo "<div class='dettagli-fattura'>";
            echo "<h3>Cliente</h3>";
            echo "<p>" . htmlspecialchars($fattura['nome_ospite']) . "</p>";
            echo "</div>";
            
            // Dettagli servizi
            echo "<div class='dettagli-fattura'>";
            echo "<h3>Dettagli</h3>";
            
            // Costo camera
            echo "<div class='riga-fattura'>";
            echo "<span>Soggiorno (" . $notti . " notti)</span>";
            echo "<span>€" . number_format($fattura['prezzo_notte'], 2) . " x " . $notti . "</span>";
            echo "</div>";
            
            // Servizi aggiuntivi (se presenti)
            $query_servizi = "SELECT s.nome_servizio, s.prezzo 
                             FROM servizi_prenotazioni sp
                             JOIN servizi s ON sp.id_servizio = s.id_servizio
                             WHERE sp.id_prenotazione = ?";
            
            $stmt = $connessione->prepare($query_servizi);
            $stmt->bind_param("i", $id_prenotazione);
            $stmt->execute();
            $result_servizi = $stmt->get_result();
            
            $totale_servizi = 0;
            while ($servizio = $result_servizi->fetch_assoc()) {
                echo "<div class='riga-fattura'>";
                echo "<span>" . htmlspecialchars($servizio['nome_servizio']) . "</span>";
                echo "<span>€" . number_format($servizio['prezzo'], 2) . "</span>";
                echo "</div>";
                $totale_servizi += $servizio['prezzo'];
            }
            $stmt->close();
            
            // Totale
            echo "<div class='riga-fattura totale-fattura'>";
            echo "<span>TOTALE</span>";
            echo "<span>€" . number_format($fattura['importo_totale'], 2) . "</span>";
            echo "</div>";
            echo "</div>";
            
            // Metodo di pagamento
            echo "<div class='metodo-pagamento'>";
            echo "<h3>Metodo di pagamento</h3>";
            echo "<p>" . htmlspecialchars($fattura['metodo_pagamento']) . "</p>";
            
            // Dettagli aggiuntivi in base al metodo di pagamento
            if ($fattura['metodo_pagamento'] == 'Carta di credito' && $fattura['numero_carta']) {
                echo "<p>Carta terminante con: " . substr($fattura['numero_carta'], -4) . "</p>";
            } elseif ($fattura['metodo_pagamento'] == 'Bonifico' && $fattura['numero_conto_corrente']) {
                echo "<p>Conto corrente: " . htmlspecialchars($fattura['numero_conto_corrente']) . "</p>";
            } elseif ($fattura['metodo_pagamento'] == 'Contanti') {
                echo "<p>Pagamento effettuato in contanti</p>";
            }
            echo "</div>";
            
            echo "</div>"; // Chiude fattura-container
        ?>
    </body>
</html>