<?php

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

if(isset($_POST['getEvals']) && isset($_POST['user']) && !empty($_POST['user'])) {

    $getEvals = $db->prepare('SELECT u.ID, u.Name, u.FirstName, a.Evaluation_Number, a.DateAndHour FROM Autoevaluations a LEFT JOIN Users u ON a.User_ID = u.ID WHERE User_ID = :user AND a.Completed = true ORDER BY DateAndHour DESC LIMIT 10');
    $getEvals->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
    $getEvals->execute();

    $countEvals = $getEvals->rowCount(); ?>

    <h3>Liste des autoévaluations</h3>

    <?php if($countEvals !== 0) { ?>

        <table id="tableauResultatsAutoevalUtilisateur">
            <thead>
                <tr>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($evals = $getEvals->fetch()) { ?>
                    <tr>
                        <td data-label="Date autoévaluation"><?= dateConvert($audits['DateAndHour']); ?></td>
                        <td data-label="Resultats autoévaluation"><button class="button-show-autoeval-result" onclick="showUserResultEval(<?= $evals['ID']; ?>, <?= $evals['Evaluation_Number']; ?>, '<?= $evals['Name']; ?>', '<?= $evals['FirstName']; ?>', '<?= dateConvert($evals['DateAndHour']); ?>')">Résultats</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>

        <table id="tableauResultatsAutoevalUtilisateur">
            <thead>
                <tr>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="Message">Cet utilisateur ne s'est pas encore autoévalué.</td>
                </tr>
            </tbody>
        </table>
    
    <?php }

}

if(isset($_POST['getAudits']) && isset($_POST['user']) && !empty($_POST['user'])) { 

    $getAudits = $db->prepare('SELECT u.ID, a.Audit_Number, u.Name, u.FirstName, a.DateAndHour, ca.Localisation FROM Audits a 
    LEFT JOIN Users u ON a.User_ID = u.ID
    LEFT JOIN CentresAFPA ca ON a.Centre = ca.ID
    WHERE User_ID = :user AND a.Completed = true ORDER BY DateAndHour DESC LIMIT 10');
    $getAudits->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
    $getAudits->execute();

    $countAudits = $getAudits->rowCount(); ?>

    <h3>Liste des audits</h3>

    <?php if($countAudits !== 0) { ?>

        <table id="tableauResultatsAuditsUtilisateur">
            <thead>
                <tr>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($audits = $getAudits->fetch()) { ?>
                    <tr>
                        <td data-label="Date audit"><?= dateConvert($audits['DateAndHour']); ?></td>
                        <td data-label="Resultats audit"><button class="button-show-autoeval-result" onclick="showUserResultAudit(<?= $audits['ID']; ?>, <?= $audits['Audit_Number']; ?>, '<?= $audits['Name']; ?>', '<?= $audits['FirstName']; ?>', '<?= dateConvert($audits['DateAndHour']); ?>','<?= $audits['Localisation']; ?>')">Résultats</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>

        <table id="tableauResultatsAuditsUtilisateur">
            <thead>
                <tr>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="Message">Cet utilisateur n'a pas encore été audité.</td>
                </tr>
            </tbody>
        </table>
    
    <?php }

}

?>