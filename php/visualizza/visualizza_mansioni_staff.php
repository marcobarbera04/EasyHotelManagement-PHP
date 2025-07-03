<!DOCTYPE html>
<html>
    <head>
        <title>Mansioni Staff</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            include "../login/db.php";
            include "../login/funzioni_autorizzazione.php";
            include "../funzioni.php";

            $codice_fiscale = $_GET['codice_fiscale'] ?? null;
            
            if (!$codice_fiscale) {
                die("Codice fiscale non specificato");
            }

            // Ottieni i dati del membro dello staff
            $query_staff = "SELECT nome, cognome FROM staff WHERE codice_fiscale = ? LIMIT 1";
            $stmt = $connessione->prepare($query_staff);
            $stmt->bind_param("s", $codice_fiscale);
            $stmt->execute();
            $staff = $stmt->get_result()->fetch_assoc();

            echo "<div class=head><H1>Mansioni di ".htmlspecialchars($staff['nome'])." ".htmlspecialchars($staff['cognome'])."<br></div>";

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='visualizza_staff.php' class='Redirect'>Indietro</a>";
            pulsante_inserimento("../inserisci/inserisci_mansione_staff.php?codice_fiscale=".$codice_fiscale, "Assegna Mansione");
            echo "</div><br>";

            // Query per ottenere tutte le mansioni del membro dello staff
            $query = "SELECT ms.mansione, m.descrizione 
                     FROM mansioni_staff ms
                     JOIN mansioni m ON ms.mansione = m.mansione
                     WHERE ms.codice_fiscale = '$codice_fiscale'";

            // Configurazione per visualizza_tabella:
            // - Tabella: mansioni_staff (per l'eliminazione)
            // - PK field: mansione (campo chiave primaria per l'eliminazione)
            // - Parametri extra: codice_fiscale (da passare alla pagina di eliminazione)
            visualizza_tabella(
                $connessione, 
                $query, 
                "", // Nessuna modifica diretta
                array(), // Nessun bottone aggiuntivo
                array(), // Nessun campo nascosto
                "mansioni_staff", // Tabella per eliminazione
                "mansione", // Campo chiave primaria
                array('codice_fiscale' => $codice_fiscale) // Parametri extra
            );
        ?>
    </body>
</html>