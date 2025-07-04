<?php
require_once "login/db.php";
require_once "login/funzioni_autorizzazione.php";
require_once "funzioni.php";
verifica_autorizzazione();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pagina principale</title>
        <link rel="stylesheet" href="..\..\css\style.css">
    </head>
    <body>            
        <div class="head">
            <H1>EHM - Easy Hotel Management</H1>
        </div>

        <div class= 'contenitore-centrale'>
        <div class = 'contenitore-redirect'><a href= visualizza/visualizza_hotel.php class = 'btn-azione'>Visualizza hotel</a></div>
        <div class = 'contenitore-redirect'><a href= visualizza/visualizza_ospiti.php class = 'btn-azione'>Visualizza tutti gli ospiti</a></div>
        <div class = 'contenitore-redirect'><a href= visualizza/visualizza_servizi.php class = 'btn-azione'>Visualizza tutti i servizi</a></div>
        <div class = 'contenitore-redirect'><a href= visualizza/visualizza_mansioni.php class = 'btn-azione'>Visualizza mansioni</a></div>
        <div class = 'contenitore-redirect'><a href= visualizza/visualizza_staff.php class = 'btn-azione'>Visualizza tutti i membri dello staff</a></div>
        <?php if ($_SESSION['id_ruolo'] == 1): ?>
            <div class = 'contenitore-redirect'><a href= visualizza/visualizza_accounts.php class = 'btn-azione'>Visualizza tutti gli account</a></div>
        <?php endif; ?>
        </div>
    </body>
</html>