<?php

require_once('../config/dbConnection.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['orderByParam']) && isset($_POST['type']) && !empty($_POST['type'])) {

    $auditType = $_POST['type'];

    if($auditType == 1) {

        if($_POST['orderByParam'] == "NCASC") {

        $getQuestionsAndCount = $db->query(
            "SELECT qaf.Question, cqaf.Name, 
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
            FROM QuestionsAuditFormateur qaf
            LEFT JOIN CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID
            ORDER BY RecurrenceNC ASC"
        );

        } elseif($_POST['orderByParam'] == "NCDESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qaf.Question, cqaf.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAuditFormateur qaf
                LEFT JOIN CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID
                ORDER BY RecurrenceNC DESC"
            );

        } elseif($_POST['orderByParam'] == "NAASC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qaf.Question, cqaf.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAuditFormateur qaf
                LEFT JOIN CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID
                ORDER BY RecurrenceNA ASC"
            );

        } elseif($_POST['orderByParam'] == "NADESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qaf.Question, cqaf.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAuditFormateur qaf
                LEFT JOIN CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID
                ORDER BY RecurrenceNA DESC"
            );

        } elseif($_POST['orderByParam'] == "NDAASC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qaf.Question, cqaf.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAuditFormateur qaf
                LEFT JOIN CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID
                ORDER BY RecurrenceNDA ASC"
            );

        } elseif($_POST['orderByParam'] == "NDADESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qaf.Question, cqaf.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NC') AS 'RecurrenceNC',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NA') AS 'RecurrenceNA',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaf.ID AND ar.Report = 'NDA') AS 'RecurrenceNDA'
                FROM QuestionsAuditFormateur qaf
                LEFT JOIN CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID
                ORDER BY RecurrenceNDA DESC"
            );

        } ?>

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
    
    <?php } elseif($auditType == 2) {

        if($_POST['orderByParam'] == "RecurrenceNCmineuresASC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qaq.Question, cqaq.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NAmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
                FROM QuestionsAuditQualiopi qaq
                LEFT JOIN CategoriesQuestionsAuditQualiopi cqaq ON qaq.Category = cqaq.ID
                ORDER BY RecurrenceNCmineures ASC"
            );

        } else if($_POST['orderByParam'] == "RecurrenceNCmineuresDESC") {

                $getQuestionsAndCount = $db->query(
                    "SELECT qaq.Question, cqaq.Name, 
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NAmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
                    FROM QuestionsAuditQualiopi qaq
                    LEFT JOIN CategoriesQuestionsAuditQualiopi cqaq ON qaq.Category = cqaq.ID
                    ORDER BY RecurrenceNCmineures DESC"
                );

        } elseif($_POST['orderByParam'] == "RecurrenceNCmajeuresASC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qaq.Question, cqaq.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NCmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
                FROM QuestionsAuditQualiopi qaq
                LEFT JOIN CategoriesQuestionsAuditQualiopi cqaq ON qaq.Category = cqaq.ID
                ORDER BY RecurrenceNCmajeures ASC"
            );

        }  elseif($_POST['orderByParam'] == "RecurrenceNCmajeuresDESC") {

            $getQuestionsAndCount = $db->query(
                "SELECT qaq.Question, cqaq.Name, 
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                    (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = qaq.ID AND ar.Report = 'NCmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
                FROM QuestionsAuditQualiopi qaq
                LEFT JOIN CategoriesQuestionsAuditQualiopi cqaq ON qaq.Category = cqaq.ID
                ORDER BY RecurrenceNCmajeures DESC"
            );

        }?>

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
                        <td class="number" data-label="Non conformités mineures"><?= $questionsAndCount['RecurrenceNCmineures']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


    <?php }

}