<?php

require_once('../config/dbConnection.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

// If a question is edited:

if(isset($_POST['operation']) && !empty($_POST['operation'])) {

    if(isset($_POST['questionID']) && !empty($_POST['questionID'])) {

        $question = intval($_POST['questionID']);

        // If a question is deleted

        if($_POST['operation'] == "delete") {

            // Delete the question from the database and the answers and reasons associated to it

            $deleteQuestion = $db->prepare('DELETE FROM QuestionsAutoevaluation WHERE ID = :questionID');
            $deleteQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $deleteQuestion->execute();

            $deleteResults = $db->prepare('DELETE FROM ResultatsAutoevaluations WHERE Question = :questionID');
            $deleteResults->bindParam(':questionID', $question, PDO::PARAM_INT);
            $deleteResults->execute();

            $deleteReasonsNC = $db->prepare('DELETE FROM RaisonsNonConformitesAutoevaluation WHERE Question_ID = :questionID');
            $deleteReasonsNC->bindParam(':questionID', $question, PDO::PARAM_INT);
            $deleteReasonsNC->execute();

            $message = "La question a bien été supprimée !";

        // If a question is disabled

        } else if($_POST['operation'] == "disable") {

            $active = 0;

            // Disable the question in the dabase

            $updateQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Active = :active  WHERE ID = :questionID');
            $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
            $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $updateQuestion->execute();
            
            $message = "La question a bien été désactivée !";

        // If a question is enabled
        
        } else if($_POST['operation'] == "enable") {

            $active = 1;

            // Activate the question in the database

            $updateQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Active = :active WHERE ID = :questionID');
            $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
            $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $updateQuestion->execute();

            $message = "La question a bien été activée !";

        // If a question is updated
        
        } else if($_POST['operation'] == "editQuestion" && isset($_POST['updatedQuestion']) && !empty($_POST['updatedQuestion'])) {

            // Update the question in the database

            $editQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Question = :question WHERE ID = :questionID');
            $editQuestion->bindParam(':question', $_POST['updatedQuestion'], PDO::PARAM_STR);
            $editQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $editQuestion->execute();

            $message = "La question a bien été éditée !";

        }

    }

}

// If a new question is created

if(isset($_POST['newQuestion']) && !empty($_POST['newQuestion'])) {

    if(isset($_POST['categorieQuestion']) && !empty($_POST['categorieQuestion'])) {

        try {

            // Inserts the new question in the database

            $addQuestion = $db->prepare('INSERT INTO QuestionsAutoevaluation(CreatedBy, Question, Category) VALUES (:user, :question, :category)');
            $addQuestion->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
            $addQuestion->bindParam(':question', $_POST['newQuestion'], PDO::PARAM_STR);
            $addQuestion->bindParam(':category', $_POST['categorieQuestion'], PDO::PARAM_INT);
            $addQuestion->execute();

            $message = "La question a bien été ajoutée !";

        } catch(PDOException $e) {

            echo $e;

        }

    }

}

if(isset($message) && !empty($message)) {

    echo $message;

}

?>