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

if(isAdmin($userInfos['Role'])) {

    function showTable($editedUserID) {

        global $db;

        $getEditedUserInfo = $db->prepare('SELECT u.ID, u.Name, u.FirstName, u.Email, f.Localisation, u.Role, u.InvitedBy, u.Activated 
        FROM Users u LEFT JOIN Facilities f ON u.Localisation = f.ID WHERE u.ID = :editedUser');
        $getEditedUserInfo->bindParam(':editedUser', $editedUserID, PDO::PARAM_INT);
        $getEditedUserInfo->execute();
        $editedUserInfo = $getEditedUserInfo->fetch(); ?>

        <table id="tableauUserInfos">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Localisation</th>
                    <th>Rôle</th>
                    <th>Modifications</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="user-email-<?= $editedUserInfo['ID']; ?>" data-label="Email"><?= $editedUserInfo['Email']; ?></td>
                    <td class="border-black" id="user-localisation-<?= $editedUserInfo['ID']; ?>" data-label="Localisation"><?= $editedUserInfo['Localisation']; ?></td>
                    <td class="border-black" id="user-role-<?= $editedUserInfo['ID']; ?>" data-label="Role"><?= roleToStr($editedUserInfo['Role']); ?></td>
                    <td data-label="Modifications">
                        <div class="buttonsModificationTableau">
                            <button onclick="updateEmail(<?= $editedUserInfo['ID']; ?>)"><i class="fas fa-at"></i> Email</button>
                            <button id="updateLocalisationButton" onclick="updateLocalisation(<?= $editedUserInfo['ID']; ?>)"><i class="fas fa-map-marker-alt"></i> Localisation</button>
                            <button id="updateRoleButton" onclick="updateRole(<?= $editedUserInfo['ID']; ?>, <?= intval($editedUserInfo['Role']); ?>)"><i class="fas fa-shield-alt"></i> Rôle</button>
                            <?php if($editedUserInfo['Activated'] == true) { ?>
                                <button class="redButton" id="updateStatus" onclick="updateStatus(this.value, <?= $editedUserInfo['ID']; ?>)" value="disable"><i class='fas fa-toggle-on'></i> Désactiver</button>
                            <?php } else { ?>
                                <button class="greenButton" id="updateStatus" onclick="updateStatus(this.value,<?= $editedUserInfo['ID']; ?>)" value="enable"><i class="fas fa-toggle-off"></i> Activer</button>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    <?php }

    if(isset($_POST['getLocalisations']) && isset($_POST['localisationText']) && !empty($_POST['localisationText']) && isset($_POST['editedUserID']) && !empty($_POST['editedUserID'])) {

        $getLocalisations = $db->query('SELECT ID, Localisation FROM Facilities ORDER BY Localisation ASC'); ?>

        <select onchange="editLocalisation(<?= $_POST['editedUserID']; ?>, this.value)" id="changeLocalisation">
            <?php while($localisations = $getLocalisations->fetch()) { ?>
                <option value="<?= $localisations['ID']; ?>" <?php if($_POST['localisationText'] == $localisations['Localisation']) { ?> selected <?php } ?>><?= $localisations['Localisation']; ?></option>
            <?php } ?>
        </select>

    <?php }

    if(isset($_POST['getEditedUserInfo']) && isset($_POST['editedUserID']) && !empty($_POST['editedUserID'])) {

        showTable($_POST['editedUserID']);

    } elseif(isset($_POST['operation']) && !empty($_POST['operation']) && isset($_POST['editedUserID']) && !empty($_POST['editedUserID'])) {

        if($_POST['operation'] == "editEmail" && isset($_POST['updatedEmail']) && !empty($_POST['updatedEmail'])) {

            $updatedEmail = htmlspecialchars($_POST['updatedEmail']);

            if(filter_var($updatedEmail, FILTER_VALIDATE_EMAIL)) {

                try {

                    $editEmail = $db->prepare('UPDATE Users SET Email = :updatedEmail WHERE ID = :editedUserID');
                    $editEmail->bindParam(':updatedEmail', $updatedEmail, PDO::PARAM_STR);
                    $editEmail->bindParam(':editedUserID', $_POST['editedUserID'], PDO::PARAM_INT);
                    $editEmail->execute();

                    echo "L'adresse email a bien été modifiée !";

                } catch(PDOException $e) {

                    echo $e;

                }

            }

        }

        if($_POST['operation'] == "editLocalisation" && isset($_POST['newLocalisationID']) && !empty($_POST['newLocalisationID'])) {

            $newLocalisation = htmlspecialchars($_POST['newLocalisationID']);

            try {

                $editLocalisation= $db->prepare('UPDATE Users SET Localisation = :updatedLocalisation WHERE ID = :editedUserID');
                $editLocalisation->bindParam(':updatedLocalisation', $newLocalisation, PDO::PARAM_INT);
                $editLocalisation->bindParam(':editedUserID', $_POST['editedUserID'], PDO::PARAM_INT);
                $editLocalisation->execute();

                echo "La localisation a bien été modifiée !";

            } catch(PDOException $e) {

                echo $e;

            }

        } elseif($_POST['operation'] == "editRole" && isset($_POST['newRole']) && !empty($_POST['newRole'])) {

            $newRole = htmlspecialchars($_POST['newRole']);

            try {

                $editLocalisation= $db->prepare('UPDATE Users SET Role = :newRole WHERE ID = :editedUserID');
                $editLocalisation->bindParam(':newRole', $newRole, PDO::PARAM_INT);
                $editLocalisation->bindParam(':editedUserID', $_POST['editedUserID'], PDO::PARAM_INT);
                $editLocalisation->execute();

                echo "Le role a bien été modifié !";

            } catch(PDOException $e) {

                echo $e;

            }

        } elseif($_POST['operation'] == "disable") {

            $activated = false;

            try {

                $updateStatus = $db->prepare('UPDATE Users SET Activated = :activated WHERE ID = :editedUserID');
                $updateStatus->bindValue(':activated', $activated, PDO::PARAM_BOOL);
                $updateStatus->bindValue(':editedUserID', $_POST['editedUserID'], PDO::PARAM_INT);
                $updateStatus->execute();

                echo "Opération réussie !";

            } catch(PDOException $e) {

                echo $e;

            }

        } elseif($_POST['operation'] == "enable") {

            $activated = true;

            try {

                $updateStatus = $db->prepare('UPDATE Users SET Activated = :activated WHERE ID = :editedUserID');
                $updateStatus->bindValue(':activated', $activated, PDO::PARAM_BOOL);
                $updateStatus->bindValue(':editedUserID', $_POST['editedUserID'], PDO::PARAM_INT);
                $updateStatus->execute();

                echo "Opération réussie !";

            } catch(PDOException $e) {

                echo $e;

            }

        }

    }

} else {

    echo "Acces refusé !";
    return;

} ?>