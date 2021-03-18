<?php

require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['audit']) && !empty($_POST['audit']) && !isset($_POST['getNonCompliances'])) {

    $countCompliances = $db->prepare('SELECT count(*) as nb_compliance FROM ConstatsAudits WHERE Audit_Number = :auditNumber AND Constat = "Conforme" AND User_ID = :user');
    $countCompliances->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
    $countCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
    $countCompliances->execute();

    $resultCount1 = $countCompliances->fetch();

    $compliances = (int) $resultCount1['nb_compliance'];

    $countNonCompliances = $db->prepare('SELECT count(*) as nb_noncompliance FROM ConstatsAudits WHERE Audit_Number = :auditNumber AND Constat = "NC" AND User_ID = :user');
    $countNonCompliances->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
    $countNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
    $countNonCompliances->execute();

    $resultCount2 = $countNonCompliances->fetch();

    $nonCompliances = (int) $resultCount2['nb_noncompliance'];

    $countNotApplicable = $db->prepare('SELECT count(*) as nb_notapplicable FROM ConstatsAudits WHERE Audit_Number = :auditNumber AND Constat = "NA" AND User_ID = :user');
    $countNotApplicable->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
    $countNotApplicable->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
    $countNotApplicable->execute();

    $resultCount3 = $countNotApplicable->fetch();

    $notApplicables = (int) $resultCount3['nb_notapplicable'];

    $countNDA = $db->prepare('SELECT count(*) as nb_na FROM ConstatsAudits WHERE Audit_Number = :auditNumber AND Constat = "NDA" AND User_ID = :user');
    $countNDA->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
    $countNDA->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
    $countNDA->execute();

    $resultCount4 = $countNDA->fetch();

    $NDA = (int) $resultCount4['nb_na'];

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

    if($notApplicables !== 0) {

        $notApplicablesTab = array(
            "label"=> "Non-applicable(s)", "y"=> $notApplicables, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
        );

    }

    if($NDA !== 0) {

        $NDATab = array(
            "label"=> "Non disponible(s) actuellement", "y"=> $NDA, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
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

    if(isset($notApplicablesTab)) {
    
        if(is_array($notApplicablesTab)) {

            array_push($dataPoints, $notApplicablesTab);

        }

    }

    if(isset($NDATab)) {
    
        if(is_array($NDATab)) {

            array_push($dataPoints, $NDATab);

        }

    }

    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

}

if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['audit']) && !empty($_POST['audit']) && isset($_POST['getNonCompliances'])) {

    $countNonCompliances = $db->prepare("SELECT COUNT(ca.Constat) as nbr_non_compliances FROM ConstatsAudits ca JOIN QuestionsAudit qa ON ca.Thematique = qa.ID WHERE ca.User_ID = :user AND Audit_Number = :auditNumber AND ca.Constat = 'NC'");
    $countNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
    $countNonCompliances->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
    $countNonCompliances->execute();

    $result = $countNonCompliances->fetch();

    $numberNonCompliances = (int) $result['nbr_non_compliances'];

    if($numberNonCompliances !== 0) {

        $getNonCompliances = $db->prepare("SELECT qa.Thematique FROM ConstatsAudits ca JOIN QuestionsAudit qa ON ca.Thematique = qa.ID WHERE ca.User_ID = :user AND Audit_Number = :auditNumber AND Constat = 'NC'");
        $getNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
        $getNonCompliances->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
        $getNonCompliances->execute();
        
        $compteurNC = 1;

    } 

    $countNonApplicables = $db->prepare("SELECT COUNT(ca.Constat) as nbr_not_applicables FROM ConstatsAudits ca JOIN QuestionsAudit qa ON ca.Thematique = qa.ID WHERE ca.User_ID = :user AND Audit_Number = :auditNumber AND ca.Constat = 'NA'");
    $countNonApplicables->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
    $countNonApplicables->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
    $countNonApplicables->execute();

    $result2 = $countNonApplicables->fetch();

    $numberNotApplicables = (int) $result2['nbr_not_applicables'];

    if($numberNotApplicables !== 0) {

        $getNotApplicables = $db->prepare("SELECT qa.Thematique FROM ConstatsAudits ca JOIN QuestionsAudit qa ON ca.Thematique = qa.ID WHERE ca.User_ID = :user AND Audit_Number = :auditNumber AND Constat = 'NA'");
        $getNotApplicables->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
        $getNotApplicables->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
        $getNotApplicables->execute();

        $compteurNA = 1;

    }

    $countNDA = $db->prepare("SELECT COUNT(ca.Constat) as nbr_NDA FROM ConstatsAudits ca JOIN QuestionsAudit qa ON ca.Thematique = qa.ID WHERE ca.User_ID = :user AND Audit_Number = :auditNumber AND ca.Constat = 'NDA'");
    $countNDA->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
    $countNDA->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
    $countNDA->execute();

    $result3 = $countNDA->fetch();

    $numberNDA = (int) $result3['nbr_NDA'];

    if($numberNDA !== 0) {

        $getNDA= $db->prepare("SELECT qa.Thematique FROM ConstatsAudits ca JOIN QuestionsAudit qa ON ca.Thematique = qa.ID WHERE ca.User_ID = :user AND Audit_Number = :auditNumber AND Constat = 'NDA'");
        $getNDA->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
        $getNDA->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
        $getNDA->execute();

        $compteurNDA = 1;

    }

    if($numberNonCompliances !== 0 || $numberNotApplicables !== 0 || $numberNDA !== 0) { ?>
        <h3>Liste</h3>
        <?php if($numberNonCompliances !== 0) { ?>
            <div class="non-conformites">
                <h4>Non conformité(s)</h4>
                <?php while($nonCompliances = $getNonCompliances->fetch()) { ?>
                    
                    <div class="resultat-question">
                        <?= $compteurNC . ") " . str_replace('?', '', $nonCompliances['Thematique']); ?>
                    </div>
                
                    <?php $compteurNC++; ?>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if($numberNotApplicables !== 0) { ?>
            <div class="non-applicables">
                <h4>Non applicable(s)</h4>
                <?php while($notApplicable = $getNotApplicables->fetch()) { ?>
                    
                    <div class="resultat-question">
                        <?= $compteurNA . ") " . str_replace('?', '', $notApplicable['Thematique']); ?>
                    </div>
                
                    <?php $compteurNA++; ?>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if($numberNDA !== 0) { ?>
            <div class="NDA">
                <h4>Non disponible(s) actuellement</h4>
                <?php while($questionNDA = $getNDA->fetch()) { ?>
                    
                    <div class="resultat-question">
                        <?= $compteurNDA . ") " . str_replace('?', '', $questionNDA['Thematique']); ?>
                    </div>
                
                    <?php $compteurNDA++; ?>
                <?php } ?>
            </div>
        <?php } ?>
    <?php }

}