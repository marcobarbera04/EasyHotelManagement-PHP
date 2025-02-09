<?php 
    function visualizza_tabella($connessione, $query, $modifica_file, $bottoni_aggiuntivi = array()){
        $sql = "$query"; //$tabella conterrà il nome della tabella

        if ($result = $connessione -> query($sql)) {
            echo "<div class = 'contenitore-tabella'>"; //div per il contenitore della tabella
            echo "<table><thead><tr>";

            //intestazioni delle tabelle
            $fields = $result->fetch_fields();
            foreach($fields as $field){
                echo "<th>$field->name</th>";
            }

            // Aggiungi intestazioni per i pulsanti aggiuntivi
            foreach ($bottoni_aggiuntivi as $button) {
                echo "<th>$button[name]</th>";
            }

            echo "<th>Modifica</th>";

            echo "</tr></th><tbody>"; //chiudo la riga e i campi della tabella aprendo il body

            //dati della tabella
            while($row = $result->fetch_assoc()){
                echo "<tr>";
                foreach($row as $key => $value){
                    echo "<td>$value</td>";
                }

                foreach ($bottoni_aggiuntivi as $button) {
                    echo "<td><form action='{$button['file']}' method='post'>";
                    foreach ($row as $key => $value) {
                        echo "<input type='hidden' name='$key' value='$value'>";
                    }
                    echo "<input type='hidden' name='id_hotel' value='{$row['id_hotel']}'>";
                    echo "<input type='submit' value='{$button['label']}'></form></td>";
                }

                //logica per il bottone modifca
                echo "<td><form action = '$modifica_file' method = 'post'>";
                foreach($row as $key => $value){
                    echo "<input type = 'hidden' name = '$key' value = '$value'>";
                }
                echo "<input type = 'submit' value = 'Modifica'></form></td>";

                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "</div>"; //chiudo il div
          } else {
            echo "Errore nella query: " . $connessione->error;
          }

        //chiudere connessione  
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
?>