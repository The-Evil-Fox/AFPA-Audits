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

            $message = "La question a bien été supprimée !";

        }

        if($_POST['operation'] == "disable") {

            $active = 0;

            $updateQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Active = :active  WHERE ID = :questionID');
            $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
            $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $updateQuestion->execute();
            
            $message = "La question a bien été désactivée !";

        }

        if($_POST['operation'] == "enable") {

            $active = 1;

            $updateQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Active = :active WHERE ID = :questionID');
            $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
            $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $updateQuestion->execute();

            $message = "La question a bien été activée !";

        }

        if($_POST['operation'] == "edit" && isset($_POST['updatedQuestion']) && !empty($_POST['updatedQuestion'])) {

            $editQuestion = $db->prepare('UPDATE QuestionsAutoevaluation SET Question = :question WHERE ID = :questionID');
            $editQuestion->bindParam(':question', $_POST['updatedQuestion'], PDO::PARAM_STR);
            $editQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $editQuestion->execute();

            $message = "La question a bien été éditée !";
        }

    }

}

if(isset($_POST['newQuestionLabel']) && !empty($_POST['newQuestionLabel'])) {

    if(isset($_POST['categorieQuestion']) && !empty($_POST['categorieQuestion'])) {

        try {

            $addQuestion = $db->prepare('INSERT INTO QuestionsAutoevaluation(User_ID, Question, Category) VALUES (:user, :question, :category)');
            $addQuestion->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
            $addQuestion->bindParam(':question', $_POST['newQuestionLabel'], PDO::PARAM_STR);
            $addQuestion->bindParam(':category', $_POST['categorieQuestion'], PDO::PARAM_INT);
            $addQuestion->execute();

            $getLastID = $db->query('SELECT ID FROM QuestionsAutoevaluation ORDER BY ID DESC LIMIT 1');
            $result = $getLastID->fetch();
            $lastID = (int) $result['ID'];
            echo $lastID;

        } catch(PDOException $e) {

            echo $e;

        }

    }

}

if(isset($message) && !empty($message)) {

    echo $message;

}

?>