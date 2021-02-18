<?php

require_once('../config/dbConnection.php');

if(isset($_FILES['inputAvatar']) && !empty($_FILES['inputAvatar']['name'])) {
        
    // Taille max d'une image = 2Mo
    $tailleMax = 2000000;
    // Extension valides
    $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
    // Si la taille du nouvel avatar de l'utilisateur ne dépasse pas la taille maximum:
    if($_FILES['inputAvatar']['size'] <= $tailleMax) {
        
        // Stockage de l'extension de l'image dans une variable
        $extensionUpload = strtolower(substr(strrchr($_FILES['inputAvatar']['name'], '.'), 1));
        // Si l'extension de l'image uploadé fais partie des extension valides:
        if(in_array($extensionUpload, $extensionsValides)) {
            
            // Création du chemin d'upload de l'image avec l'id de l'utilisateur pour le nom du fichier
            $chemin = "../assets/avatars/".$_SESSION['ID'].".".$extensionUpload;
            // Stockage du résultat du déplacement de l'image uploadé vers le chemin
            $resultat = move_uploaded_file($_FILES['inputAvatar']['tmp_name'], $chemin);
            // Si le résultat est positif:
            if($resultat) {
                
                // stockage du nom du nouvel avatar de l'utilisateur
                $avatar = $_SESSION['ID'].".".$extensionUpload;
                // Mise à jour de l'avatar de l'utilisateur dans la base de données
                $updateAvatar = $db->prepare('UPDATE Users SET Avatar = :avatar WHERE ID = :id');
                $updateAvatar->bindParam(':avatar', $avatar, PDO::PARAM_STR);
                $updateAvatar->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
                $updateAvatar->execute();
                
                // On retourne le nom du fichier uploadé pour que javascript puisse le mettre à jour au niveau du front
                $feedback = "assets/avatars/".$_SESSION['ID'].".".$extensionUpload;
                
            } else {
                
                // Message d'erreur si une erreur survient
                $feedback = "Erreur: Une erreur est survenue durant l'importation de votre nouvelle photo de profil !";
                
            }
            
        } else {
            
            // Message d'erreur si l'extension n'est pas valide
            $feedback = "Erreur: Votre nouvelle photo de profil dois être au format JPG, JPEG, GIF ou PNG !";
            
        }
        
    } else {
        
        // Message d'erreur si le nouvel avatar dépasse la taille autorisé
        $feedback = "Erreur: Votre nouvelle photo de profil ne doit pas dépasser 2Mo !";
        
    }

    if(isset($feedback) && !empty($feedback)) {

        echo $feedback;

    }
    
}

?>