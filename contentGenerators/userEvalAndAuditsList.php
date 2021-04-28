<?php

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

// Gets the list of all the autoevaluations of the selected user

if(isset($_POST['getEvals']) && isset($_POST['user']) && !empty($_POST['user'])) {

    $getEvals = $db->prepare(
        'SELECT u.ID, u.Name, u.FirstName, a.Evaluation_Number, a.DateAndHour 
        FROM Autoevaluations a LEFT JOIN Users u ON a.User_ID = u.ID WHERE User_ID = :user 
        AND a.Completed = true ORDER BY DateAndHour DESC LIMIT 10'
    );
    $getEvals->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
    $getEvals->execute();

    // Count the number of autoevaluations completed

    $countEvals = $getEvals->rowCount();

    /*
       If the count is different from 0 shows a table containing the infos of the 
       evaluation with a button to see the result for each
    */

    if($countEvals !== 0) { ?>

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
                        <td data-label="Action"><button class="button-show-autoeval-result" onclick="showUserResultEval(<?= $evals['ID']; ?>, <?= $evals['Evaluation_Number']; ?>, '<?= $evals['Name']; ?>', '<?= $evals['FirstName']; ?>', '<?= dateConvert($evals['DateAndHour']); ?>')"><i class="fas fa-eye"></i> Voir les résultats</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php 

    // Else shows a error message

    } else { ?>

        <div id="errorMessage">Cet utilisateur ne s'est pas encore autoévalué !</div>
    
    <?php }

}

// Gets the list of all the audits of the selected user with the infos about the assistants and localisation

if(isset($_POST['getAudits']) && isset($_POST['user']) && !empty($_POST['user'])) { 

    $getAudits = $db->prepare(
        "SELECT u.ID, a.Type, a.Audit_Number, u.Name, u.FirstName, a.DateAndHour, ca.Localisation,
            (SELECT u.Name FROM Users u WHERE a.Auditor = u.ID) AS 'auditeurName',
            (SELECT u.FirstName FROM Users u WHERE a.Auditor = u.ID) AS 'auditeurFirstName',
            (SELECT u.Name FROM Users u WHERE a.Assistant1 = u.ID) AS 'Assistant1Name',
            (SELECT u.FirstName AS 'Assistant1FirstName' FROM Users u WHERE a.Assistant1 = u.ID) AS 'Assistant1FirstName',
            (SELECT u.Name FROM Users u WHERE a.Assistant2 = u.ID) AS 'Assistant2Name',
            (SELECT u.FirstName AS 'Assistant1FirstName' FROM Users u WHERE a.Assistant2 = u.ID) AS 'Assistant2FirstName'
        FROM Audits a 
        LEFT JOIN Users u ON a.User_ID = u.ID
        LEFT JOIN Facilities ca ON a.Facility = ca.ID
        WHERE User_ID = :user AND a.Completed = true ORDER BY a.Audit_Number DESC LIMIT 15"
    );
    $getAudits->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
    $getAudits->execute();

    // Count the number of completed audits

    $countAudits = $getAudits->rowCount();

    /*
       If the count is different from 0 shows a table containing the date and hour of the 
       audits with a button to see the result for each. The localisation and assistants are passed as
       parameters to javascript and will be displayed when the chart will be generated if the user decides
       to show the result of the audit
    */

    if($countAudits !== 0) { ?>

        <h3>Liste des audits</h3>
        <table id="tableauResultatsAuditsUtilisateur">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($audits = $getAudits->fetch()) { ?>
                    <tr>
                        <td data-label="Type"><?php if($audits['Type'] == 1) { echo "Audit formateur"; } elseif($audits['Type'] == 2) { echo "Audit Qualiopi"; } ?></td>
                        <td data-label="Date audit"><?= dateConvert($audits['DateAndHour']); ?></td>
                        <td data-label="Action"><button class="button-show-autoeval-result" onclick="showUserResultAudit(<?= $audits['ID']; ?>, <?= $audits['Type']; ?>, <?= $audits['Audit_Number']; ?>, '<?= $audits['Name']; ?>', '<?= $audits['FirstName']; ?>', '<?= $audits['auditeurName']; ?>', '<?= $audits['auditeurFirstName']; ?>', '<?= dateConvert($audits['DateAndHour']); ?>','<?= $audits['Localisation']; ?>'<?php if($audits['Assistant1Name'] && $audits['Assistant1FirstName'] !== NULL) { ?>, '<?= $audits['Assistant1Name']; ?>', '<?= $audits['Assistant1FirstName']; ?>' <?php } if($audits['Assistant2Name'] && $audits['Assistant2FirstName'] !== NULL) { ?>, '<?= $audits['Assistant2Name']; ?>', '<?= $audits['Assistant2FirstName']; ?>' <?php } ?>)" ><i class="fas fa-eye"></i> Voir les résultats</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php

    // Else shows a error message

    } else { ?>

        <div id="errorMessage">Cet utilisateur n'a pas encore été audité !</div>
    
    <?php }

} ?>