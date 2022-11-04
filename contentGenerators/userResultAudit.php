<?php

require_once('../config/dbConnection.php');
require_once('../config/reqUser.php');
require_once('../config/roles.php');
require_once('../config/dateConvert.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé !";
    return;

}

// Checks if the user is authorised to access this, if not disconnect the user

if(isAdmin($userInfos['Role']) || isAuditeur($userInfos['Role'])) {

    // Gets the data of the selected audit in the userEvalAndAuditsList page (administration -> resultats utilisateurs)

    if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['auditType']) && !empty($_POST['auditType']) && isset($_POST['auditNumber']) && !empty($_POST['auditNumber']) && !isset($_POST['getNonCompliances'])) {

        $auditType = htmlspecialchars($_POST['auditType']);

        if($auditType == 1) {

            // Count the number of compliances for each completed audits and stock the result as a int in $compliances

            $countCompliances = $db->prepare('SELECT count(*) as nb_compliance FROM AuditsReports WHERE Audit_Type = :auditType AND Audit_Number = :auditNumber AND Report = "Conforme" AND User_ID = :user');
            $countCompliances->bindParam(':auditType', $auditType, PDO::PARAM_INT);
            $countCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countCompliances->execute();

            $resultCount1 = $countCompliances->fetch();

            $compliances = (int) $resultCount1['nb_compliance'];

            // Count the number of non compliances for each completed audits and stock the result as a int in $nonCompliances

            $countNonCompliances = $db->prepare('SELECT count(*) as nb_noncompliance FROM AuditsReports WHERE Audit_Type = :auditType AND Audit_Number = :auditNumber AND Report = "NC" AND User_ID = :user');
            $countNonCompliances->bindParam(':auditType', $auditType, PDO::PARAM_INT);
            $countNonCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countNonCompliances->execute();

            $resultCount2 = $countNonCompliances->fetch();

            $nonCompliances = (int) $resultCount2['nb_noncompliance'];

            // Count the number of not applicables for each completed audits and stock the result as a int in $notApplicables

            $countNotApplicable = $db->prepare('SELECT count(*) as nb_notapplicable FROM AuditsReports WHERE Audit_Type = :auditType AND Audit_Number = :auditNumber AND Report = "NA" AND User_ID = :user');
            $countNotApplicable->bindParam(':auditType', $auditType, PDO::PARAM_INT);
            $countNotApplicable->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countNotApplicable->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countNotApplicable->execute();

            $resultCount3 = $countNotApplicable->fetch();

            $notApplicables = (int) $resultCount3['nb_notapplicable'];

            // Count the number of not currently available for each completed audits and stock the result as a int in $NDA

            $countNDA = $db->prepare('SELECT count(*) as nb_na FROM AuditsReports WHERE Audit_Type = :auditType AND Audit_Number = :auditNumber AND Report = "NDA" AND User_ID = :user');
            $countNDA->bindParam(':auditType', $auditType, PDO::PARAM_INT);
            $countNDA->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countNDA->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countNDA->execute();

            $resultCount4 = $countNDA->fetch();

            $NDA = (int) $resultCount4['nb_na'];

            /* 
            Checks each result defined above and if it's not equal to 0: 
            Create an array containing the data to display on the chart with the styling parameters
            */

            if($compliances !== 0) {

                $compliancesTab = array(
                    "label"=> "Conforme(s)", "y"=> $compliances, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
                );

            }

            if($nonCompliances !== 0) {

                $nonCompliancesTab = array(
                    "label"=> "Non conforme(s)", "y"=> $nonCompliances, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
                );

            }

            if($notApplicables !== 0) {

                $notApplicablesTab = array(
                    "label"=> "Non applicable(s)", "y"=> $notApplicables, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
                );

            }

            if($NDA !== 0) {

                $NDATab = array(
                    "label"=> "Non disponible(s)", "y"=> $NDA, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
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

            if(isset($notApplicablesTab) && is_array($notApplicablesTab)) {

                array_push($dataPoints, $notApplicablesTab);

            }

            if(isset($NDATab) && is_array($NDATab)) {

                array_push($dataPoints, $NDATab);

            }

            // Returns the $datapoints array, encoded in JSON

            echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

        } elseif($auditType == 2) {

            $countCompliances = $db->prepare('SELECT count(*) as nb_conformités FROM AuditsReports WHERE Audit_Type = :auditType AND Audit_Number = :auditNumber AND Report = "Conforme" AND User_ID = :user');
            $countCompliances->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
            $countCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countCompliances->execute();

            $resultCount1 = $countCompliances->fetch();

            $compliances = (int) $resultCount1['nb_conformités'];

            // Count the number of non compliances for each completed audits and stock the result as a int in $NC

            $countNCm = $db->prepare('SELECT count(*) as nb_NCmineures FROM AuditsReports WHERE Audit_Type = :auditType AND Audit_Number = :auditNumber AND Report = "NCmineure" AND User_ID = :user');
            $countNCm->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
            $countNCm->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countNCm->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countNCm->execute();

            $resultCount2 = $countNCm->fetch();

            $NCmineures = (int) $resultCount2['nb_NCmineures'];

            // Count the number of not applicables for each completed audits and stock the result as a int in $NA

            $countNCM = $db->prepare('SELECT count(*) as nb_NCmajeures FROM AuditsReports WHERE Audit_Type = :auditType AND Audit_Number = :auditNumber AND Report = "NCmajeure" AND User_ID = :user');
            $countNCM->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
            $countNCM->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countNCM->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countNCM->execute();

            $resultCount3 = $countNCM->fetch();

            $NCmajeures = (int) $resultCount3['nb_NCmajeures'];

            /*
            Checks each result defined above and if it's not equal to 0: 
            Create an array containing the data to display on the chart with the styling parameters
            */

            if($compliances !== 0) {

                $compliancesTab = array(
                    "label"=> "Conformes", "y"=> $compliances, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
                );

            }

            if($NCmineures !== 0) {

                $NCmineuresTab = array(
                    "label"=> "Non conformes mineures", "y"=> $NCmineures, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
                );

            }

            if($NCmajeures !== 0) {

                $NCmajeuresTab = array(
                    "label"=> "Non conformes majeures", "y"=> $NCmajeures, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
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

            if(isset($NCmajeuresTab) && is_array($NCmajeuresTab)) {

                array_push($dataPoints, $NCmajeuresTab);

            }

            if(isset($NCmineuresTab) && is_array($NCmineuresTab)) {

                array_push($dataPoints, $NCmineuresTab);

            }

            // Returns the $datapoints array, encoded in JSON

            echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

        }

    }

    /*
    Creates and shows 3 table containing all the questions reported as non compliance,
    not applicable or not currently available for the selected audit
    */

    if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['auditType']) && !empty($_POST['auditType']) && isset($_POST['auditNumber']) && !empty($_POST['auditNumber']) && isset($_POST['getNonCompliances'])) {

        $auditType = htmlspecialchars($_POST['auditType']);

        if($auditType == 1) {

            $countNonCompliances = $db->prepare("SELECT COUNT(ar.Report) as nbr_non_compliances FROM AuditsReports ar JOIN QuestionsAuditFormateur qaf ON ar.Question = qaf.ID WHERE ar.User_ID = :user AND ar.Audit_Number = :auditNumber AND ar.Report = 'NC'");
            $countNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countNonCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countNonCompliances->execute();

            $result = $countNonCompliances->fetch();

            $numberNonCompliances = (int) $result['nbr_non_compliances'];

            if($numberNonCompliances !== 0) {

                $getNonCompliances = $db->prepare("SELECT qaf.Question, ar.Observation FROM AuditsReports ar JOIN QuestionsAuditFormateur qaf ON ar.Question = qaf.ID WHERE ar.User_ID = :user AND ar.Audit_Number = :auditNumber AND ar.Report = 'NC'");
                $getNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                $getNonCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
                $getNonCompliances->execute();

            } 

            $countNonApplicables = $db->prepare("SELECT COUNT(ar.Report) as nbr_not_applicables FROM AuditsReports ar JOIN QuestionsAuditFormateur qaf ON ar.Question = qaf.ID WHERE ar.User_ID = :user AND ar.Audit_Number = :auditNumber AND ar.Report = 'NA'");
            $countNonApplicables->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countNonApplicables->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countNonApplicables->execute();

            $result2 = $countNonApplicables->fetch();

            $numberNotApplicables = (int) $result2['nbr_not_applicables'];

            if($numberNotApplicables !== 0) {

                $getNotApplicables = $db->prepare("SELECT qaf.Question FROM AuditsReports ar JOIN QuestionsAuditFormateur qaf ON ar.Question = qaf.ID WHERE ar.User_ID = :user AND ar.Audit_Number = :auditNumber AND ar.Report = 'NA'");
                $getNotApplicables->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                $getNotApplicables->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
                $getNotApplicables->execute();

            }

            $countNDA = $db->prepare("SELECT COUNT(ar.Report) as nbr_NDA FROM AuditsReports ar JOIN QuestionsAuditFormateur qaf ON ar.Question = qaf.ID WHERE ar.User_ID = :user AND ar.Audit_Number = :auditNumber AND ar.Report = 'NDA'");
            $countNDA->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countNDA->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countNDA->execute();

            $result3 = $countNDA->fetch();

            $numberNDA = (int) $result3['nbr_NDA'];

            if($numberNDA !== 0) {

                $getNDA= $db->prepare("SELECT qaf.Question FROM AuditsReports ar JOIN QuestionsAuditFormateur qaf ON ar.Question = qaf.ID WHERE ar.User_ID = :user AND ar.Audit_Number = :auditNumber AND ar.Report = 'NDA'");
                $getNDA->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                $getNDA->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
                $getNDA->execute();

            }

            // Only create the tables if there is at least 1 non compliance or 1 not applicable or 1 not currently available

            if($numberNonCompliances !== 0 || $numberNotApplicables !== 0 || $numberNDA !== 0) {

                // Creates and shows the non compliances table if there is at least 1 non compliance

                if($numberNonCompliances !== 0) { ?>
                    <table id="tableauNonConformites">
                        <thead>
                            <tr>
                                <th><?php if($numberNonCompliances > 1) { echo "Non conformes"; } else { echo "Non conforme"; } ?></th>
                                <th>Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($nonCompliances = $getNonCompliances->fetch()) { ?>
                                <tr>
                                    <td data-label="Non conforme"><?= str_replace('?', '', $nonCompliances['Question']); ?></td>
                                    <td data-label="Observation"><?= $nonCompliances['Observation']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php }

                // Creates and shows the not applicables table if there is at least 1 not applicable

                if($numberNotApplicables !== 0) { ?>
                    <table id="tableauNonApplicables">
                        <thead>
                            <tr>
                                <th><?php if($numberNotApplicables > 1) { echo "Non applicables"; } else { echo "Non applicable"; } ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($notApplicable = $getNotApplicables->fetch()) { ?>
                                <tr>
                                    <td data-label="Non applicable"><?= str_replace('?', '', $notApplicable['Question']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php }

                // Creates and shows the not currently available table if there is at least 1 not currently available

                if($numberNDA !== 0) { ?>
                    <table id="tableauNonDisponiblesActuellement">
                        <thead>
                            <tr>
                                <th><?php if($numberNDA > 1) { echo "Non disponibles actuellement"; } else { echo "Non disponible actuellement"; } ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($questionNDA = $getNDA->fetch()) { ?>
                                <tr>
                                    <td data-label="Non disponible actuellement"><?= str_replace('?', '', $questionNDA['Question']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php }

            }

        } elseif($auditType == 2) {

            $countMajorNonCompliances = $db->prepare("SELECT COUNT(ar.Report) as nbr_NCmajeures FROM AuditsReports ar JOIN IndicatorsQualiopi iq ON ar.Question = iq.ID WHERE ar.User_ID = :user AND ar.Audit_Type = :auditType AND ar.Audit_Number = :auditNumber AND ar.Report = 'NCmajeure'");
            $countMajorNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countMajorNonCompliances->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
            $countMajorNonCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countMajorNonCompliances->execute();

            $result = $countMajorNonCompliances->fetch();

            $numberMajorNonCompliances = (int) $result['nbr_NCmajeures'];

            if($numberMajorNonCompliances !== 0) {

                $getMajorNonCompliances = $db->prepare("SELECT iq.Indicator, iq.Evidences, ar.Observation FROM AuditsReports ar JOIN IndicatorsQualiopi iq ON ar.Question = iq.ID WHERE ar.User_ID = :user AND ar.Audit_Type = :auditType AND ar.Audit_Number = :auditNumber AND ar.Report = 'NCmajeure'");
                $getMajorNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                $getMajorNonCompliances->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
                $getMajorNonCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
                $getMajorNonCompliances->execute();

            } 
            
            $countMinorNonCompliances = $db->prepare("SELECT COUNT(ar.Report) as nbr_NCmineures FROM AuditsReports ar JOIN IndicatorsQualiopi iq ON ar.Question = iq.ID WHERE ar.User_ID = :user AND ar.Audit_Type = :auditType AND ar.Audit_Number = :auditNumber AND ar.Report = 'NCmineure'");
            $countMinorNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $countMinorNonCompliances->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
            $countMinorNonCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
            $countMinorNonCompliances->execute();

            $result2 = $countMinorNonCompliances->fetch();

            $numberMinorNonCompliances = (int) $result2['nbr_NCmineures'];

            if($numberMinorNonCompliances !== 0) {

                $getMinorNonCompliances = $db->prepare("SELECT iq.Indicator, iq.Evidences, ar.Observation FROM AuditsReports ar  JOIN IndicatorsQualiopi iq ON ar.Question = iq.ID WHERE ar.User_ID = :user AND ar.Audit_Type = :auditType AND ar.Audit_Number = :auditNumber AND Report = 'NCmineure'");
                $getMinorNonCompliances->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                $getMinorNonCompliances->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
                $getMinorNonCompliances->bindParam(':auditNumber', $_POST['auditNumber'], PDO::PARAM_INT);
                $getMinorNonCompliances->execute();

            }

            if($numberMajorNonCompliances !== 0 || $numberMinorNonCompliances !== 0) {
                
                // Creates and shows the non compliances table if there is at least 1 major non compliance

                if($numberMajorNonCompliances !== 0) { ?>
                    <table id="tableauNonConformitesmajeures">
                        <thead>
                            <tr>
                                <th><?php if($numberMajorNonCompliances > 1) { echo "Non conformitées majeures"; } else { echo "Non conformitée majeure"; } ?></th>
                                <th>Preuves à fournir</th>
                                <th>Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($majorNonCompliances = $getMajorNonCompliances->fetch()) { ?>
                                <tr>
                                    <td data-label="Non conformitée majeure"><?= $majorNonCompliances['Indicator']; ?></td>
                                    <td data-label="Preuves à fournir"><?= str_replace(".", "<br>", $majorNonCompliances['Evidences']); ?></td>
                                    <td data-label="Observation"><?= $majorNonCompliances['Observation']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php }

                // Creates and shows the not applicables table if there is at least 1 minor not compliance

                if($numberMinorNonCompliances !== 0) { ?>
                    <table id="tableauNonConformitesmineures">
                        <thead>
                            <tr>
                                <th><?php if($numberMinorNonCompliances > 1) { echo "Non conformitées mineures"; } else { echo "Non conformitée mineure"; } ?></th>
                                <th>Preuves à fournir</th>
                                <th>Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($minorNonCompliances = $getMinorNonCompliances->fetch()) { ?>
                                <tr>
                                    <td data-label="Non conformitée mineure"><?= $minorNonCompliances['Indicator']; ?></td>
                                    <td data-label="Preuves à fournir"><?= str_replace(".", "<br>", $minorNonCompliances['Evidences']); ?></td>
                                    <td data-label="Observation"><?= $minorNonCompliances['Observation']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php }

            }

        }

    }

} else {

    echo "Acces refusé !";
    return;

} ?>