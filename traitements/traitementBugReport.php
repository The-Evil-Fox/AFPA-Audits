<?php

require_once('../config/dbConnection.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['bug']) && !empty($_POST['bug'])) {

    // Insert the bug report into the database with the errorcode if the user gives one

    $insertBugReport = $db->prepare('INSERT INTO BugReports(Bug, ErrorCode, SubmittedBy) VALUES(:bug, :errorCode, :user)');
    $insertBugReport->bindParam(':bug', $_POST['bug'], PDO::PARAM_STR);
    if(isset($_POST['errorCode']) && !empty($_POST['errorCode'])) {

        $insertBugReport->bindParam(':errorCode', $_POST['errorCode'], PDO::PARAM_STR);

    } else {

        $errorCode = null;
        $insertBugReport->bindParam(':errorCode', $errorCode, PDO::PARAM_NULL);
        
    }

    $insertBugReport->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $insertBugReport->execute();

    echo "Votre rapport de bug a bien été reçu !";
    return;
    
}

?>