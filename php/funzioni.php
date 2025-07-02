<?php 
    function visualizza_tabella($connessione, $query, $modifica_file, $bottoni_aggiuntivi = array(), $campi_nascosti = array()) {
    $sql = "$query";

    if ($result = $connessione->query($sql)) {
        echo "<div class='contenitore-tabella'>";
        echo "<table><thead><tr>";

        // Intestazioni delle tabelle (escludi i campi nascosti)
        $fields = $result->fetch_fields();
        foreach($fields as $field) {
            if(!in_array($field->name, $campi_nascosti)) {
                echo "<th>$field->name</th>";
            }
        }

        // Aggiungi intestazioni per i pulsanti aggiuntivi
        foreach ($bottoni_aggiuntivi as $button) {
            echo "<th>$button[name]</th>";
        }

        echo "<th>Modifica</th>";
        echo "</tr></thead><tbody>";

        // Dati della tabella
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            
            // Mostra solo i campi non nascosti
            foreach($row as $key => $value) {
                if(!in_array($key, $campi_nascosti)) {
                    echo "<td>$value</td>";
                }
            }

            // Pulsanti aggiuntivi (includi TUTTI i campi nei hidden)
            foreach ($bottoni_aggiuntivi as $button) {
                echo "<td><form action='{$button['file']}' method='post'>";
                foreach ($row as $key => $value) {
                    echo "<input type='hidden' name='$key' value='$value'>";
                }
                echo "<input type='submit' style='border: none; background: none; cursor: pointer;' value='{$button['label']}'></form></td>";
            }

            // Bottone modifica (includi TUTTI i campi nei hidden)
            echo "<td><form action='$modifica_file' method='post'>";
            foreach($row as $key => $value) {
                echo "<input type='hidden' name='$key' value='$value'>";
            }
            echo "<input type='submit' value='Modifica'></form></td>";

            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    } else {
        echo "Errore nella query: " . $connessione->error;
    }

    $connessione->close();
}

    function visualizza_edifici($connessione, $query, $modifica_file, $bottoni_aggiuntivi = array(), $campi_nascosti = array('id_edificio', 'id_hotel')) {
    $sql = "$query";

    if ($result = $connessione->query($sql)) {
        echo "<div class='contenitore-tabella'>";
        echo "<table><thead><tr>";

        // Intestazioni delle tabelle (escludi i campi nascosti)
        $fields = $result->fetch_fields();
        foreach($fields as $field) {
            if(!in_array($field->name, $campi_nascosti)) {
                echo "<th>$field->name</th>";
            }
        }

        // Aggiungi intestazioni per i pulsanti aggiuntivi
        foreach ($bottoni_aggiuntivi as $button) {
            echo "<th>$button[name]</th>";
        }

        echo "<th>Modifica</th>";
        echo "</tr></thead><tbody>";

        // Dati della tabella
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            
            // Mostra solo i campi non nascosti
            foreach($row as $key => $value) {
                if(!in_array($key, $campi_nascosti)) {
                    echo "<td>$value</td>";
                }
            }

            // Pulsanti aggiuntivi (includi TUTTI i campi nei hidden)
            foreach ($bottoni_aggiuntivi as $button) {
                echo "<td><form action='{$button['file']}' method='post'>";
                foreach ($row as $key => $value) {
                    echo "<input type='hidden' name='$key' value='$value'>";
                }
                // Questi due hidden sono ridondanti (già inclusi nel foreach sopra)
                // echo "<input type='hidden' name='id_edificio' value='{$row['id_edificio']}'>";
                // echo "<input type='hidden' name='id_hotel' value='{$row['id_hotel']}'>";
                echo "<input type='submit' style='border: none; background: none; cursor: pointer;' value='{$button['label']}'></form></td>";
            }

            // Bottone modifica (includi TUTTI i campi nei hidden)
            echo "<td><form action='$modifica_file' method='post'>";
            foreach($row as $key => $value) {
                echo "<input type='hidden' name='$key' value='$value'>";
            }
            echo "<input type='submit' value='Modifica'></form></td>";

            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    } else {
        echo "Errore nella query: " . $connessione->error;
    }

    $connessione->close();
}

    function salva_primo_campo($connessione, $query){
        if($result = $connessione->query($query)){
            $row = $result->fetch_assoc();
            return reset($row); // restituisce il primo campo dell'array associativo
        }else{
            return null;
        }
    }

    function pulsante_inserimento($pagina_inserimento, $testo_pulsante = "Aggiungi") {
        echo "<a href='$pagina_inserimento' class='Redirect aggiungi'>$testo_pulsante</a>";
    }

    function inserisci($connessione, $tabella, $dati, $regole_validazione = []) {
        // Validazione
        if (!empty($regole_validazione)) {
            $errori = [];
            
            foreach ($regole_validazione as $campo => $regole) {
                $valore = $dati[$campo] ?? null;
                
                if (!empty($regole['required']) && empty($valore)) {
                    $errori[] = "Il campo $campo è obbligatorio";
                    continue;
                }
                
                if (isset($regole['max_length']) && strlen($valore) > $regole['max_length']) {
                    $errori[] = "Il campo $campo non può superare {$regole['max_length']} caratteri";
                }
            }
            
            if (!empty($errori)) {
                return ['successo' => false, 'errori' => $errori];
            }
        }
        
        // Preparazione query
        $campi = array_keys($dati);
        $valori = array_values($dati);
        $segnaposti = str_repeat('?,', count($campi) - 1) . '?';
        
        $query = "INSERT INTO $tabella (" . implode(', ', $campi) . ") VALUES ($segnaposti)";
        $stmt = $connessione->prepare($query);
        
        if (!$stmt) {
            return ['successo' => false, 'errori' => ["Errore preparazione query: " . $connessione->error]];
        }
        
        // Determina tipi parametri
        $tipi = '';
        foreach ($valori as $valore) {
            if (is_int($valore)) {
                $tipi .= 'i';
            } elseif (is_float($valore)) {
                $tipi .= 'd';
            } else {
                $tipi .= 's';
            }
        }
        
        // Esegui query
        $stmt->bind_param($tipi, ...$valori);
        
        if ($stmt->execute()) {
            $id_inserito = $connessione->insert_id;
            $stmt->close();
            return ['successo' => true, 'id' => $id_inserito];
        } else {
            $stmt->close();
            return ['successo' => false, 'errori' => ["Errore esecuzione query: " . $stmt->error]];
        }
    }
?>