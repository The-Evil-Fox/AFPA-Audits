<?php

require_once('../config/dbConnection.php');

$evalNumber = intval($_POST['evalNumber']);

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

if(is_array($compliancesTab)) {

	array_push($dataPoints, $compliancesTab);

}

if(is_array($nonCompliancesTab)) {

	array_push($dataPoints, $nonCompliancesTab);

}

echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

?>