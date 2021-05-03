<?php

/* 
   Check if the user is authentified and get his infos if he is.
   If not he is not authentified, then he is headed to the index (login) page 
*/

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