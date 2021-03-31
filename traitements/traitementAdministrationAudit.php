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

            $deleteQuestion = $db->prepare('DELETE FROM QuestionsAudit WHERE ID = :questionID');
            $deleteQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $deleteQuestion->execute();

            $deleteConstatsAudits = $db->prepare('DELETE FROM AuditsReports WHERE Question = :questionID');
            $deleteConstatsAudits->bindParam(':questionID', $question, PDO::PARAM_INT);
            $deleteConstatsAudits->execute();

            $message = "La question a bien été supprimée !";

        } else if($_POST['operation'] == "disable") {

            $active = 0;

            $updateQuestion = $db->prepare('UPDATE QuestionsAudit SET Active = :active WHERE ID = :questionID');
            $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
            $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $updateQuestion->execute();
            
            $message = "La question a bien été désactivée !";

        } else if($_POST['operation'] == "enable") {

            $active = 1;

            $updateQuestion = $db->prepare('UPDATE QuestionsAudit SET Active = :active WHERE ID = :questionID');
            $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
            $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $updateQuestion->execute();

            $message = "La question a bien été activée !";

        } else if($_POST['operation'] == "editQuestion" && isset($_POST['updatedQuestion']) && !empty($_POST['updatedQuestion'])) {

            $editQuestion = $db->prepare('UPDATE QuestionsAudit SET Question = :question WHERE ID = :questionID');
            $editQuestion->bindParam(':question', $_POST['updatedQuestion'], PDO::PARAM_STR);
            $editQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $editQuestion->execute();

            $message = "La question a bien été éditée !";

        } else if($_POST['operation'] == "editEvidence" && isset($_POST['updatedEvidence']) && !empty($_POST['updatedEvidence'])) {

            $editQuestion = $db->prepare('UPDATE QuestionsAudit SET Evidence = :evidence WHERE ID = :questionID');
            $editQuestion->bindParam(':evidence', $_POST['updatedEvidence'], PDO::PARAM_STR);
            $editQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
            $editQuestion->execute();

            $message = "La preuve à fournir a bien été éditée !";

        }

    }

}

if(isset($_POST['newQuestion']) && !empty($_POST['newQuestion'])) {

    if(isset($_POST['categorieQuestion']) && !empty($_POST['categorieQuestion'])) {

        if(isset($_POST['evidenceQuestion'])) {

            $evidenceQuestion = htmlspecialchars($_POST['evidenceQuestion']);

            if($evidenceQuestion == "false") {

                $evidenceQuestion = NULL;

            }

            try {

                $addQuestion = $db->prepare('INSERT INTO QuestionsAudit(CreatedBy, Question, Evidence, Category) VALUES(:user, :question, :evidence, :category)');
                $addQuestion->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
                $addQuestion->bindParam(':question', $_POST['newQuestion'], PDO::PARAM_STR);
                if($evidenceQuestion !== NULL) {

                    $addQuestion->bindParam(':evidence', $evidenceQuestion, PDO::PARAM_STR);

                } else {

                    $addQuestion->bindValue(':evidence', $evidenceQuestion, PDO::PARAM_NULL);

                }
                $addQuestion->bindParam(':category', $_POST['categorieQuestion'], PDO::PARAM_INT);
                $addQuestion->execute();

                $message = "La question a bien été ajoutée !";

            } catch(PDOException $e) {

                echo $e;

            }

        }

    }

}

if(isset($message) && !empty($message)) {

    echo $message;

}

?>