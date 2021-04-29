<?php

require_once('../config/dbConnection.php');
require_once('../config/roles.php');
require_once('../config/dateConvert.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}


// Counting the total number of finalised audits

if(isset($_POST['countAudits']) && isset($_POST['auditType']) && !empty($_POST['auditType'])) {

    $selectAudits = $db->prepare('SELECT * FROM Audits WHERE Completed = 1 AND Type = :auditType');
    $selectAudits->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
    $selectAudits->execute();
    $countAudits = $selectAudits->rowCount();

    // If the count is equal to 0 shows a error message and quit

    if($countAudits == 0) { ?>

        <div id='errorMessage'>
            Statistiques globales impossibles à afficher car aucun audit de cette catégorie n'a été complété pour le moment !
        </div>
    
    <?php }

    return;

}

// Gets the global stats of the selected audit type (only if ajax didn't got the error message above as a response of his first request)

if(isset($_POST['getStats']) && isset($_POST['auditType']) && !empty($_POST['auditType'])) {

    $auditType = htmlspecialchars($_POST['auditType']);

    // Selects all infos about the completed audits

    $selectAudits = $db->prepare('SELECT * FROM Audits WHERE Completed = 1 AND Type = :auditType');
    $selectAudits->bindParam(':auditType', $auditType, PDO::PARAM_INT);
    $selectAudits->execute();

    // Count the numbers of results

    $countAudits = $selectAudits->rowCount();

    // If the number of results is equal to 0 shows a error message and quit

    if($countAudits == 0) { ?>

        <div id='errorMessage'>
            Statistiques globales impossibles à afficher car aucun audit n'a été complété pour le moment !
        </div>
        
        <?php return;
    
    }

    if($auditType == 1) {

        // Count the number of compliances for each completed audits and stock the result as a int in $compliances

        $countCompliances = $db->prepare("SELECT COUNT(*) AS 'conformités' FROM AuditsReports ar INNER JOIN Audits a 
        ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
        AND ar.Report = 'Conforme' and ar.Audit_Type = :auditType");
        $countCompliances->bindParam(':auditType', $auditType, PDO::PARAM_INT);
        $countCompliances->execute();

        $resultCount1 = $countCompliances->fetch();

        $compliances = (int) $resultCount1['conformités'];

        // Count the number of non compliances for each completed audits and stock the result as a int in $NC

        $countNC = $db->prepare("SELECT COUNT(*) AS 'NC' FROM AuditsReports ar INNER JOIN Audits a 
        ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
        AND ar.Report = 'NC' AND ar.Audit_Type = :auditType");
        $countNC->bindParam(':auditType', $auditType, PDO::PARAM_INT);
        $countNC->execute();

        $resultCount2 = $countNC->fetch();

        $NC = (int) $resultCount2['NC'];

        // Count the number of not applicables for each completed audits and stock the result as a int in $NA

        $countNA = $db->prepare("SELECT COUNT(*) AS 'NA' FROM AuditsReports ar INNER JOIN Audits a 
        ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
        AND ar.Report = 'NA' AND ar.Audit_Type = :auditType");
        $countNA->bindParam(':auditType', $auditType, PDO::PARAM_INT);
        $countNA->execute();

        $resultCount3 = $countNA->fetch();

        $NA = (int) $resultCount3['NA'];

        // Count the number of not currently available for each completed audits and stock the result as a int in $NDA

        $countNDA = $db->prepare("SELECT COUNT(*) AS 'NDA' FROM AuditsReports ar INNER JOIN Audits a 
        ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
        AND ar.Report = 'NDA' AND ar.Audit_Type = :auditType");
        $countNDA->bindParam(':auditType', $auditType, PDO::PARAM_INT);
        $countNDA->execute();

        $resultCount4 = $countNDA->fetch();

        $NDA = (int) $resultCount4['NDA'];

        /* 
        Checks each result defined above and if it's not equal to 0: 
        Create an array containing the data to display on the chart with the styling parameters
        */

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
                "label"=> "Non disponibles", "y"=> $NDA, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
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

        if(isset($NCTab) && is_array($NCTab)) {

            array_push($dataPoints, $NCTab);

        }

        if(isset($NATab) && is_array($NATab)) {

            array_push($dataPoints, $NATab);

        }

        if(is_array($NDATab) && is_array($NDATab)) {

            array_push($dataPoints, $NDATab);

        }

        // Returns the $datapoints array, encoded in JSON

        echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

    } else if($auditType == 2) {

        // Count the number of compliances for each completed audits and stock the result as a int in $compliances

        $countCompliances = $db->prepare("SELECT COUNT(*) AS 'conformités' FROM AuditsReports ar INNER JOIN Audits a 
        ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
        AND ar.Report = 'Conforme' and ar.Audit_Type = :auditType");
        $countCompliances->bindParam(':auditType', $auditType, PDO::PARAM_INT);
        $countCompliances->execute();

        $resultCount1 = $countCompliances->fetch();

        $compliances = (int) $resultCount1['conformités'];

        // Count the number of non compliances for each completed audits and stock the result as a int in $NC

        $countNCmineures = $db->prepare("SELECT COUNT(*) AS 'NCmineures' FROM AuditsReports ar INNER JOIN Audits a 
        ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
        AND ar.Report = 'NCmineure' AND ar.Audit_Type = :auditType");
        $countNCmineures->bindParam(':auditType', $auditType, PDO::PARAM_INT);
        $countNCmineures->execute();

        $resultCount2 = $countNCmineures->fetch();

        $NCmineures = (int) $resultCount2['NCmineures'];

        // Count the number of not applicables for each completed audits and stock the result as a int in $NA

        $countNCmajeures = $db->prepare("SELECT COUNT(*) AS 'NCmajeures' FROM AuditsReports ar INNER JOIN Audits a 
        ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
        AND ar.Report = 'NCmajeure' AND ar.Audit_Type = :auditType");
        $countNCmajeures->bindParam(':auditType', $auditType, PDO::PARAM_INT);
        $countNCmajeures->execute();

        $resultCount3 = $countNCmajeures->fetch();

        $NCmajeures = (int) $resultCount3['NCmajeures'];

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

            $NCmTab = array(
                "label"=> "Non conformités mineures", "y"=> $NCmineures, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
            );

        }

        if($NCmajeures !== 0) {

            $NCMTab = array(
                "label"=> "Non conformités majeures", "y"=> $NCmajeures, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
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

        if(isset($NCMTab) && is_array($NCMTab)) {

            array_push($dataPoints, $NCMTab);

        }

        if(isset($NCmTab) && is_array($NCmTab)) {

            array_push($dataPoints, $NCmTab);

        }

        // Returns the $datapoints array, encoded in JSON

        echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

    }

}

// Table containing all the questions and the numbers for each type of report

if(isset($_POST['getTab']) && isset($_POST['auditType'])) {

    $auditType = htmlspecialchars($_POST['auditType']);

    // If the type if 1

    if($auditType == 1) {

        $getQuestionsAndCount = $db->query(
            "SELECT qaf.Question, cqaf.Name, 
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NC' AND ar.Audit_Type = 1) AS 'RecurrenceNC',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NA' AND ar.Audit_Type = 1) AS 'RecurrenceNA',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NDA' AND ar.Audit_Type = 1) AS 'RecurrenceNDA'
            FROM QuestionsAuditFormateur qaf
            LEFT JOIN CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID
            ORDER BY qaf.ID, qaf.Category ASC"
        ); ?>

        <select class="responsiveOrderBy" onchange="statsOrderBy(1, this.value)">
            <option value="">Sélectionnez une option de tri</option>
            <option value="NCDESC">Non conformes décroissant</option>
            <option value="NCASC">Non conformes croissant</option>
            <option value="NADESC">Non applicables décroissant</option>
            <option value="NAASC">Non applicables croissant</option>
            <option value="NDADESC">Non disponibles décroissant</option>
            <option value="NDAASC">Non disponibles croissant</option>
        </select>

        <!-- Base of the table -->

        <table id="tableauGlobalStats">
            <thead>
                <tr>
                    <th>Thématique</th>
                    <th class="forceInline">Non conformes 
                        <i onclick="statsOrderBy(1, 'NCDESC')" class="fas fa-sort-up <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NCDESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(1, 'NCASC')" class="fas fa-sort-down <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NCASC") { ?>active<?php } ?>"></i>
                    </th>
                    <th class="forceInline">Non applicables 
                        <i onclick="statsOrderBy(1, 'NADESC')" class="fas fa-sort-up <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NADESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(1, 'NAASC')" class="fas fa-sort-down <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NAASC") { ?>active<?php } ?>"></i>
                    </th>
                    <th class="forceInline">Non disponibles 
                        <i onclick="statsOrderBy(1, 'NDADESC')"class="fas fa-sort-up <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NDADESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(1, 'NDAASC')"class="fas fa-sort-down  <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NDAASC") { ?>active<?php } ?>"></i>
                    </th>
                </tr>
            </thead>
            <!-- Inserting the data in the table -->
            <tbody>
                <?php while($questionsAndCount = $getQuestionsAndCount->fetch()) { ?>
                    <tr>
                        <td data-label="Thematique"><?= str_replace('?', '', $questionsAndCount['Question']); ?></td>
                        <td class="number" data-label="Non conforme"><?= $questionsAndCount['RecurrenceNC']; ?></td>
                        <td class="number" data-label="Non applicable"><?= $questionsAndCount['RecurrenceNA']; ?></td>
                        <td class="number" data-label="Non disponible"><?= $questionsAndCount['RecurrenceNDA']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    
    <?php } else if($auditType == 2) {

        $getQuestionsAndCount = $db->query(
            "SELECT qaq.Question, cqaq.Name, 
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NCmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
            FROM QuestionsAuditQualiopi qaq
            LEFT JOIN CategoriesQuestionsAuditQualiopi cqaq ON qaq.Category = cqaq.ID
            ORDER BY qaq.ID, qaq.Category ASC"
        ); ?>

        <select class="responsiveOrderBy" onchange="statsOrderBy(2, this.value)">
            <option value="">Sélectionnez une option de tri</option>
            <option value="RecurrenceNCmajeuresDESC">Non conformes majeures décroissant</option>
            <option value="RecurrenceNCmajeuresASC">Non conformes majeures croissant</option>
            <option value="RecurrenceNCmineuresDESC">Non conformes mineures décroissant</option>
            <option value="RecurrenceNCmineuresASC">Non conformes mineures croissant</option>
        </select>

        <!-- Base of the table -->

        <table id="tableauGlobalStats">
            <thead>
                <tr>
                    <th>Indicateur</th>
                    <th>Preuves à fournir</th>
                    <th class="forceInline">Non conformités majeures 
                        <i onclick="statsOrderBy(2, 'RecurrenceNCmajeuresDESC')" class="fas fa-sort-up <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "RecurrenceNCmajeuresDESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(2, 'RecurrenceNCmajeuresASC')" class="fas fa-sort-down <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "RecurrenceNCmajeuresASC") { ?>active<?php } ?>"></i>
                    </th>
                    <th class="forceInline">Non conformités mineures 
                        <i onclick="statsOrderBy(2, 'RecurrenceNCmineuresDESC')"class="fas fa-sort-up <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "RecurrenceNCmineuresDESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(2, 'RecurrenceNCmineuresASC')"class="fas fa-sort-down  <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "RecurrenceNCmineuresASC") { ?>active<?php } ?>"></i>
                    </th>
                </tr>
            </thead>
            <!-- Inserting the data in the table -->
            <tbody>
                <?php while($questionsAndCount = $getQuestionsAndCount->fetch()) { ?>
                    <tr>
                        <td data-label="Indicateur"><?= $questionsAndCount['Name']; ?></td>
                        <td data-label="Preuves à fournir"><?= str_replace('?', '', $questionsAndCount['Question']); ?></td>
                        <td class="number" data-label="Non conformités majeures"><?= $questionsAndCount['RecurrenceNCmajeures']; ?></td>
                        <td class="number" data-label="Non conformités majeures"><?= $questionsAndCount['RecurrenceNCmineures']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php }

} ?>