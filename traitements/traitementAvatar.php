<?php

require_once('../config/dbConnection.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_FILES['inputAvatar']) && !empty($_FILES['inputAvatar']['name'])) {
        
    // Max size of a image
    
    $tailleMax = 2097152;

    // Valid extensions

    $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');

    // If the size of the new avatar does not exceed the maximum size:
    
    if($_FILES['inputAvatar']['size'] <= $tailleMax) {
        
        // Stores the extension of the new avatar in a variable

        $extensionUpload = strtolower(substr(strrchr($_FILES['inputAvatar']['name'], '.'), 1));
        
        // If the extension of the uploaded image is one of the valid extensions:

        if(in_array($extensionUpload, $extensionsValides)) {
            
            // Creation of the image upload path wih the user id for the file name

            $chemin = "../assets/avatars/".$_SESSION['ID'].".".$extensionUpload;

            // Storage of the result of moving the uploaded image to the path

            $resultat = move_uploaded_file($_FILES['inputAvatar']['tmp_name'], $chemin);
            // if the result is true
            if($resultat) {
                
                // Storage of the user's new avatar name

                $avatar = $_SESSION['ID'].".".$extensionUpload;

                // Update of the user's avatar in the database

                $updateAvatar = $db->prepare('UPDATE Users SET Avatar = :avatar WHERE ID = :id');
                $updateAvatar->bindParam(':avatar', $avatar, PDO::PARAM_STR);
                $updateAvatar->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
                $updateAvatar->execute();
                
                // $feedback = "assets/avatars/".$_SESSION['ID'].".".$extensionUpload;
                
            } else {
                
                // Error message if a error occurs
                $feedback = "Erreur: Une erreur est survenue durant l'importation de votre nouvelle photo de profil !";
                
            }
            
        } else {
            
            // Error message if the extension is not valid
            $feedback = "Erreur: Votre nouvelle photo de profil dois être au format JPG, JPEG, GIF ou PNG !";
            
        }
        
    } else {
        
        // Error message is the size of the avatar is too heavy
        $feedback = "Erreur: Votre nouvelle photo de profil ne doit pas dépasser 2Mo !";
        
    }

    if(isset($feedback) && !empty($feedback)) {

        echo $feedback;

    }
    
}

?>