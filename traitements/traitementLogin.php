<?php

require_once('../config/dbConnection.php');

// Check if the email and password from the login form match to a user in the database

$selectAccount = $db->prepare('SELECT ID FROM Users WHERE Email = :email AND Password = :password AND Activated = 1');
$selectAccount->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
$selectAccount->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
$selectAccount->execute();

$countResults = $selectAccount->rowCount();

// If there is a match set the session ID of the user

if($countResults == 1) {

    $accountIdentifier = $selectAccount->fetch();

    $_SESSION = [];
    $_SESSION['ID'] = $accountIdentifier['ID'];

// Else shows returns a error message

} else {

    $errorMessage = "Ces identifiants ne correspondent à aucun compte !";

}

if(isset($errorMessage) && !empty($errorMessage)) {

    echo $errorMessage;

}


?>