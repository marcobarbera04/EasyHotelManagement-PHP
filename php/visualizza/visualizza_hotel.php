<?php
session_start();
include "../login/db.php";
include "../login/funzioni_autorizzazione.php";
include "../funzioni.php";

// Verifica autorizzazione (deve essere dopo session_start)
verifica_autorizzazione();

// Se l'utente è un amministratore (ruolo 1), mostra tutti gli hotel
if ($_SESSION['id_ruolo'] == 1) {
    $query = "SELECT id_hotel, nome, via FROM hotel";
} 
// Se l'utente è un gestore (ruolo 2), mostra solo gli hotel assegnati
elseif ($_SESSION['id_ruolo'] == 2 && isset($_SESSION['hotel_gestiti'])) {
    $hotel_gestiti = implode(",", $_SESSION['hotel_gestiti']);
    $query = "SELECT id_hotel, nome, via FROM hotel WHERE id_hotel IN ($hotel_gestiti)";
} 
// Altrimenti mostra un messaggio di errore
else {
    die("Non hai i permessi per visualizzare gli hotel");
}
?>

<!DOCTYPE html>
<html>
<style>
</style>
    <head>
        <title>Visualizza hotel</title>
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>    
        <?php
            echo '<div class="head">';
            echo '<h1>';
            echo ($_SESSION['id_ruolo'] == 1 ? "Tutti gli hotel" : "I tuoi hotel");
            echo '</h1>';
            echo '</div>';

            echo "<div class='contenitore-pulsanti'>";
            echo "<a href='../dashboard.php' class='Redirect'>Indietro</a>";
            
            // Mostra il pulsante "Aggiungi" solo se l'utente è amministratore
            if ($_SESSION['id_ruolo'] == 1) {
                pulsante_inserimento("../inserisci/inserisci_hotel.php", "Aggiungi");
            }
            
            echo "</div><br>";

            $bottoni_aggiuntivi = array(
                array('name' => 'Visualizza Prenotazioni', 'file' => 'visualizza_prenotazioni.php', 'label' => '&#x1F4C5'),
                array('name' => 'Visualizza Telefono', 'file' => 'visualizza_telefono.php', 'label' => '&#x1F4DE'),
                array('name' => 'Visualizza Email', 'file' => 'visualizza_email.php', 'label' => '&#x1F4E7'),
                array('name' => 'Visualizza Edifici', 'file' => 'visualizza_edifici.php', 'label' => '&#x1F3E2'),
                array('name' => 'Visualizza Staff', 'file' => 'visualizza_staff_hotel.php', 'label' => '&#x1F465'),
                array('name' => 'Visualizza Servizi offerti', 'file' => 'visualizza_servizi_offerti.php', 'label' => '&#x1F4A1')
            );
            
            $campi_nascosti = array('id_hotel');
            visualizza_tabella($connessione, $query, "../modifica/modifica_hotel.php", $bottoni_aggiuntivi, $campi_nascosti);
        ?>
    </body>
</html>