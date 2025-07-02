<?php
function verifica_autorizzazione($ruolo_richiesto = null, $id_hotel = null) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['id_account'])) {
        header("Location: ../index.php");
        exit();
    }

    // Per gestori, carica la lista degli hotel gestiti se non è già in sessione
    if ($_SESSION['id_ruolo'] == 2 && !isset($_SESSION['hotel_gestiti'])) {
        include "db.php";
        $account_id = $_SESSION['id_account'];
        $query = "SELECT id_hotel FROM hotel_gestiti_account WHERE id_account = ?";
        $stmt = $connessione->prepare($query);
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $hotel_gestiti = array();
        while ($row = $result->fetch_assoc()) {
            $hotel_gestiti[] = $row['id_hotel'];
        }
        
        $_SESSION['hotel_gestiti'] = $hotel_gestiti;
        $stmt->close();
    }
}

function ottieni_hotel_gestiti() {
    return isset($_SESSION['hotel_gestiti']) ? $_SESSION['hotel_gestiti'] : array();
}

function ha_permesso($ruolo_richiesto, $id_hotel = null) {
    if ($_SESSION['id_ruolo'] == $ruolo_richiesto) {
        if ($ruolo_richiesto == 2 && $id_hotel) {
            return isset($_SESSION['hotel_gestiti']) && in_array($id_hotel, $_SESSION['hotel_gestiti']);
        }
        return true;
    }
    return false;
}
?>