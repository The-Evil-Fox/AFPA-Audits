<?php


require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['method']) && !empty($_POST['method'])) {

    if($_POST['method'] == "updateConstat") {

        if(isset($_POST['constat']) && !empty($_POST['constat'])) {

            if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['audit']) && !empty($_POST['audit']) && isset($_POST['thematique'])) {

                $checkConstatExist = $db->prepare('SELECT * FROM ConstatsAudits WHERE User_ID = :user AND Audit_Number = :auditNumber AND Thematique = :thematique');
                $checkConstatExist->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                $checkConstatExist->bindParam('auditNumber', $_POST['audit'], PDO::PARAM_INT);
                $checkConstatExist->bindParam(':thematique', $_POST['thematique'], PDO::PARAM_INT);
                $checkConstatExist->execute();

                $countConstat = $checkConstatExist->rowCount();

                if($countConstat == 1) {

                    $updateConstat = $db->prepare('UPDATE ConstatsAudits SET Constat = :constat WHERE User_ID = :user AND Audit_Number = :auditNumber AND Thematique = :thematique');
                    $updateConstat->bindParam(':constat', $_POST['constat'], PDO::PARAM_STR);
                    $updateConstat->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                    $updateConstat->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
                    $updateConstat->bindParam(':thematique', $_POST['thematique'], PDO::PARAM_INT);
                    $updateConstat->execute();

                } else {

                    $insertConstat = $db->prepare('INSERT INTO ConstatsAudits(Audit_Number, Thematique, Constat, User_ID, Auditor) VALUES(:auditNumber, :thematique, :constat, :user, :auditor)');
                    $insertConstat->bindParam(':constat', $_POST['constat'], PDO::PARAM_STR);
                    $insertConstat->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                    $insertConstat->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
                    $insertConstat->bindParam(':thematique', $_POST['thematique'], PDO::PARAM_INT);
                    $insertConstat->bindParam(':auditor', $_SESSION['ID'], PDO::PARAM_INT);
                    $insertConstat->execute();

                }

            }

        }

    } else if($_POST['method'] == "updateObservation") {

        if(isset($_POST['observation']) && !empty($_POST['observation'])) {

            if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['audit']) && !empty($_POST['audit']) && isset($_POST['thematique']) && !empty($_POST['thematique'])) {

                $updateObservation = $db->prepare('UPDATE ConstatsAudits SET Observation = :observation WHERE User_ID = :user AND Audit_Number = :auditNumber AND Thematique = :thematique');
                $updateObservation->bindParam(':observation', $_POST['observation'], PDO::PARAM_STR);
                $updateObservation->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
                $updateObservation->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
                $updateObservation->bindParam(':thematique', $_POST['thematique'], PDO::PARAM_INT);
                $updateObservation->execute();

            }

        }

    } else if($_POST['method'] == "finaliseAudit") {

        if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['audit']) && !empty($_POST['audit']) && isset($_POST['localisation']) && !empty($_POST['localisation'])) {

            $completed = true;

            $finaliseAudit = $db->prepare('UPDATE Audits SET Completed = :completed WHERE Audit_Number = :auditNumber AND User_ID = :user');
            $finaliseAudit->bindParam(':completed', $completed, PDO::PARAM_BOOL);
            $finaliseAudit->bindParam(':auditNumber', $_POST['audit'], PDO::PARAM_INT);
            $finaliseAudit->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $finaliseAudit->execute();

            $getUserName = $db->prepare('SELECT Name, FirstName FROM Users WHERE ID = :user');
            $getUserName->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $getUserName->execute();

            $userName = $getUserName->fetch();

            $actualite = "a été audité !";
            $insertActualite = $db->prepare('INSERT INTO Actualites(User, Actualite, Audit_Number, Centre) VALUES(:user, :actualite, :eval, :localisation)');
            $insertActualite->bindParam(':user', $_POST['userID'], PDO::PARAM_INT);
            $insertActualite->bindParam(':actualite', $actualite, PDO::PARAM_STR);
            $insertActualite->bindParam(':eval', $_POST['audit'], PDO::PARAM_INT);
            $insertActualite->bindParam(':localisation', $_POST['localisation'], PDO::PARAM_INT);
            $insertActualite->execute();

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
                    "label"=> "Non disponible(s) actuellement", "y"=> $NDA, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
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

    } else if($_POST['method'] == "getNonCompliances") {

        if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['audit']) && !empty($_POST['audit'])) {

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
                
                <?php if($numberNonCompliances !== 0) { ?>
                    <table id="tableauNonConformites">
                        <thead>
                            <tr>
                                <th><?php if($numberNonCompliances > 1) { echo "Non conformes"; } else { echo "Non-conforme"; } ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($nonCompliances = $getNonCompliances->fetch()) { ?>
                                <tr>
                                    <td data-label="Non conforme"><?= str_replace('?', '', $nonCompliances['Thematique']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                <?php if($numberNotApplicables !== 0) { ?>
                    <table id="tableauNonApplicables">
                        <thead>
                            <tr>
                                <th><?php if($numberNotApplicables > 1) { echo "Non-applicables"; } else { echo "Non-applicable"; } ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($notApplicable = $getNotApplicables->fetch()) { ?>
                                <tr>
                                    <td data-label="Non applicable"><?= str_replace('?', '', $notApplicable['Thematique']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                <?php if($numberNDA !== 0) { ?>
                    <table id="tableauNonDisponiblesActuellement">
                        <thead>
                            <tr>
                                <th><?php if($numberNDA > 1) { echo "Non-disponibles actuellement"; } else { echo "Non-disponible actuellement"; } ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($questionNDA = $getNDA->fetch()) { ?>
                                <tr>
                                    <td data-label="Non disponible actuellement"><?= str_replace('?', '', $questionNDA['Thematique']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php }
            }
            
        }

    }

}

?>