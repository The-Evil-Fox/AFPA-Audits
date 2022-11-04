<?php

// Session init

session_start();


// Try connecting to the database or return the error code

try {

    $db = new PDO('mysql:host=localhost; dbname=ProjetAudit', "root", "admin");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("set names utf8mb4");

} catch(exception $e) {

    echo $e;

}


?>