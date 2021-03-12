<?php

require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['checkReason']) && isset($_POST['evalNumber']) && isset($_POST['question'])) {

    $checkReason = $db->prepare('SELECT ID FROM RaisonsNonConformitesAutoevaluation WHERE Question_ID = :question AND User = :user AND Eval_Number = :eval');
    $checkReason->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
    $checkReason->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $checkReason->bindParam(':eval', $_POST['evalNumber'], PDO::PARAM_INT);
    $checkReason->execute();

    $countResult = $checkReason->rowCount();

    if($countResult !== 0) {

        $deleteReason = $db->prepare('DELETE FROM RaisonsNonConformitesAutoevaluation WHERE Question_ID = :question AND User = :user AND Eval_Number = :eval');
        $deleteReason->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
        $deleteReason->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $deleteReason->bindParam(':eval', $_POST['evalNumber'], PDO::PARAM_INT);
        $deleteReason->execute();

    }

}

if(isset($_POST['answer']) && !empty($_POST['answer'])) {

    try {

        $updateAnswer = $db->prepare('UPDATE ResultatsAutoevaluations SET Answer = :answer WHERE Evaluation_Number = :evalNumber AND Question = :question AND User_ID = :user');
        $updateAnswer->bindParam(':evalNumber', $_POST['evalNumber'], PDO::PARAM_INT);
        $updateAnswer->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
        $updateAnswer->bindParam(':answer', $_POST['answer'], PDO::PARAM_STR);
        $updateAnswer->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $updateAnswer->execute();

        if($_POST['answer'] == "Oui") {

            $checkIfReasonExist1 = $db->prepare('SELECT * FROM RaisonsNonConformitesAutoevaluation WHERE User = :user AND Question_ID = :question AND Eval_Number = :eval');
            $checkIfReasonExist1->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
            $checkIfReasonExist1->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
            $checkIfReasonExist1->bindParam(':eval', $_POST['evalNumber'], PDO::PARAM_INT);
            $checkIfReasonExist1->execute();

            $countReason1 = $checkIfReasonExist1->rowCount();

            if($countReason1 == 1) {

                $deleteReason = $db->prepare('DELETE FROM RaisonsNonConformitesAutoevaluation WHERE User = :user AND Question_ID = :question AND Eval_Number = :eval');
                $deleteReason->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
                $deleteReason->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
                $deleteReason->bindParam(':eval', $_POST['evalNumber'], PDO::PARAM_INT);
                $deleteReason->execute();

            }

        }

    } catch(PDOException $e) {

        $message = $e;

    }

}

if(isset($_POST['reason']) && !empty($_POST['reason'])) {

    try {

        $checkIfReasonExist2 = $db->prepare('SELECT * FROM RaisonsNonConformitesAutoevaluation WHERE User = :user AND Question_ID = :question AND Eval_Number = :eval');
        $checkIfReasonExist2->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $checkIfReasonExist2->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
        $checkIfReasonExist2->bindParam(':eval', $_POST['evalNumber'], PDO::PARAM_INT);
        $checkIfReasonExist2->execute();

        $countReason2 = $checkIfReasonExist2->rowCount();

        if($countReason2 == 0) {

            $addReason = $db->prepare('INSERT INTO RaisonsNonConformitesAutoevaluation (User, Question_ID, Reason, Eval_Number) VALUES (:user, :question, :reason, :eval)');
            $addReason->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
            $addReason->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
            $addReason->bindParam(':reason', $_POST['reason'], PDO::PARAM_STR);
            $addReason->bindParam(':eval', $_POST['evalNumber'], PDO::PARAM_INT);
            $addReason->execute();

        } else {

            $updateReason = $db->prepare('UPDATE RaisonsNonConformitesAutoevaluation SET Reason = :reason WHERE User = :user AND Question_ID = :question AND Eval_Number = :eval');
            $updateReason->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
            $updateReason->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
            $updateReason->bindParam(':reason', $_POST['reason'], PDO::PARAM_STR);
            $updateReason->bindParam(':eval', $_POST['evalNumber'], PDO::PARAM_INT);
            $updateReason->execute();

        }
    
    } catch(PDOException $e) {

        $message = $e;

    }

}

if(isset($_POST['eval']) && !empty($_POST['eval'])) {

    $evalNumber = intval($_POST['eval']);

    try {

        $actualDate = date('Y-m-d H:i:s');
        $completed = true;
        $completeAutoEval = $db->prepare('UPDATE Autoevaluations SET Completed = :completed, DateAndHour = :actualDate WHERE User_ID = :user AND Evaluation_Number = :eval');
        $completeAutoEval->bindParam(':completed', $completed, PDO::PARAM_BOOL);
        $completeAutoEval->bindParam(':actualDate', $actualDate, PDO::PARAM_STR);
        $completeAutoEval->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $completeAutoEval->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $completeAutoEval->execute();

        $actualite = "s'est autoévalué !";
        $insertActualite = $db->prepare('INSERT INTO Actualites(User, Actualite, Eval_Number) VALUES(:user, :actualite, :eval)');
        $insertActualite->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $insertActualite->bindParam(':actualite', $actualite, PDO::PARAM_STR);
        $insertActualite->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $insertActualite->execute();

        $countCompliances = $db->prepare('SELECT count(*) as nb_compliance FROM ResultatsAutoevaluations WHERE Evaluation_Number = :eval AND Answer = "Oui" AND User_ID = :user');
        $countCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $countCompliances->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $countCompliances->execute();

        $resultCount1 = $countCompliances->fetch();

        $compliances = (int) $resultCount1['nb_compliance'];

        $countNonCompliances = $db->prepare('SELECT count(*) as nb_noncompliance FROM ResultatsAutoevaluations WHERE Evaluation_Number = :eval AND Answer = "Non" AND User_ID = :user');
        $countNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $countNonCompliances->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $countNonCompliances->execute();

        $resultCount2 = $countNonCompliances->fetch();

        $nonCompliances = (int) $resultCount2['nb_noncompliance'];

        if($compliances !== 0) {

            $compliancesTab = array(
                "label"=> "Conformités", "y"=> $compliances, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
            );

        }

        if($nonCompliances !== 0) {

            $nonCompliancesTab = array(
                "label"=> "Non-conformités", "y"=> $nonCompliances, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
            );

        }

        $dataPoints = array(

        );

        if(isset($compliancesTab)) {
        
            if(is_array($compliancesTab)) {

                array_push($dataPoints, $compliancesTab);

            }

        }

        if(isset($nonCompliancesTab)) {

            if(is_array($nonCompliancesTab)) {

                array_push($dataPoints, $nonCompliancesTab);
    
            }

        }

        echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

    } catch(PDOException $e) {

        $message = $e;
    }

}

if(isset($_POST['getNonCompliances']) && isset($_POST['evalNumber'])) {

	$evalNumber = intval($_POST['evalNumber']);

    $numberAllNonCompliances = $db->prepare('SELECT COUNT(qa.Question) as nbr_non_compliances FROM RaisonsNonConformitesAutoevaluation rnca JOIN QuestionsAutoevaluation qa ON rnca.Question_ID = qa.ID WHERE rnca.User = :user AND Eval_Number = :eval ');
    $numberAllNonCompliances->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
	$numberAllNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
    $numberAllNonCompliances->execute();

    $result = $numberAllNonCompliances->fetch();

    $numberNonCompliances = (int) $result['nbr_non_compliances'];

    if($numberNonCompliances !== 0) {

        $getNonCompliances = $db->prepare('SELECT qa.Question FROM RaisonsNonConformitesAutoevaluation rnca JOIN QuestionsAutoevaluation qa ON rnca.Question_ID = qa.ID WHERE rnca.User = :user AND Eval_Number = :eval');
        $getNonCompliances->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $getNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $getNonCompliances->execute();
        
        $compteur = 1;
        ?>

        <h3>Liste des non-conformités</h3>
        <div class="non-conformites">
            <?php while($nonCompliances = $getNonCompliances->fetch()) { ?>
                
                <div class="resultat-question">
                    <?= $compteur . ") " . $nonCompliances['Question']; ?>
                </div>
            
                <?php $compteur++; ?>
            <?php } ?>
        </div>

    <?php }

}

if(isset($message) && !empty($message)) {

    echo $message;

}

?>