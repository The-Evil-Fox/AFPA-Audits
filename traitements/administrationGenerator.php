<?php

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['actualites'])) {

    $reqActus = $db->query('SELECT u.ID, u.Name, u.Firstname,a.Actualite, a.Eval_Number, a.Audit_Number, a.DateAndHour FROM Actualites a 
    LEFT JOIN Users u ON a.User = u.ID ORDER BY DateAndHour DESC');

    while($actu = $reqActus->fetch()) { ?>

        <?php if($_SESSION['ID'] !== $actu['ID']) { ?>
            
            <div class="card text-center">
                <div class="card-content">
                    <span class="actu-date"><?= dateConvert($actu['DateAndHour']); ?></span>
                    <?= $actu['Name'] . " " . $actu['Firstname'] . " " . $actu['Actualite']; ?>
                    <?php if($actu['Eval_Number'] !== null) { ?>
                        <button class="button-show-autoeval-result" onclick="showUserResultEval(<?= $actu['ID']; ?>, <?= $actu['Eval_Number']; ?>, '<?= $actu['Name']; ?>', '<?= $actu['Firstname']; ?>')">Voir ses résultats</button>
                    <?php } ?>
                    <?php if($actu['Audit_Number'] !== null) {?>
                        <button class="button-show-audit-result" onclick="showUserResultAudit(<?= $actu['ID']; ?>, <?= $actu['Audit_Number']; ?>, '<?= $actu['Name']; ?>', '<?= $actu['Firstname']; ?>')">Voir ses résultats</button>
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
    <div id="autoevalParams">
        <h3>Ajouter une question au formulaire d'autoevaluation</h3>
        <div class="autoeval-param-ajoutquestion">
            <input type="text" id="newQuestion" placeholder="Ajouter une nouvelle question...">
            <select id="categorie">
                <option value="">Veuillez selectionner une catégorie</option>
                <?php while($categories = $getCategories->fetch()) { ?>
                    <option value="<?= $categories['Category']; ?>"><?= $categories['Name']; ?></option>
                <?php } ?>
            </select>
            <button onclick="addQuestion()">Ajouter</button>
        </div>
        <h3>Liste des questions présentes dans la base de données</h3>
        <div class="autoeval-param-questions" id="tableauQuestionsAutoEval">
            <?php while($question = $getQuestions->fetch()) { ?>
                <div class="tableau" id="question<?= $question['ID']; ?>">
                    <div class="question" id="question-container-<?= $question['ID']; ?>">
                        <span id="questionStatus<?= $question['ID']; ?>"><?php if($question['Active'] == true) { ?>[ACTIVE] <?php } else { ?>[INACTIVE] <?php } ?></span>
                        <span id="question-label-<?= $question['ID']; ?>"><?= $question['Question']; ?></span>
                    </div>
                    <div class="buttons">
                        <button <?php if($question['Active'] == true) { ?> id="disable<?= $question['ID']; ?>" <?php } else { ?> id="enable<?= $question['ID']; ?>"<?php } ?> onclick="updateStatusRemoveQuestionAutoEval(this, <?= $question['ID']; ?>)" <?php if($question['Active'] == true) { ?> value="disable" <?php } else { ?> value="enable" <?php } ?>>Activer / Désactiver</button>
                        <button onclick="updateQuestionAutoEval(this, <?= $question['ID']; ?>)" value="edit">Modifier</button>
                        <button onclick="updateStatusRemoveQuestionAutoEval(this, <?= $question['ID']; ?>)" value="delete">Supprimer</button>
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

?>