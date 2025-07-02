<?php
session_start();
include "db.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Accesso Negato</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="contenitore-errore">
        <h2>Accesso Negato</h2>
        <p>Non hai i permessi necessari per accedere a questa risorsa.</p>
        <a href="dashboard.php">Torna alla Dashboard</a>
    </div>
</body>
</html>