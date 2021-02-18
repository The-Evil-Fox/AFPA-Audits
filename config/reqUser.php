<?php

if(isset($_SESSION['ID'])) {

$userAccount = $db->prepare('SELECT * FROM Users WHERE ID = :userid');
$userAccount->bindParam(':userid', $_SESSION['ID'], PDO::PARAM_INT);
$userAccount->execute();

$userInfos = $userAccount->fetch();

} else {

    header('Location: index.php');
    exit();

}

?>