<?php
    //variabili db
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "ehm";
        
    //oggetto mysqli
    $connessione = new mysqli($host, $user, $password, $database);
        
    //Controllo connessione
    if($connessione === false){
        die("Errore di connessione: " . $connessione->connect_error);
    }
    //echo"Connessione riuscita " . $connessione->host_info;
?>