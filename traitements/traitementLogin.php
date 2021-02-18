<?php

require_once('../config/dbConnection.php');

$selectAccount = $db->prepare('SELECT ID FROM Users WHERE Email = :email AND Password = :password');
$selectAccount->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
$selectAccount->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
$selectAccount->execute();

$countResults = $selectAccount->rowCount();

if($countResults == 1) {

    $accountIdentifier = $selectAccount->fetch();

    $_SESSION = [];
    $_SESSION['ID'] = $accountIdentifier['ID'];

} else {

    $errorMessage = "Ces identifiants ne correspondent à aucun compte !";

}

if(isset($errorMessage) && !empty($errorMessage)) {

    echo $errorMessage;

}


?>