<?php

require_once('../config/dbConnection.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['id']) && !empty($_POST['id'])  && isset($_POST['link']) && !empty($_POST['link'])) {

    $action = htmlspecialchars($_POST['action']);

    // If the user delete a file
    if($action == "delete") {

        $action = htmlspecialchars($_POST['action']);
        $url = "../documents/" . $_POST['link'];

        // Delete the file from the database

        $deleteDocument = $db->prepare('DELETE FROM Documents WHERE ID = :id');
        $deleteDocument->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $deleteDocument->execute();

        // Delete the file from the server directory

        unlink($url);

        return;

    }
    
}

if(isset($_FILES['addDocument']) && !empty($_FILES['addDocument']['name'])) {

    // Max size of a document

    $tailleMax = 5000000;

    // Valid extension

    $extensionValide = "pdf";

    // If the size of the new document does not exceed the maximum size:

    if($_FILES['addDocument']['size'] <= $tailleMax) {
        
        // Stores the extension of the new avatar in a variable

        $extensionUpload = strtolower(substr(strrchr($_FILES['addDocument']['name'], '.'), 1));

        // If the extension of the uploaded document is one of the valid extensions:

        if($extensionUpload == $extensionValide) {
            
            // Creation of the document upload path

            $chemin = "../documents/".$_FILES['addDocument']['name'];

            // Storage of the result of moving the uploaded document to the path

            $resultat = move_uploaded_file($_FILES['addDocument']['tmp_name'], $chemin);
            // If the result is true:
            if($resultat) {
                
                // Stores the name of the document into a new variable and delete the extension from it

                $deleteExtensionFromName = basename($_FILES['addDocument']['name'], ".pdf");
                $occurences = array("-", "_");
                $documentName = str_replace($occurences, " ", $deleteExtensionFromName);
                
                // Insert the document in the database

                $insertDocument = $db->prepare('INSERT INTO Documents(Document, Link) VALUES(:document, :link)');
                $insertDocument->bindParam(':document', $documentName, PDO::PARAM_STR);
                $insertDocument->bindParam(':link', $_FILES['addDocument']['name'], PDO::PARAM_STR);
                $insertDocument->execute();
                
                return;

            } else {
                
                // Error message if a error occurs

                $feedback = "Erreur: Une erreur est survenue durant l'importation de votre document";
                
            }
            
        } else {
            
            // Error message if the extension is not valid

            $feedback = "Erreur: Le document doit être au format PDF !";
            
        }
        
    } else {
        
        // Error message is the size of the document is too heavy

        $feedback = "Erreur: Le document ne doit pas dépasser 5Mo !";
        
    }

}

if(isset($feedback) && !empty($feedback)) {

    echo $feedback;

}

?>