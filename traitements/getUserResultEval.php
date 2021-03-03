<?php

require_once('../config/dbConnection.php');

if(isset($_POST['userID']) && isset($_POST['eval']) && !isset($_POST['getNonCompliances'])) {

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

    if(is_array($compliancesTab)) {

        array_push($dataPoints, $compliancesTab);

    }

    if(is_array($nonCompliancesTab)) {

        array_push($dataPoints, $nonCompliancesTab);

    }

    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

}

if(isset($_POST['user']) && isset($_POST['evalNumber']) && isset($_POST['getNonCompliances'])) {

    $evalNumber = intval($_POST['evalNumber']);
    $user = intval($_POST['user']);

	$getUserNonCompliances = $db->prepare('SELECT q.Question FROM RaisonsNonConformites rnc JOIN Questions q ON rnc.Question_ID = q.ID WHERE rnc.User = :user AND Eval_Number = :eval');
	$getUserNonCompliances->bindParam(':user', $user, PDO::PARAM_INT);
	$getUserNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
	$getUserNonCompliances->execute();
	
	$compteur = 1;

	?>

	<h3>Liste des non conformités</h3>
	<?php while($userNonCompliances = $getUserNonCompliances->fetch()) { ?>
		<div class="resultat-question">
			<?= $compteur . ") " . $userNonCompliances['Question']; ?>
		</div>
	
		<?php $compteur++; ?>
	<?php } ?>

<?php }

?>