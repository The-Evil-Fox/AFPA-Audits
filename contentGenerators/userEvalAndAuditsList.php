<?php

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

if(isset($_POST['getEvals']) && isset($_POST['user']) && !empty($_POST['user'])) {

    $getEvals = $db->prepare('SELECT u.ID, u.Name, u.FirstName, a.Evaluation_Number, a.DateAndHour FROM Autoevaluations a LEFT JOIN Users u ON a.User_ID = u.ID WHERE User_ID = :user AND a.Completed = true ORDER BY DateAndHour DESC LIMIT 10');
    $getEvals->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
    $getEvals->execute();

    $countEvals = $getEvals->rowCount(); ?>

    <?php if($countEvals !== 0) { ?>

        <h3>Liste des autoévaluations</h3>
        <table id="tableauResultatsAutoevalUtilisateur">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($evals = $getEvals->fetch()) { ?>
                    <tr>
                        <td data-label="Date autoévaluation"><?= dateConvert($evals['DateAndHour']); ?></td>
                        <td data-label="Action"><button class="button-show-autoeval-result" onclick="showUserResultEval(<?= $evals['ID']; ?>, <?= $evals['Evaluation_Number']; ?>, '<?= $evals['Name']; ?>', '<?= $evals['FirstName']; ?>', '<?= dateConvert($evals['DateAndHour']); ?>')">Résultats</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>

        <div id="errorMessage">Cet utilisateur ne s'est pas encore autoévalué !</div>
    
    <?php }

}

if(isset($_POST['getAudits']) && isset($_POST['user']) && !empty($_POST['user'])) { 

    $getAudits = $db->prepare(
        "SELECT u.ID, a.Audit_Number, u.Name, u.FirstName, a.DateAndHour, ca.Localisation,
            (SELECT u.Name FROM Users u WHERE a.Auditor = u.ID) AS 'auditeurName',
            (SELECT u.FirstName FROM Users u WHERE a.Auditor = u.ID) AS 'auditeurFirstName',
            (SELECT u.Name FROM Users u WHERE a.Assistant1 = u.ID) AS 'Assistant1Name',
            (SELECT u.FirstName AS 'Assistant1FirstName' FROM Users u WHERE a.Assistant1 = u.ID) AS 'Assistant1FirstName',
            (SELECT u.Name FROM Users u WHERE a.Assistant2 = u.ID) AS 'Assistant2Name',
            (SELECT u.FirstName AS 'Assistant1FirstName' FROM Users u WHERE a.Assistant2 = u.ID) AS 'Assistant2FirstName'
        FROM Audits a 
        LEFT JOIN Users u ON a.User_ID = u.ID
        LEFT JOIN CentresAFPA ca ON a.Centre = ca.ID
        WHERE User_ID = :user AND a.Completed = true ORDER BY DateAndHour DESC LIMIT 10"
    );
    $getAudits->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
    $getAudits->execute();

    $countAudits = $getAudits->rowCount(); ?>

    <?php if($countAudits !== 0) { ?>

        <h3>Liste des audits</h3>
        <table id="tableauResultatsAuditsUtilisateur">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($audits = $getAudits->fetch()) { ?>
                    <tr>
                        <td data-label="Date audit"><?= dateConvert($audits['DateAndHour']); ?></td>
                        <td data-label="Action"><button class="button-show-autoeval-result" onclick="showUserResultAudit(<?= $audits['ID']; ?>, <?= $audits['Audit_Number']; ?>, '<?= $audits['Name']; ?>', '<?= $audits['FirstName']; ?>', '<?= $audits['auditeurName']; ?>', '<?= $audits['auditeurFirstName']; ?>', '<?= dateConvert($audits['DateAndHour']); ?>','<?= $audits['Localisation']; ?>'<?php if($audits['Assistant1Name'] && $audits['Assistant1FirstName'] !== NULL) { ?>, '<?= $audits['Assistant1Name']; ?>', '<?= $audits['Assistant1FirstName']; ?>' <?php } if($audits['Assistant2Name'] && $audits['Assistant2FirstName'] !== NULL) { ?>, '<?= $audits['Assistant2Name']; ?>', '<?= $audits['Assistant2FirstName']; ?>' <?php } ?>)" >Résultats</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>

        <div id="errorMessage">Cet utilisateur n'a pas encore été audité !</div>
    
    <?php }

}

?>