<?php

session_start();

try {

    $db = new PDO('mysql:host=localhost; dbname=ProjetAudit', "root", "admin");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(exception $e) {

    echo $e;

}

?>