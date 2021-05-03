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

    // News generation

    if(isset($_POST['actualites'])) {

        // Get the last 15 autoevaluations and/or audits completed in the actualites table

        $reqActus = $db->query(
            "SELECT u.ID, u.Name, u.FirstName, n.Actualite, n.Audit_Type, n.Eval_Number, n.Audit_Number, n.DateAndHour, f.Localisation,
                (SELECT u.Name FROM Users u WHERE n.Auditor = u.ID) AS 'auditeurName',
                (SELECT u.FirstName FROM Users u WHERE n.Auditor = u.ID) AS 'auditeurFirstName',
                (SELECT u.Name FROM Users u WHERE n.Assistant1 = u.ID) AS 'Assistant1Name',
                (SELECT u.FirstName AS 'Assistant1FirstName' FROM Users u WHERE n.Assistant1 = u.ID) AS 'Assistant1FirstName',
                (SELECT u.Name FROM Users u WHERE n.Assistant2 = u.ID) AS 'Assistant2Name',
                (SELECT u.FirstName AS 'Assistant1FirstName' FROM Users u WHERE n.Assistant2 = u.ID) AS 'Assistant2FirstName'
            FROM News n 
            LEFT JOIN Users u ON n.User = u.ID 
            LEFT JOIN Facilities f ON n.Facility = f.ID ORDER BY DateAndHour DESC LIMIT 15"
        );
        $countActus = $reqActus->rowCount();

        // if no records are found -> a error message is displayed instead

        if($countActus == 0) { ?>

            <div id="errorMessage">Aucune actualité à afficher pour le moment !</div>

        <?php } else { ?>

            <div id="titleContainer">
                <h3>Fil d'actualité</h3>
            </div>
            <table id="tableauActualites">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Actualité</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($actu = $reqActus->fetch()) { ?>

                    <?php if($_SESSION['ID'] !== $actu['ID']) { ?>

                        <tr>
                            <td data-label="Utilisateur"><?= $actu['Name'] . " " . $actu['FirstName']; ?></td>
                            <td data-label="Actualité"><?= $actu['Actualite']; ?> <?php if($actu['Audit_Type'] !== null) { if($actu['Audit_Type'] == 1) { ?> (Audit formateur) <?php } elseif($actu['Audit_Type'] == 2) { ?>(Audit Qualiopi) <?php } } ?></td>
                            <td data-label="Date">le <?= dateConvert($actu['DateAndHour']); ?></td>
                            <?php if($actu['Eval_Number'] !== null) { ?>
                                <td><button class="button-show-autoeval-result" onclick="showUserResultEval(<?= $actu['ID']; ?>, <?= $actu['Eval_Number']; ?>, '<?= $actu['Name']; ?>', '<?= $actu['FirstName']; ?>', '<?= dateConvert($actu['DateAndHour']); ?>')"><i class="fas fa-eye"></i> Voir les résultats</button></td>
                            <?php } ?>
                            <?php if($actu['Audit_Number'] !== null) {?>
                                <td><button class="button-show-audit-result" onclick="showUserResultAudit(<?= $actu['ID']; ?>, <?= $actu['Audit_Type']; ?>, <?= $actu['Audit_Number']; ?>, '<?= $actu['Name']; ?>', '<?= $actu['FirstName']; ?>', '<?= $actu['auditeurName']; ?>', '<?= $actu['auditeurFirstName']; ?>', '<?= dateConvert($actu['DateAndHour']); ?>','<?= $actu['Localisation']; ?>'<?php if($actu['Assistant1Name'] && $actu['Assistant1FirstName'] !== NULL) { ?>, '<?= $actu['Assistant1Name']; ?>', '<?= $actu['Assistant1FirstName']; ?>' <?php } if($actu['Assistant2Name'] && $actu['Assistant2FirstName'] !== NULL) { ?>, '<?= $actu['Assistant2Name']; ?>', '<?= $actu['Assistant2FirstName']; ?>' <?php } ?>)"><i class="fas fa-eye"></i> Voir les résultats</button></td>
                            <?php } ?>
                        </tr>

                    <?php } ?>

                <?php } ?>
                </tbody>
            </table>

        <?php }

    }

    // Autoevaluation editor generator

    if(isset($_POST['autoevaluation'])) {

        // Get all the autoevaluation questions and their categories

        $getQuestions = $db->query('SELECT qa.ID, qa.Question, qa.Category, qa.Active, cqa.Name FROM QuestionsAutoevaluation qa LEFT JOIN CategoriesQuestionsAutoevaluation cqa ON qa.Category = cqa.Category ORDER BY cqa.Category, qa.ID ASC');

        // Get all the categories for the question add toolbar

        $getCategories = $db->query('SELECT * FROM CategoriesQuestionsAutoevaluation ORDER BY Category ASC');

    ?>


        <div id="titleContainer">
            <h3>Modification autoévaluation</h3>
        </div>
        <!-- Question add toolbar -->
        <div class="userInputBar">
            <button id="autoevalAddQuestionButton" onclick="showAdminForm('autoeval')"><i class="fas fa-plus"></i> Ajouter une question</button>
            <div class="autoeval-param-ajoutquestion" id="autoevalFormContainer">
                <input type="text" id="newQuestion" placeholder="Ajouter une nouvelle question...">
                <select id="categorie">
                    <option value="">Veuillez selectionner une catégorie</option>
                    <?php while($categories = $getCategories->fetch()) { ?>
                        <option value="<?= $categories['Category']; ?>"><?= $categories['Name']; ?></option>
                    <?php } ?>
                </select>
                <div id="message"></div>
                <div class="autoeval-param-ajoutquestion-buttonsContainer">
                    <button class="greenButton" onclick="addQuestionQuestionnaire('autoevaluation')"><i class="fas fa-check"></i></button>
                    <button class="redButton" onclick="showAdminForm('autoeval')"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>

        <table id="tableauQuestionnaire">
            <thead>
                <tr>
                    <th>Statut</th>
                    <th>Question</th>
                    <th>Categorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($question = $getQuestions->fetch()) { ?>
                    <tr>
                        <td data-label="Statut" id="questionStatus<?= $question['ID']; ?>"><?php if($question['Active'] == true) { ?>Active<?php } else { ?>Inactive<?php } ?></td>
                        <td data-label="Question" class="border-black" id="question-label-<?= $question['ID']; ?>"><?= $question['Question']; ?></td>
                        <td data-label="Catégorie" class="border-black"><?= $question['Name']; ?></td>
                        <td data-label="Actions">
                            <div class="buttonsModificationTableau">
                                <button <?php if($question['Active'] == true) { ?> class="redButton"<?php } else { ?> class="greenButton"<?php } ?>
                                <?php if($question['Active'] == true) { ?> id="disable<?= $question['ID']; ?>" <?php } else { ?> id="enable<?= $question['ID']; ?>"<?php } ?> 
                                onclick="updateStatusRemoveQuestionQuestionnaire('autoeval', this, <?= $question['ID']; ?>)" 
                                <?php if($question['Active'] == true) { ?> value="disable" <?php } else { ?> value="enable" <?php } ?>>
                                    <?php if($question['Active'] == true) { ?> <i class="fas fa-toggle-on"></i> Désactiver<?php } else { ?><i class="fas fa-toggle-off"></i> Activer<?php } ?>
                                </button>
                                <button onclick="updateQuestionQuestionnaire('autoeval', <?= $question['ID']; ?>)" value="edit"><i class="far fa-edit"></i> Modifier</button>
                                <button class="redButton" onclick="updateStatusRemoveQuestionQuestionnaire('autoeval', this, <?= $question['ID']; ?>)" value="delete"><i class="fas fa-trash-alt"></i> Supprimer</button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php 

    }

    // Documents generator

    if(isset($_POST['documents'])) {

        // Get all the documents

        $getDocuments = $db->query('SELECT * FROM Documents ORDER BY Documents.Document ASC');

        // Count the number of results

        $countDocuments = $getDocuments->rowCount();

    ?>

        <div id="titleContainer">
            <h3>Documents</h3>
        </div>
        <div class="userInputBar">
        <button onclick="addDocument()" id="addDocumentButton"><i class="fas fa-plus"></i> Ajouter un document</button>
        <form id="formDocument" method="POST" enctype="multipart/form-data">
            <input type="file" name="addDocument" id="addDocument">
        </form>
        </div>

        <!-- If the number of results is different from 0 shows the document in a table -->
        <?php if($countDocuments !== 0) { ?>
            
            <table id="tableauDocuments">
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($documents = $getDocuments->fetch()) { ?>
                        <tr>
                            <td id="<?= $documents['ID']; ?>" data-label="Document"><?= $documents['Document']; ?></td>
                            
                            <td data-label="Actions">
                                <div class="buttonsModificationTableau">
                                    <button><a target="_blank" href="documents/<?= $documents['Link']; ?>"><i class="fas fa-eye"></i> Voir</a></button>
                                    <button class="redButton" onclick="deleteDocument(this.value, <?= $documents['ID']; ?>, '<?= $documents['Link']; ?>')" value="delete"><i class="fas fa-trash-alt"></i> Supprimer</button>
                                </div>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>

        <!-- shows a error message in a table -->
        <?php } else { ?>

            <table id="tableauDocuments">
                <thead>
                    <tr>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <td data-label="Message">Aucun documents disponible</td>
                </tbody>
            </table>

        <?php }

    }

    if(isset($_POST['audit'])) {

        $getAuditsTypes = $db->query('SELECT * FROM TypesAudits'); ?>

        <div id="titleContainer">
            <h3>Modification audit</h3>
        </div>
        <div id="selectContainer">
            <select id="modificationAuditSelect" onchange="generateModifyingAuditTool(this.value)">
                <option value="">Veuillez sélectionner un audit à modifier</option>
                <?php while($auditsTypes = $getAuditsTypes->fetch()) { ?>
                    <option value="<?= $auditsTypes['ID']; ?>"><?= $auditsTypes['Name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div id="modifyingToolContainer"></div>

    <?php }

    if(isset($_POST['utilisateurs'])) {

        /*
        Select the id, name and first of all the users who have the role 1 (formateur) in the database
        to display them in the user select toolbar    
    */

        $getUsers = $db->query('SELECT ID, Name, FirstName FROM Users WHERE Role = 1');

        ?>
        
        <div id="titleContainer">
            <h3>Examiner les résultats d'un utilisateur</h3>
        </div>
        <div class="userInputBar">
            <select id="listUsersSelect" onchange="getAllResultsUser(this.value)">
                <option value="">Séléctionnez un utilisateur</option>
                <?php while($users = $getUsers->fetch()) {?>
                    <option value="<?= $users['ID']; ?>"><?= $users['Name'] . " " . $users['FirstName']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div id="userAutoevals"></div>
        <div id="userAudits"></div>

    <?php }

    if(isset($_POST['globalStats'])) { 
        
        $allAuditsTypes = $db->query('SELECT * FROM TypesAudits');?>

        <div id="titleContainer">
            <h3>Statistiques globales</h3>
        </div>
        <div class="container-select">
            <select id="globalStatsSelect" onchange="showStats(this.value)">
            <option value="">Selectionner un type d'audit</option>
            <?php while($auditsTypes = $allAuditsTypes->fetch()) { ?>
                <option value="<?= $auditsTypes['ID']; ?>"><?= $auditsTypes['Name']; ?></option>
            <?php } ?>
            </select>
        </div>
        <div id="globalStats-container"></div>

    <?php }

    if(isAdmin($userInfos['Role']) && isset($_POST['usersManagement'])) { 
        
        $getAllUsers = $db->prepare('SELECT ID, Name, FirstName FROM Users WHERE ID <> :user ORDER BY FirstName ASC');
        $getAllUsers->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $getAllUsers->execute(); ?>

        <div id="titleContainer">
            <h3>Gestion utilisateurs</h3>
        </div>
        <select id="userSelect" onchange="editUser(this.value)">
            <option value="">Veuillez sélectionner un utilisateur</option>
            <?php while($allUsers = $getAllUsers->fetch()) { ?>
                <option value="<?= $allUsers['ID']; ?>"><?= $allUsers['Name'] . " " . $allUsers['FirstName']; ?></option>
            <?php } ?>
        </select>
        <div id="userModifications"></div>
    <?php }

    if(isAdmin($userInfos['Role']) && isset($_POST['bugReports'])) { 
            
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

} else {

    echo "Acces refusé !";
    return;

} ?>