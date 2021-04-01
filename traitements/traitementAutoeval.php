<?php

require_once('../config/dbConnection.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

// Delete the reason associated to the question if it exists when the user "Oui" in the autoevaluation

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

// Update or insert the answer to a question and delete the reason if the user says "Oui" and the reason for a non compliance already exist

if(isset($_POST['answer']) && !empty($_POST['answer'])) {

    try {

        $checkAnswerExist = $db->prepare('SELECT * FROM ResultatsAutoevaluations WHERE Evaluation_Number = :evalNumber AND Question = :question AND User_ID = :user');
        $checkAnswerExist->bindParam(':evalNumber', $_POST['evalNumber'], PDO::PARAM_INT);
        $checkAnswerExist->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
        $checkAnswerExist->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $checkAnswerExist->execute();

        $countResultExist = $checkAnswerExist->rowCount();

        if($countResultExist == 1) {

            $updateAnswer = $db->prepare('UPDATE ResultatsAutoevaluations SET Answer = :answer WHERE Evaluation_Number = :evalNumber AND Question = :question AND User_ID = :user');
            $updateAnswer->bindParam(':evalNumber', $_POST['evalNumber'], PDO::PARAM_INT);
            $updateAnswer->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
            $updateAnswer->bindParam(':answer', $_POST['answer'], PDO::PARAM_STR);
            $updateAnswer->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
            $updateAnswer->execute();

        } else {

            $insertAnswer = $db->prepare('INSERT INTO ResultatsAutoevaluations(Evaluation_Number, Question, Answer, User_ID) VALUES(:evalNumber, :question, :answer, :user)');
            $insertAnswer->bindParam(':evalNumber', $_POST['evalNumber'], PDO::PARAM_INT);
            $insertAnswer->bindParam(':question', $_POST['question'], PDO::PARAM_INT);
            $insertAnswer->bindParam(':answer', $_POST['answer'], PDO::PARAM_STR);
            $insertAnswer->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
            $insertAnswer->execute();

        }

        // 

        if($_POST['answer'] == "Oui" && $countResultExist == 1) {

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

// Insert or update the reason to a non compliance in the autoevaluation

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

// Finalise the autoevaluation

if(isset($_POST['eval']) && !empty($_POST['eval'])) {

    $evalNumber = intval($_POST['eval']);

    try {

        // Update the autoevaluation in the database and set the completion date and hour and set it to completed too

        $actualDate = date('Y-m-d H:i:s');
        $completed = true;
        $completeAutoEval = $db->prepare('UPDATE Autoevaluations SET Completed = :completed, DateAndHour = :actualDate WHERE User_ID = :user AND Evaluation_Number = :eval');
        $completeAutoEval->bindParam(':completed', $completed, PDO::PARAM_BOOL);
        $completeAutoEval->bindParam(':actualDate', $actualDate, PDO::PARAM_STR);
        $completeAutoEval->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $completeAutoEval->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $completeAutoEval->execute();

        // Insert the news in the database

        $actualite = "s'est autoévalué !";
        $insertActualite = $db->prepare('INSERT INTO Actualites(User, Actualite, Eval_Number) VALUES(:user, :actualite, :eval)');
        $insertActualite->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $insertActualite->bindParam(':actualite', $actualite, PDO::PARAM_STR);
        $insertActualite->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $insertActualite->execute();

        // get the compliances and non compliances for the charts displayed after the finalisation

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

        /* 
           Checks each result defined above and if it's not equal to 0: 
           Create an array containing the data to display on the chart with the styling parameters
        */
        
        if($compliances !== 0) {

            $compliancesTab = array(
                "label"=> "Conformités", "y"=> $compliances, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
            );

        }

        if($nonCompliances !== 0) {

            $nonCompliancesTab = array(
                "label"=> "Non-conformités", "y"=> $nonCompliances, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
            );

        }

        // Creates a empty array wich will contain the data to send as a response

        $dataPoints = array(

        );

        /*
           Checks if each of our expected results exists and if they are stored in a array.
           If they do, push the array into the $datapoints array that will be sent back as a response
        */

        if(isset($compliancesTab) && is_array($compliancesTab)) {

            array_push($dataPoints, $compliancesTab);

        }

        if(isset($nonCompliancesTab) && is_array($nonCompliancesTab)) {

            array_push($dataPoints, $nonCompliancesTab);

        }

        // Returns the $datapoints array, encoded in JSON

        echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

    } catch(PDOException $e) {

        $message = $e;
    }

}

// Creates and shows the tables containing all the non compliances questions and reasons

if(isset($_POST['getNonCompliances']) && isset($_POST['evalNumber'])) {

	$evalNumber = intval($_POST['evalNumber']);

    $numberAllNonCompliances = $db->prepare('SELECT COUNT(qa.Question) as nbr_non_compliances FROM RaisonsNonConformitesAutoevaluation rnca JOIN QuestionsAutoevaluation qa ON rnca.Question_ID = qa.ID WHERE rnca.User = :user AND Eval_Number = :eval ');
    $numberAllNonCompliances->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
	$numberAllNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
    $numberAllNonCompliances->execute();

    $result = $numberAllNonCompliances->fetch();

    $numberNonCompliances = (int) $result['nbr_non_compliances'];

    // If there is no non compliances, don't show anything

    if($numberNonCompliances !== 0) {

        $getNonCompliances = $db->prepare('SELECT qa.Question FROM RaisonsNonConformitesAutoevaluation rnca JOIN QuestionsAutoevaluation qa ON rnca.Question_ID = qa.ID WHERE rnca.User = :user AND Eval_Number = :eval');
        $getNonCompliances->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $getNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $getNonCompliances->execute();

        ?>

        <table id="tableauNonConformites">
            <thead>
                <tr>
                    <th><?php if($numberNonCompliances > 1) { echo "Questions non-conformes"; } else { echo "Question non-conforme"; } ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while($nonCompliances = $getNonCompliances->fetch()) { ?>
                    <tr>
                        <td data-label="Non conformité"><?= str_replace('?', '', $nonCompliances['Question']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php }

}

if(isset($message) && !empty($message)) {

    echo $message;

}

?>