<?php

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

if(isset($_POST['getEvals']) && isset($_POST['user']) && !empty($_POST['user'])) {

    $getEvals = $db->prepare('SELECT u.ID, u.Name, u.FirstName, a.Evaluation_Number, a.DateAndHour FROM Autoevaluations a LEFT JOIN Users u ON a.User_ID = u.ID WHERE User_ID = :user AND a.Completed = true ORDER BY DateAndHour DESC LIMIT 10');
    $getEvals->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
    $getEvals->execute();

    $countEvals = $getEvals->rowCount();

    if($countEvals == 0) { ?>

        <h3>Autoevaluations</h3>
        <div class="card text-center">
            <div class="card-content">
                <span class="noresultfound">Cet utilisateur ne s'est pas encore autoévalué !</span>
            </div>
        </div>

    <?php } else { ?>

        <h3>Autoevaluations</h3>
        <?php while($evals = $getEvals->fetch()) { ?>
            <div class="card text-center">
                <div class="card-content">
                    <?= "Autoevaluation du " . dateConvert($evals['DateAndHour']); ?>
                    <button class="button-show-autoeval-result" onclick="showUserResultEval(<?= $evals['ID']; ?>, <?= $evals['Evaluation_Number']; ?>, '<?= $evals['Name']; ?>', '<?= $evals['FirstName']; ?>', '<?= dateConvert($evals['DateAndHour']); ?>')">Voir ses résultats</button>
                </div>
            </div>
        <?php }

    }

}

if(isset($_POST['getAudits']) && isset($_POST['user']) && !empty($_POST['user'])) { 

    $getAudits = $db->prepare('SELECT u.ID, a.Audit_Number, u.Name, u.FirstName, a.DateAndHour, ca.Localisation FROM Audits a 
    LEFT JOIN Users u ON a.User_ID = u.ID
    LEFT JOIN CentresAFPA ca ON a.Centre = ca.ID
    WHERE User_ID = :user AND a.Completed = true ORDER BY DateAndHour DESC LIMIT 10');
    $getAudits->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
    $getAudits->execute();

    $countAudits = $getAudits->rowCount();

    if($countAudits == 0) { ?>

        <h3>Audits</h3>
        <div class="card text-center">
            <div class="card-content">
                <span class="noresultfound">Cet utilisateur n'a pas encore été audité !</span>
            </div>
        </div>

    <?php } else { ?>

        <h3>Audits</h3>
        <?php while($audits = $getAudits->fetch()) { ?>
            <div class="card text-center">
                <div class="card-content">
                    <?= "Audit du " . dateConvert($audits['DateAndHour']); ?>
                    <button class="button-show-autoeval-result" onclick="showUserResultAudit(<?= $audits['ID']; ?>, <?= $audits['Audit_Number']; ?>, '<?= $audits['Name']; ?>', '<?= $audits['FirstName']; ?>', '<?= dateConvert($audits['DateAndHour']); ?>','<?= $audits['Localisation']; ?>')">Voir ses résultats</button>
                </div>
            </div>
        <?php }

    }

}

?>