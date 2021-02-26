<?php

require_once('../config/dbConnection.php');

if(isset($_POST['autoEvalution'])) {

    $getQuestions = $db->query('SELECT * FROM Questions ORDER BY ID ASC');
    $getCategories = $db->query('SELECT * FROM CategoriesQuestions ORDER BY Category ASC');

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
                        <span id="questionStatus<?= $question['ID']; ?>"><?php if($question['Active'] == true) { ?>[ACTIVE]<?php } else { ?> [INACTIVE] <?php } ?></span>
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

<?php } ?>