<?php

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé !";
    return;

}

if(isset($_POST['operation']) && isset($_POST['bugReportID']) && !empty($_POST['bugReportID'])) {

    if($_POST['operation'] == "deleteBugReport") {

        $bugReportID = htmlspecialchars($_POST['bugReportID']);

        $deleteBugReport = $db->prepare('DELETE FROM BugReports WHERE ID = :bugReportID');
        $deleteBugReport->bindParam(':bugReportID', $bugReportID, PDO::PARAM_INT);
        $deleteBugReport->execute();

        $getBugReports = $db->query('SELECT bg.ID, bg.Bug, bg.ErrorCode, bg.DateAndHour, bg.SubmittedBy, u.Name, u.FirstName FROM BugReports bg 
        LEFT JOIN Users u ON bg.SubmittedBy = u.ID ORDER BY DateAndHour ASC');
        $countBugReports = $getBugReports->rowCount();

        if($countBugReports == 0) { ?>

            <div id="errorMessage">Aucun rapport de bugs à afficher pour le moment !</div>

        <?php } else { ?>

            <div id="titleContainer">
                <h3>Rapports de bug</h3>
            </div>
            <table id="tableauRapportsBug">
                <thead>
                    <tr>
                        <th>Bug</th>
                        <th>Code d'erreur</th>
                        <th>Date</th>
                        <th>Créé par</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($bugReports = $getBugReports->fetch()) { ?>
                    <tr>
                        <td data-label="Bug"><?= $bugReports['Bug']; ?></td>
                        <td class="border-black" data-label="Code d'erreur">
                            <?php if($bugReports['ErrorCode'] !== null ) { echo $bugReports['ErrorCode']; } else { echo "Aucun"; }?></td>
                        <td class="border-black" data-label="Date">le <?= dateConvert($bugReports['DateAndHour']); ?></td>
                        <td class="border-black" data-label="Créé par"><?= $bugReports['Name'] . " " . $bugReports['FirstName']; ?></td>
                        <td><button class="redButton centerMargin" onclick="deleteBugReport(<?= $bugReports['ID']; ?>)"><i class="fas fa-trash-alt"></i> Supprimer</button></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } ?>

    <?php }

}

if(isset($_POST['bug']) && !empty($_POST['bug'])) {

    // Insert the bug report into the database with the errorcode if the user gives one

    $insertBugReport = $db->prepare('INSERT INTO BugReports(Bug, ErrorCode, SubmittedBy) VALUES(:bug, :errorCode, :user)');
    $insertBugReport->bindParam(':bug', $_POST['bug'], PDO::PARAM_STR);
    if(isset($_POST['errorCode']) && !empty($_POST['errorCode'])) {

        $insertBugReport->bindParam(':errorCode', $_POST['errorCode'], PDO::PARAM_STR);

    } else {

        $errorCode = null;
        $insertBugReport->bindParam(':errorCode', $errorCode, PDO::PARAM_NULL);
        
    }

    $insertBugReport->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $insertBugReport->execute();

    echo "Votre rapport de bug a bien été reçu !";
    return;
    
}

?>