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

            <select class="responsiveOrderBy" onchange="statsOrderBy(1, this.value)">
                <option value="">Sélectionnez une option de tri</option>
                <option value="NCDESC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NCDESC") { ?>selected<?php } ?>>Non conformes décroissant</option>
                <option value="NCASC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NCASC") { ?>selected<?php } ?>>Non conformes croissant</option>
                <option value="NADESC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NADESC") { ?>selected<?php } ?>>Non applicables décroissant</option>
                <option value="NAASC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NAASC") { ?>selected<?php } ?>>Non applicables croissant</option>
                <option value="NDADESC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NDADESC") { ?>selected<?php } ?>>Non disponibles décroissant</option>
                <option value="NDAASC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NDAASC") { ?>selected<?php } ?>>Non disponibles croissant</option>
            </select>
            <table id="tableauGlobalStats">
                <thead>
                    <tr>
                        <th>Thématique</th>
                        <th class="forceInline">Non conformes 
                            <i onclick="statsOrderBy(1, 'NCDESC')" class="fas fa-sort-up <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NCDESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(1, 'NCASC')" class="fas fa-sort-down <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NCASC") { ?>active<?php } ?>"></i>
                        </th>
                        <th class="forceInline">Non applicables 
                            <i onclick="statsOrderBy(1, 'NADESC')" class="fas fa-sort-up <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NADESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(1, 'NAASC')" class="fas fa-sort-down <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NAASC") { ?>active<?php } ?>"></i>
                        </th>
                        <th class="forceInline">Non disponibles 
                            <i onclick="statsOrderBy(1, 'NDADESC')"class="fas fa-sort-up <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NDADESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(1, 'NDAASC')"class="fas fa-sort-down  <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "NDAASC") { ?>active<?php } ?>"></i>
                        </th>
                    </tr>
                </thead>
                <!-- Inserting the data in the table -->
                <tbody>
                    <?php while($questionsAndCount = $getQuestionsAndCount->fetch()) { ?>
                        <tr>
                            <td data-label="Thematique"><?= str_replace('?', '', $questionsAndCount['Question']); ?></td>
                            <td class="number border-black" data-label="Non conforme"><?= $questionsAndCount['RecurrenceNC']; ?></td>
                            <td class="number border-black" data-label="Non applicable"><?= $questionsAndCount['RecurrenceNA']; ?></td>
                            <td class="number" data-label="Non disponible"><?= $questionsAndCount['RecurrenceNDA']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        
        <?php } elseif($auditType == 2) {

            if($_POST['orderByParam'] == "RecurrenceNCmineuresASC") {

                $getQuestionsAndCount = $db->query(
                    "SELECT iq.Indicator, iq.Evidences, 
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = iq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = iq.ID AND ar.Report = 'NAmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
                    FROM IndicatorsQualiopi iq
                    ORDER BY RecurrenceNCmineures ASC"
                );

            } else if($_POST['orderByParam'] == "RecurrenceNCmineuresDESC") {

                $getQuestionsAndCount = $db->query(
                    "SELECT iq.Indicator, iq.Evidences, 
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = iq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = iq.ID AND ar.Report = 'NAmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
                    FROM IndicatorsQualiopi iq
                    ORDER BY RecurrenceNCmineures DESC"
                );

            } elseif($_POST['orderByParam'] == "RecurrenceNCmajeuresASC") {

                $getQuestionsAndCount = $db->query(
                    "SELECT iq.Indicator, iq.Evidences,
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = iq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = iq.ID AND ar.Report = 'NCmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
                    FROM IndicatorsQualiopi iq
                    ORDER BY RecurrenceNCmajeures ASC"
                );

            }  elseif($_POST['orderByParam'] == "RecurrenceNCmajeuresDESC") {

                $getQuestionsAndCount = $db->query(
                    "SELECT iq.Indicator, iq.Evidences, 
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = iq.ID AND ar.Report = 'NCmineure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmineures',
                        (SELECT COUNT(ID) FROM AuditsReports ar WHERE ar.Question = iq.ID AND ar.Report = 'NCmajeure' AND ar.Audit_Type = 2) AS 'RecurrenceNCmajeures'
                    FROM IndicatorsQualiopi iq
                    ORDER BY RecurrenceNCmajeures DESC"
                );

            }?>
            <select class="responsiveOrderBy" onchange="statsOrderBy(2, this.value)">
                <option value="">Sélectionnez une option de tri</option>
                <option value="RecurrenceNCmajeuresDESC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "RecurrenceNCmajeuresDESC") { ?>selected<?php } ?>>Non conformes majeures décroissant</option>
                <option value="RecurrenceNCmajeuresASC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "RecurrenceNCmajeuresASC") { ?>selected<?php } ?>>Non conformes majeures croissant</option>
                <option value="RecurrenceNCmineuresDESC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "RecurrenceNCmineuresDESC") { ?>selected<?php } ?>>Non conformes mineures décroissant</option>
                <option value="RecurrenceNCmineuresASC" <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "RecurrenceNCmineuresASC") { ?>selected<?php } ?>>Non conformes mineures croissant</option>
            </select>
            <table id="tableauGlobalStats">
                <thead>
                    <tr>
                        <th>Indicateur</th>
                        <th>Preuves à fournir</th>
                        <th class="forceInline">Non conformités majeures 
                            <i onclick="statsOrderBy(2, 'RecurrenceNCmajeuresDESC')" class="fas fa-sort-up <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "RecurrenceNCmajeuresDESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(2, 'RecurrenceNCmajeuresASC')" class="fas fa-sort-down <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "RecurrenceNCmajeuresASC") { ?>active<?php } ?>"></i>
                        </th>
                        <th class="forceInline">Non conformités mineures 
                            <i onclick="statsOrderBy(2, 'RecurrenceNCmineuresDESC')"class="fas fa-sort-up <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "RecurrenceNCmineuresDESC") { ?>active<?php } ?>"></i> <i onclick="statsOrderBy(2, 'RecurrenceNCmineuresASC')"class="fas fa-sort-down  <?php if(isset($_POST['orderByParam']) && !empty($_POST['orderByParam']) && $_POST['orderByParam'] == "RecurrenceNCmineuresASC") { ?>active<?php } ?>"></i>
                        </th>
                    </tr>
                </thead>
                <!-- Inserting the data in the table -->
                <tbody>
                    <?php while($questionsAndCount = $getQuestionsAndCount->fetch()) { ?>
                        <tr>
                            <td data-label="Indicateur"><?= $questionsAndCount['Indicator']; ?></td>
                            <td class="border-black" data-label="Preuves à fournir"><?= str_replace(".", "<br>", $questionsAndCount['Evidences']); ?></td>
                            <td class="number border-black" data-label="Non conformités majeures"><?= $questionsAndCount['RecurrenceNCmajeures']; ?></td>
                            <td class="number" data-label="Non conformités mineures"><?= $questionsAndCount['RecurrenceNCmineures']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>


        <?php }

    }

} else {

    echo "Acces refusé !";
    return;

} ?>