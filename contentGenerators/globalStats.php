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

if(isset($_POST['countAudits'])) {

    $selectAudits = $db->query('SELECT * FROM Audits WHERE Completed = 1');
    $countAudits = $selectAudits->rowCount();

    // If the count is equal to 0 shows a error message and quit

    if($countAudits == 0) { ?>

        <div id='errorMessage'>
            Statistiques globales impossibles à afficher car aucun audit n'a été complété pour le moment !
        </div>
    
    <?php }

    return;

}

// Gets the global stats (only if ajax didn't got the error message above as a response of his first request)

if(isset($_POST['getStats'])) {

    // Selects all infos about the completed audits

    $selectAudits = $db->query('SELECT * FROM Audits WHERE Completed = 1');

    // Count the numbers of results

    $countAudits = $selectAudits->rowCount();

    // If the number of results is equal to 0 shows a error message and quit

    if($countAudits == 0) {

        echo "Aucun audits complété présent dans la base de données pour réaliser des statistiques !";
        return;
    
    }

    // Count the number of compliances for each completed audits and stock the result as a int in $compliances

    $countCompliances = $db->query("SELECT COUNT(*) AS 'conformités' FROM AuditsReports ar INNER JOIN Audits a 
    ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
    AND ar.Report = 'Conforme'");

    $resultCount1 = $countCompliances->fetch();

    $compliances = (int) $resultCount1['conformités'];

    // Count the number of non compliances for each completed audits and stock the result as a int in $NC

    $countNC = $db->query("SELECT COUNT(*) AS 'NC' FROM AuditsReports ar INNER JOIN Audits a 
    ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
    AND ar.Report = 'NC'");

    $resultCount2 = $countNC->fetch();

    $NC = (int) $resultCount2['NC'];

    // Count the number of not applicables for each completed audits and stock the result as a int in $NA

    $countNA = $db->query("SELECT COUNT(*) AS 'NA' FROM AuditsReports ar INNER JOIN Audits a 
    ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
    AND ar.Report = 'NA'");

    $resultCount3 = $countNA->fetch();

    $NA = (int) $resultCount3['NA'];

    // Count the number of not currently available for each completed audits and stock the result as a int in $NDA

    $countNDA = $db->query("SELECT COUNT(*) AS 'NDA' FROM AuditsReports ar INNER JOIN Audits a 
    ON ar.Audit_Number = a.Audit_Number AND ar.User_ID = a.User_ID WHERE a.Completed = 1 
    AND ar.Report = 'NDA'");

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

}

// Table containing all the questions and the numbers for each type of report

if(isset($_POST['getTab'])) {

    // First gets the stats:
    // (This one here is the default one displayed by ajax when a user requests the global stats)

    if(!isset($_POST['orderBy'])) {

        $getQuestionsAndCount = $db->query(
            "SELECT qa.Question, cqa.Name, 
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
            FROM QuestionsAudit qa
            LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
            ORDER BY qa.ID, qa.Category ASC"
        );

    // The rest of the code here is just to order the table with a specified parameters gived by the user

    } elseif(isset($_POST['orderBy']) && !empty($_POST['orderBy'])) {
        
        if($_POST['orderBy'] == "NCASC") {

        $getQuestionsAndCount = $db->query(
            "SELECT qa.Question, cqa.Name, 
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
            FROM QuestionsAudit qa
            LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
            ORDER BY RecurrenceNC ASC"
        );

        } elseif($_POST['orderBy'] == "NCDESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Question, cqa.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNC DESC"
            );

        } elseif($_POST['orderBy'] == "NAASC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Question, cqa.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNA ASC"
            );

        } elseif($_POST['orderBy'] == "NADESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Question, cqa.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNA DESC"
            );

        } elseif($_POST['orderBy'] == "NDAASC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Question, cqa.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNDA ASC"
            );

        } elseif($_POST['orderBy'] == "NDADESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Question, cqa.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qa.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNDA DESC"
            );

        }

    } ?>

    <!-- Displays a order by selector wich will only be seen by small screens users -->

    <select class="responsiveOrderBy" onchange="statsOrderBy(this.value)">
        <option value="">Sélectionnez une option de tri</option>
        <option value="NCDESC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NCDESC") { ?>selected<?php } ?>>Non conformes décroissant</option>
        <option value="NCASC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NCASC") { ?>selected<?php } ?>>Non conformes croissant</option>
        <option value="NADESC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NADESC") { ?>selected<?php } ?>>Non applicables décroissant</option>
        <option value="NAASC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NAASC") { ?>selected<?php } ?>>Non applicables décroissant</option>
        <option value="NDDESC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NDDESC") { ?>selected<?php } ?>>Non disponibles décroissant</option>
        <option value="NDASC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NDASC") { ?>selected<?php } ?>>Non disponibles décroissant</option>
    </select>

    <!-- Base of the table -->

    <table id="tableauGlobalStats">
        <thead>
            <tr>
                <th>Thématique</th>
                <th class="forceInline">Non conformes 
                    <i onclick="statsOrderBy('NCDESC')" class="fas fa-sort-up <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NCDESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy('NCASC')" class="fas fa-sort-down <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NCASC") { ?>active<?php } ?>"></i>
                </th>
                <th class="forceInline">Non applicables 
                    <i onclick="statsOrderBy('NADESC')" class="fas fa-sort-up <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NADESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy('NAASC')" class="fas fa-sort-down <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NAASC") { ?>active<?php } ?>"></i>
                </th>
                <th class="forceInline">Non disponibles 
                    <i onclick="statsOrderBy('NDADESC')"class="fas fa-sort-up <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NDADESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy('NDAASC')"class="fas fa-sort-down  <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NDAASC") { ?>active<?php } ?>"></i>
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


    
<?php } ?>