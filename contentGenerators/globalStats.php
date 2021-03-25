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
            "label"=> "Non disponibles", "y"=> $NDA, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
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

    if(!isset($_POST['orderBy'])) {

        $getQuestionsAndCount = $db->query(
            "SELECT qa.Thematique, cqa.Name, 
                (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NC') AS 'RecurrenceNC',
                (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NA') AS 'RecurrenceNA',
                (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NDA') AS 'RecurrenceNDA'
            FROM QuestionsAudit qa
            LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
            ORDER BY qa.ID, qa.Category ASC"
        );

    } elseif(isset($_POST['orderBy']) && !empty($_POST['orderBy'])) {
        
        if($_POST['orderBy'] == "NCASC") {

        $getQuestionsAndCount = $db->query(
            "SELECT qa.Thematique, cqa.Name, 
                (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NC') AS 'RecurrenceNC',
                (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NA') AS 'RecurrenceNA',
                (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NDA') AS 'RecurrenceNDA'
            FROM QuestionsAudit qa
            LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
            ORDER BY RecurrenceNC ASC"
        );

        } elseif($_POST['orderBy'] == "NCDESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Thematique, cqa.Name, 
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNC DESC"
            );

        } elseif($_POST['orderBy'] == "NAASC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Thematique, cqa.Name, 
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNA ASC"
            );

        } elseif($_POST['orderBy'] == "NADESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Thematique, cqa.Name, 
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNA DESC"
            );

        } elseif($_POST['orderBy'] == "NDAASC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Thematique, cqa.Name, 
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNDA ASC"
            );

        } elseif($_POST['orderBy'] == "NDADESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qa.Thematique, cqa.Name, 
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM ConstatsAudits ca WHERE ca.Thematique = qa.ID AND ca.Constat = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAudit qa
                LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID
                ORDER BY RecurrenceNDA DESC"
            );

        }

    } ?>

    <select class="responsiveOrderBy" onchange="statsOrderBy(this.value)">
        <option value="">Sélectionnez une option de tri</option>
        <option value="NCDESC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NCDESC") { ?>selected<?php } ?>>Non conformes décroissant</option>
        <option value="NCASC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NCASC") { ?>selected<?php } ?>>Non conformes croissant</option>
        <option value="NADESC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NADESC") { ?>selected<?php } ?>>Non applicables décroissant</option>
        <option value="NAASC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NAASC") { ?>selected<?php } ?>>Non applicables décroissant</option>
        <option value="NDDESC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NDDESC") { ?>selected<?php } ?>>Non disponibles décroissant</option>
        <option value="NDASC" <?php if(isset($_POST['orderBy']) && !empty($_POST['orderBy']) && $_POST['orderBy'] == "NDASC") { ?>selected<?php } ?>>Non disponibles décroissant</option>
    </select>

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
        <tbody>
            <?php while($questionsAndCount = $getQuestionsAndCount->fetch()) { ?>
                <tr>
                    <td data-label="Thematique"><?= str_replace('?', '', $questionsAndCount['Thematique']); ?></td>
                    <td class="number" data-label="Non conforme"><?= $questionsAndCount['RecurrenceNC']; ?></td>
                    <td class="number" data-label="Non applicable"><?= $questionsAndCount['RecurrenceNA']; ?></td>
                    <td class="number" data-label="Non disponible"><?= $questionsAndCount['RecurrenceNDA']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    
<?php } ?>

