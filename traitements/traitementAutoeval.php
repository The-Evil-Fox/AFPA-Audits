<?php

require_once('../config/dbConnection.php');

$questionsAndAnswers = array();

$questionsAndAnswers = array_combine($_POST['Questions'], $_POST['Reponses']);

$tabReasons = $_POST['Raisons'];
      
$reasons = array_combine(array_column($tabReasons, 'id'), array_column($tabReasons, 'reason'));

$selectLastEval = $db->prepare('SELECT Evaluation_Number FROM Autoevaluations WHERE User_ID = :user ORDER BY Evaluation_Number DESC LIMIT 1');
$selectLastEval->bindParam(':user',$_SESSION['ID'], PDO::PARAM_INT);
$selectLastEval->execute();

$countResult = $selectLastEval->rowCount();

if($countResult == 0) {

    $evaluationNumber = 1;

} else {

    $lastEval = $selectLastEval->fetch();
    $evaluationNumber = $lastEval['Evaluation_Number'] + 1;

}

try {

    $timeStamp = $db->prepare('INSERT INTO Autoevaluations(Evaluation_Number, User_iD) VALUES(:evalNumber, :user)');
    $timeStamp->bindParam(':evalNumber', $evaluationNumber, PDO::PARAM_INT);
    $timeStamp->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $timeStamp->execute();

    $insertEval = $db->prepare('INSERT INTO ResultatsAutoevaluations(Evaluation_Number, Question, Answer, User_ID) VALUES(:evalnumber, :question, :answer, :userId)');
    $insertEval->bindParam(':evalnumber', $evaluationNumber);
    $insertEval->bindParam(':question', $question);
    $insertEval->bindParam(':answer', $answer);
    $insertEval->bindParam(':userId', $user);
    foreach($questionsAndAnswers as $key=>$value) {
        // echo "question n°" . $key . "a la réponse :" . $value . " ";
        $question = intval($key);
        $answer = $value;
        $user = $_SESSION['ID'];
        $insertEval->execute();
    }

    try {

        if(isset($_POST['Raisons']) && !empty($_POST['Raisons'])) {

            $insertReasons = $db->prepare('INSERT INTO RaisonsNonConformites(User, Question_ID, Reason) VALUES (:user, :question, :reason)');
            $insertReasons->bindParam(':user', $user);
            $insertReasons->bindParam(':question', $questionID);
            $insertReasons->bindParam(':reason', $reason);
            foreach($reasons as $key=>$value) {
                $questionID = intval($key);
                $reason = $value;
                $user = $_SESSION['ID'];
                $insertReasons->execute();
            }

        }

    } catch(Exception $e) {

        echo $e;
    }

    $message = "<span class='phrase'>Votre auto-évaluation a bien été validée !</span><span class='phrase'>Merci pour votre temps !</span>"; 

} catch(PDOException $e) {

    echo $e;

}

if(isset($message) && !empty($message)) {

    echo $message;

}

?>