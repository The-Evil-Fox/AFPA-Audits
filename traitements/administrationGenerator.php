<?php

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['actualites'])) {

    $reqActus = $db->query('SELECT u.ID, u.Name, u.FirstName, a.Actualite, a.Eval_Number, a.Audit_Number, a.DateAndHour, ca.Localisation FROM Actualites a 
    LEFT JOIN Users u ON a.User = u.ID LEFT JOIN CentresAFPA ca ON a.Centre = ca.ID ORDER BY DateAndHour DESC');

    while($actu = $reqActus->fetch()) { ?>

        <?php if($_SESSION['ID'] !== $actu['ID']) { ?>
            
            <div class="card text-center">
                <div class="card-content">
                    <span class="actu-date"><?= dateConvert($actu['DateAndHour']); ?></span>
                    <?= $actu['Name'] . " " . $actu['FirstName'] . " " . $actu['Actualite']; ?>
                    <?php if($actu['Eval_Number'] !== null) { ?>
                        <button class="button-show-autoeval-result" onclick="showUserResultEval(<?= $actu['ID']; ?>, <?= $actu['Eval_Number']; ?>, '<?= $actu['Name']; ?>', '<?= $actu['FirstName']; ?>', '<?= dateConvert($actu['DateAndHour']); ?>')">Voir ses résultats</button>
                    <?php } ?>
                    <?php if($actu['Audit_Number'] !== null) {?>
                        <button class="button-show-audit-result" onclick="showUserResultAudit(<?= $actu['ID']; ?>, <?= $actu['Audit_Number']; ?>, '<?= $actu['Name']; ?>', '<?= $actu['FirstName']; ?>', '<?= dateConvert($actu['DateAndHour']); ?>', '<?= $actu['Localisation']; ?>')">Voir ses résultats</button>
                    <?php } ?>
                </div>
            </div>

        <?php } ?>

    <?php }

}

if(isset($_POST['autoEvalution'])) {

    $getQuestions = $db->query('SELECT * FROM QuestionsAutoevaluation ORDER BY ID ASC');
    $getCategories = $db->query('SELECT * FROM CategoriesQuestionsAutoevaluation ORDER BY Category ASC');

?>
    <div id="questionnairesParams">
        <h3>Ajouter une question au formulaire d'autoevaluation</h3>
        <div class="questionnaires-param-ajoutquestion">
            <input type="text" id="newQuestion" placeholder="Ajouter une nouvelle question...">
            <select id="categorie">
                <option value="">Veuillez selectionner une catégorie</option>
                <?php while($categories = $getCategories->fetch()) { ?>
                    <option value="<?= $categories['Category']; ?>"><?= $categories['Name']; ?></option>
                <?php } ?>
            </select>
            <button onclick="addQuestionQuestionnaire('autoevaluation')">Ajouter</button>
        </div>
        <h3>Liste des questions présentes dans la base de données</h3>
        <div class="questionnaires-param-questions" id="tableauQuestionsQuestionnaires">
            <?php while($question = $getQuestions->fetch()) { ?>
                <div class="tableau" id="question<?= $question['ID']; ?>">
                    <div class="question" id="question-container-<?= $question['ID']; ?>">
                        <span id="questionStatus<?= $question['ID']; ?>"><?php if($question['Active'] == true) { ?>[ACTIVE] <?php } else { ?>[INACTIVE] <?php } ?></span>
                        <span id="question-label-<?= $question['ID']; ?>"><?= $question['Question']; ?></span>
                    </div>
                    <div class="buttons">
                        <button <?php if($question['Active'] == true) { ?> id="disable<?= $question['ID']; ?>" <?php } else { ?> id="enable<?= $question['ID']; ?>"<?php } ?> onclick="updateStatusRemoveQuestionQuestionnaire('autoeval', this, <?= $question['ID']; ?>)" <?php if($question['Active'] == true) { ?> value="disable" <?php } else { ?> value="enable" <?php } ?>>Activer / Désactiver</button>
                        <button onclick="updateQuestionQuestionnaire('autoeval', <?= $question['ID']; ?>)" value="edit">Modifier</button>
                        <button onclick="updateStatusRemoveQuestionQuestionnaire('autoeval', this, <?= $question['ID']; ?>)" value="delete">Supprimer</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

<?php 

}

if(isset($_POST['documents'])) {

    $getDocuments = $db->query('SELECT * FROM Documents ORDER BY Documents.Document ASC');

?>
    <div id="tableauDocuments">
        <h3>Ajouter un document</h3>
            <div class="ajout-document">
            <form id="formDocument" method="POST" enctype="multipart/form-data">
                <input type="file" name="addDocument" id="addDocument">
            </form>
            <button onclick="addDocument()">Ajouter</button>
            </div>
        <h3>Liste des documents disponibles</h3>
        <?php while($documents = $getDocuments->fetch()) { ?>
            <div class="document" id="<?= $documents['ID']; ?>">
                <div class="document-name"><i class="fas fa-paperclip"></i><a target="_blank" href="documents/<?= $documents['Link']; ?>"><?= $documents['Document']; ?></a></div>
                <div class="document-buttons"><button onclick="deleteDocument(this.value, <?= $documents['ID']; ?>, '<?= $documents['Link']; ?>')" value="delete">Supprimer</button></div>
            </div>
        <?php } ?>
    </div>

<?php

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
            "label"=> "Conformités", "y"=> $compliances, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
        );

    }

    if($NC !== 0) {

        $NCTab = array(
            "label"=> "Non-conformités", "y"=> $NC, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
        );

    }

    if($NA !== 0) {

        $NATab = array(
            "label"=> "Non applicables", "y"=> $NA, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
        );

    }

    if($NDA !== 0) {

        $NDATab = array(
            "label"=> "Non disponibles actuellement", "y"=> $NDA, "indexLabelFontColor" => "#FFFFFF", "indexLabelFontWeight" => "bolder"
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

if(isset($_POST['audit'])) {

    $getQuestions = $db->query('SELECT * FROM QuestionsAudit ORDER BY ID ASC');
    $getCategories = $db->query('SELECT * FROM CategoriesQuestionsAudit ORDER BY ID ASC');

?>
    <div id="questionnairesParams">
        <h3>Ajouter une question au formulaire d'audit</h3>
        <div class="questionnaires-param-ajoutquestion">
            <input type="text" id="newQuestion" placeholder="Ajouter une nouvelle question...">
            <input type="text" id="preuvesQuestion" placeholder="Insérer les preuves à fournir (optionnel)">
            <select id="categorie">
                <option value="">Veuillez selectionner une catégorie</option>
                <?php while($categories = $getCategories->fetch()) { ?>
                    <option value="<?= $categories['ID']; ?>"><?= $categories['Name']; ?></option>
                <?php } ?>
            </select>
            <button onclick="addQuestionQuestionnaire('audit')">Ajouter</button>
        </div>
        <h3>Liste des questions présentes dans la base de données</h3>
        <div class="questionnaires-param-questions" id="tableauQuestionsQuestionnaires">
            <?php while($question = $getQuestions->fetch()) { ?>
                <div class="tableau" id="question<?= $question['ID']; ?>">
                    <div class="question" id="question-container-<?= $question['ID']; ?>">
                        <span id="questionStatus<?= $question['ID']; ?>"><?php if($question['Active'] == true) { ?>[ACTIVE] <?php } else { ?>[INACTIVE] <?php } ?></span>
                        <span id="question-label-<?= $question['ID']; ?>"><?= $question['Thematique']; ?></span>
                    </div>
                    <div class="buttons">
                        <button <?php if($question['Active'] == true) { ?> id="disable<?= $question['ID']; ?>" <?php } else { ?> id="enable<?= $question['ID']; ?>"<?php } ?> onclick="updateStatusRemoveQuestionQuestionnaire('audit', this, <?= $question['ID']; ?>)" <?php if($question['Active'] == true) { ?> value="disable" <?php } else { ?> value="enable" <?php } ?>>Activer / Désactiver</button>
                        <button onclick="updateQuestionQuestionnaire('audit', <?= $question['ID']; ?>)" value="edit">Modifier</button>
                        <button onclick="updateStatusRemoveQuestionQuestionnaire('audit', this, <?= $question['ID']; ?>)" value="delete">Supprimer</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>



<?php }

if(isset($_POST['utilisateurs'])) {

    $getUsers = $db->query('SELECT ID, Name, FirstName FROM Users WHERE Role = 1');

    ?>
    
    <div id="usersList">
        <select id="listUsersSelect" onchange="getAllResultsUser(this.value)">
            <option value="">Selectionner un utilisateur</option>
            <?php while($users = $getUsers->fetch()) {?>
                <option value="<?= $users['ID']; ?>"><?= $users['Name'] . " " . $users['FirstName']; ?></option>
            <?php } ?>
        </select>
        <div id="userAutoevals"></div>
        <div id="userAudits"></div>
    </div>

<?php }

?>