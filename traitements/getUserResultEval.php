<?php

require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['eval']) && !empty($_POST['eval']) && !isset($_POST['getNonCompliances'])) {

    $user = intval($_POST['userID']);
    $evalNumber = intval($_POST['eval']);

    $countCompliances = $db->prepare('SELECT count(*) as nb_compliance FROM ResultatsAutoevaluations WHERE Evaluation_Number = :eval AND Answer = "Oui" AND User_ID = :user');
    $countCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
    $countCompliances->bindParam(':user', $user, PDO::PARAM_INT);
    $countCompliances->execute();

    $resultCount1 = $countCompliances->fetch();

    $compliances = (int) $resultCount1['nb_compliance'];

    $countNonCompliances = $db->prepare('SELECT count(*) as nb_noncompliance FROM ResultatsAutoevaluations WHERE Evaluation_Number = :eval AND Answer = "Non" AND User_ID = :user');
    $countNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
    $countNonCompliances->bindParam(':user', $user, PDO::PARAM_INT);
    $countNonCompliances->execute();

    $resultCount2 = $countNonCompliances->fetch();

    $nonCompliances = (int) $resultCount2['nb_noncompliance'];

    if($compliances !== 0) {

        $compliancesTab = array(
            "label"=> "Conformité(s)", "y"=> $compliances, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
        );

    }

    if($nonCompliances !== 0) {

        $nonCompliancesTab = array(
            "label"=> "Non-conformité(s)", "y"=> $nonCompliances, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
        );

    }

    $dataPoints = array(

    );

    if(is_array($compliancesTab)) {

        array_push($dataPoints, $compliancesTab);

    }

    if(isset($nonCompliancesTab)) {

        if(is_array($nonCompliancesTab)) {

            array_push($dataPoints, $nonCompliancesTab);
    
        }

    }

    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

}

if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['evalNumber']) && !empty($_POST['evalNumber']) && isset($_POST['getNonCompliances'])) {

    $evalNumber = intval($_POST['evalNumber']);
    $user = intval($_POST['userID']);

    $numberAllNonCompliances = $db->prepare('SELECT COUNT(qa.Question) as nbr_non_compliances FROM RaisonsNonConformitesAutoevaluation rnca JOIN QuestionsAutoevaluation qa ON rnca.Question_ID = qa.ID WHERE rnca.User = :user AND Eval_Number = :eval ');
    $numberAllNonCompliances->bindParam(':user', $user, PDO::PARAM_INT);
	$numberAllNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
    $numberAllNonCompliances->execute();

    $result = $numberAllNonCompliances->fetch();

    $numberNonCompliances = (int) $result['nbr_non_compliances'];

    if($numberNonCompliances !== 0) {

        $getUserNonCompliances = $db->prepare('SELECT qa.Question FROM RaisonsNonConformitesAutoevaluation rnca JOIN QuestionsAutoevaluation qa ON rnca.Question_ID = qa.ID WHERE rnca.User = :user AND Eval_Number = :eval');
        $getUserNonCompliances->bindParam(':user', $user, PDO::PARAM_INT);
        $getUserNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $getUserNonCompliances->execute();
        
        $compteur = 1;

        ?>

        <h3>Liste des non-conformité(s)</h3>
        <div class="non-conformites">
            <?php while($nonCompliances = $getUserNonCompliances->fetch()) { ?>
                
                <div class="resultat-question">
                    <?= $compteur . ") " . str_replace('?', '', $nonCompliances['Question']); ?>
                </div>
            
                <?php $compteur++; ?>
            <?php } ?>
        </div>

    <?php }

}

?>