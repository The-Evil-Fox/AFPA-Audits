<?php

require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['id']) && !empty($_POST['id'])  && isset($_POST['link']) && !empty($_POST['link'])) {

    $action = htmlspecialchars($_POST['action']);

    if($action == "delete") {

        $action = htmlspecialchars($_POST['action']);
        $url = "../documents/" . $_POST['link'];

        if($action == "delete") {

            $deleteDocument = $db->prepare('DELETE FROM Documents WHERE ID = :id');
            $deleteDocument->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $deleteDocument->execute();

            unlink($url);

            return;

        }

    }
    
}

if(isset($_FILES['addDocument']) && !empty($_FILES['addDocument']['name'])) {

    // Taille max d'une image = 5Mo
    $tailleMax = 5000000;
    // Extension valide
    $extensionValide = "pdf";
    // Si la taille du nouvel avatar de l'utilisateur ne dépasse pas la taille maximum:
    if($_FILES['addDocument']['size'] <= $tailleMax) {
        
        // Stockage de l'extension de l'image dans une variable
        $extensionUpload = strtolower(substr(strrchr($_FILES['addDocument']['name'], '.'), 1));
        // Si l'extension de l'image uploadé fais partie des extension valides:
        if($extensionUpload == $extensionValide) {
            
            // Création du chemin d'upload de l'image avec l'id de l'utilisateur pour le nom du fichier
            $chemin = "../documents/".$_FILES['addDocument']['name'];
            // Stockage du résultat du déplacement de l'image uploadé vers le chemin
            $resultat = move_uploaded_file($_FILES['addDocument']['tmp_name'], $chemin);
            // Si le résultat est positif:
            if($resultat) {
                
                $deleteExtensionFromName = basename($_FILES['addDocument']['name'], ".pdf");
                $occurences = array("-", "_");
                $documentName = str_replace($occurences, " ", $deleteExtensionFromName);
                
                $insertDocument = $db->prepare('INSERT INTO Documents(Document, Link) VALUES(:document, :link)');
                $insertDocument->bindParam(':document', $documentName, PDO::PARAM_STR);
                $insertDocument->bindParam(':link', $_FILES['addDocument']['name'], PDO::PARAM_STR);
                $insertDocument->execute();
                
                return;

            } else {
                
                // Message d'erreur si une erreur survient
                $feedback = "Erreur: Une erreur est survenue durant l'importation de votre document";
                
            }
            
        } else {
            
            // Message d'erreur si l'extension n'est pas valide
            $feedback = "Erreur: Le document doit être au format PDF !";
            
        }
        
    } else {
        
        // Message d'erreur si le nouvel avatar dépasse la taille autorisé
        $feedback = "Erreur: Le document ne doit pas dépasser 5Mo !";
        
    }

}

if(isset($feedback) && !empty($feedback)) {

    echo $feedback;

}

?>