<?php

require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['operation']) && !empty($_POST['operation'])) {

    if(isset($_POST['questionID']) && !empty($_POST['questionID'])) {

        $question = intval($_POST['questionID']);

        if($_POST['operation'] == "delete") {

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

        } else if($_POST['operation'] == "disable") {

            $active = 0;

            $updateQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Active = :active  WHERE ID = :questionID');
            $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
            $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $updateQuestion->execute();
            
            $message = "La question a bien été désactivée !";

        } else if($_POST['operation'] == "enable") {

            $active = 1;

            $updateQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Active = :active WHERE ID = :questionID');
            $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
            $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $updateQuestion->execute();

            $message = "La question a bien été activée !";

        } else if($_POST['operation'] == "edit" && isset($_POST['updatedQuestion']) && !empty($_POST['updatedQuestion'])) {

            $editQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Question = :question WHERE ID = :questionID');
            $editQuestion->bindParam(':question', $_POST['updatedQuestion'], PDO::PARAM_STR);
            $editQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $editQuestion->execute();

            $message = "La question a bien été éditée !";

        }

    }

}

if(isset($_POST['newQuestion']) && !empty($_POST['newQuestion'])) {

    if(isset($_POST['categorieQuestion']) && !empty($_POST['categorieQuestion'])) {

        try {

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