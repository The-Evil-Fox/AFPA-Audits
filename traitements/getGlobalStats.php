<?php

require_once('../config/dbConnection.php');
require_once('../config/roles.php');
require_once('../config/dateConvert.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['getStats'])) {

    $countCompliances = $db->query("SELECT COUNT(*) AS 'conformités' FROM ConstatsAudits ca INNER JOIN Audits a 
    ON ca.Audit_Number = a.Audit_Number AND ca.User_ID = a.User_ID WHERE a.Completed = 1 
    AND ca.Constat = 'Conforme'");

    $resultCount1 = $countCompliances->fetch();

    $compliances = (int) $resultCount1['conformités'];

    $countNC = $db->query("SELECT COUNT(*) AS 'NC' FROM ConstatsAudits ca INNER JOIN Audits a 
    ON ca.Audit_Number = a.Audit_Number AND ca.User_ID = a.User_ID WHERE a.Completed = 1 
    AND ca.Constat = 'NC'");

    $resultCount2 = $countNC->fetch();

    $NC = (int) $resultCount2['NC'];

    $countNA = $db->query("SELECT COUNT(*) AS 'NA' FROM ConstatsAudits ca INNER JOIN Audits a 
    ON ca.Audit_Number = a.Audit_Number AND ca.User_ID = a.User_ID WHERE a.Completed = 1 
    AND ca.Constat = 'NA'");

    $resultCount3 = $countNA->fetch();

    $NA = (int) $resultCount3['NA'];

    $countNDA = $db->query("SELECT COUNT(*) AS 'NDA' FROM ConstatsAudits ca INNER JOIN Audits a 
    ON ca.Audit_Number = a.Audit_Number AND ca.User_ID = a.User_ID WHERE a.Completed = 1 
    AND ca.Constat = 'NDA'");

    $resultCount4 = $countNDA->fetch();

    $NDA = (int) $resultCount4['NDA'];

    if($compliances !== 0) {

        $compliancesTab = array(
            "label"=> "Conformes", "y"=> $compliances, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
        );

    }

    if($NC !== 0) {

        $NCTab = array(
            "label"=> "Non conformes", "y"=> $NC, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
        );

    }

    if($NA !== 0) {

        $NATab = array(
            "label"=> "Non applicables", "y"=> $NA, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
        );

    }

    if($NDA !== 0) {

        $NDATab = array(
            "label"=> "Non disponibles actuellement", "y"=> $NDA, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
        );

    }

    $dataPoints = array(

    );

    if(is_array($compliancesTab)) {

        array_push($dataPoints, $compliancesTab);

    }

    if(isset($NCTab)) {

        if(is_array($NCTab)) {

            array_push($dataPoints, $NCTab);

        }

    }

    if(is_array($NATab)) {

        array_push($dataPoints, $NATab);

    }

    if(is_array($NDATab)) {

        array_push($dataPoints, $NDATab);

    }

    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

}

if(isset($_POST['getTab'])) {

}

