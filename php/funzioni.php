<?php 
function visualizza_tabella($connessione, $query, $modifica_file, $bottoni_aggiuntivi = array(), $campi_nascosti = array(), $tabella = null, $pk_field = 'id', $parametri_extra = array()) {
    // Gestione eliminazione
    if ($tabella !== null && isset($_POST['elimina'])) {
        $id = $_POST[$pk_field];
        $sql = "DELETE FROM $tabella WHERE $pk_field = ?";
        $stmt = $connessione->prepare($sql);
        
        // Determina il tipo di parametro (i per intero, s per stringa)
        $tipo_parametro = is_numeric($id) ? 'i' : 's';
        $stmt->bind_param($tipo_parametro, $id);
        
        if ($stmt->execute()) {
            echo "<div class='successo'>Record eliminato con successo</div>";
            
            // Ricarica la pagina mantenendo i parametri
            $parametri_da_mantenere = array_merge($_GET, $parametri_extra);
            $query_string = http_build_query($parametri_da_mantenere);
            echo "<script>window.location.href = window.location.pathname + '?$query_string';</script>";
            exit();
        } else {
            echo "<div class='errore'>Errore durante l'eliminazione: " . $connessione->error . "</div>";
        }
        $stmt->close();
    }

    // Visualizzazione tabella
    $sql = "$query";

    if ($result = $connessione->query($sql)) {
        echo "<div class='contenitore-tabella'>";
        echo "<table><thead><tr>";

        // Intestazioni delle colonne
        $fields = $result->fetch_fields();
        foreach($fields as $field) {
            if(!in_array($field->name, $campi_nascosti)) {
                echo "<th>$field->name</th>";
            }
        }

        // Pulsanti aggiuntivi
        foreach ($bottoni_aggiuntivi as $button) {
            echo "<th>$button[name]</th>";
        }

        // Mostra "Modifica" solo se specificato il file
        if (!empty($modifica_file)) {
            echo "<th>Modifica</th>";
        }
        
        if ($tabella !== null) {
            echo "<th>Elimina</th>";
        }
        echo "</tr></thead><tbody>";

        // Righe della tabella
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            
            // Valori delle celle
            foreach($row as $key => $value) {
                if(!in_array($key, $campi_nascosti)) {
                    echo "<td>$value</td>";
                }
            }

            // Pulsanti aggiuntivi
            foreach ($bottoni_aggiuntivi as $button) {
                if (isset($button['parametro'])) {
                    $param_value = $row[$button['parametro']];
                    $query_params = [$button['parametro'] => $param_value];
                    
                    // Aggiungi parametri extra se specificati
                    if (!empty($parametri_extra)) {
                        $query_params = array_merge($query_params, $parametri_extra);
                    }
                    
                    $query_string = http_build_query($query_params);
                    echo "<td><a href='{$button['file']}?$query_string' class='bottone-azione'>{$button['label']}</a></td>";
                } else {
                    echo "<td><form action='{$button['file']}' method='post'>";
                    foreach ($row as $key => $value) {
                        echo "<input type='hidden' name='$key' value='$value'>";
                    }
                    // Aggiungi parametri extra
                    foreach ($parametri_extra as $key => $value) {
                        echo "<input type='hidden' name='$key' value='$value'>";
                    }
                    echo "<input type='submit' style='border: none; background: none; cursor: pointer;' value='{$button['label']}'></form></td>";
                }
            }

            // Bottone modifica (solo se specificato il file)
            if (!empty($modifica_file)) {
                echo "<td><form action='$modifica_file' method='post'>";
                foreach($row as $key => $value) {
                    echo "<input type='hidden' name='$key' value='$value'>";
                }
                // Aggiungi parametri extra
                foreach ($parametri_extra as $key => $value) {
                    echo "<input type='hidden' name='$key' value='$value'>";
                }
                echo "<input type='submit' value='Modifica'></form></td>";
            }

            // Bottone elimina
            if ($tabella !== null) {
                echo "<td><form method='post' onsubmit='return confirm(\"Sei sicuro di voler eliminare questo record?\")'>";
                echo "<input type='hidden' name='elimina' value='1'>";
                echo "<input type='hidden' name='$pk_field' value='{$row[$pk_field]}'>";
                // Aggiungi parametri extra
                foreach ($parametri_extra as $key => $value) {
                    echo "<input type='hidden' name='$key' value='$value'>";
                }
                echo "<input type='submit' value='Elimina' style='background-color: #ff4444; color: white;'></form></td>";
            }

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