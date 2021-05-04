<?php

require_once('../config/dbConnection.php');
require_once('../config/reqUser.php');
require_once('../config/roles.php');
require_once('../config/dateConvert.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé !";
    return;

}

// Checks if the user is authorised to access this, if not disconnect the user

if(isAdmin($userInfos['Role']) || isAuditeur($userInfos['Role'])) {

    if(isset($_POST['modificationAudit']) && isset($_POST['auditType']) && !empty($_POST['auditType'])) {

        if($_POST['auditType'] == 1) {

            // Get all the audit questions and their categories

            $getQuestions = $db->query('SELECT qaf.ID, qaf.Question, qaf.Evidence, qaf.Active, cqaf.Name FROM QuestionsAuditFormateur qaf LEFT JOIN 
            CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID ORDER BY cqaf.ID, qaf.ID ASC');

            // Get all the categories for the question add toolbar

            $getCategories = $db->query('SELECT * FROM CategoriesQuestionsAuditFormateur ORDER BY ID ASC');

            ?>
            <div class="userInputBar">
                <button id="auditAddQuestionButton" onclick="showAdminForm('audit')"><i class="fas fa-plus"></i> Ajouter une question</button>
                <div class="audit-param-ajoutquestion" id="auditFormContainer">
                    <input type="text" id="newQuestion" placeholder="Insérez une nouvelle question">
                    <input type="text" id="preuvesQuestion" placeholder="Insérez les preuves à fournir (optionnel)">
                    <select id="categorie">
                        <option value="">Veuillez selectionner une catégorie</option>
                        <?php while($categories = $getCategories->fetch()) { ?>
                            <option value="<?=  $categories['ID']; ?>"><?= $categories['Name']; ?></option>
                        <?php } ?>
                    </select>
                    <div id="message"></div>
                    <div class="audit-param-ajoutquestion-buttonsContainer">
                        <button class="greenButton" onclick="addQuestionQuestionnaire('audit', 1)"><i class="fas fa-check"></i></button>
                        <button class="redButton" onclick="showAdminForm('audit')"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>

            <table id="tableauQuestionnaire">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Question</th>
                        <th>Preuve</th>
                        <th>Categorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($question = $getQuestions->fetch()) { ?>
                        <tr>
                            <td data-label="Statut" id="questionStatus<?= $question['ID']; ?>"><?php if($question['Active'] == true) { ?>Active<?php } else { ?>Inactive<?php } ?></td>
                            <td data-label="Question" class="border-black" id="question-label-<?= $question['ID']; ?>"><?= $question['Question']; ?></td>
                            <td data-label="Preuve" class="border-black" id="question-evidence-<?= $question['ID']; ?>"><?php if($question['Evidence'] !== NULL) { echo $question['Evidence']; } else { echo "/"; } ?></td>
                            <td data-label="Categorie" class="border-black"><?= $question['Name']; ?></td>
                            <td data-label="Actions">
                                <div class="buttonsModificationQuestionnaire">
                                    <button <?php if($question['Active'] == true) { ?> class="redButton"<?php } else { ?> class="greenButton"<?php } ?>
                                    <?php if($question['Active'] == true) { ?> id="disable<?= $question['ID']; ?>" <?php } else { ?> id="enable<?= $question['ID']; ?>"<?php } ?>
                                    onclick="updateStatusRemoveQuestionQuestionnaire('audit', this, <?= $question['ID']; ?>, 1)"
                                    <?php if($question['Active'] == true) { ?> value="disable" <?php } else { ?> value="enable" <?php } ?>>
                                    <?php if($question['Active'] == true) { ?> <i class="fas fa-toggle-on"></i> Désactiver<?php } else { ?><i class="fas fa-toggle-off"></i> Activer<?php } ?>
                                    <button onclick="updateQuestionQuestionnaire('audit', <?= $question['ID']; ?>, 1)" value="edit"><i class="far fa-edit"></i> Modifier question</button>
                                    <button onclick="updateEvidenceQuestionnaire(<?= $question['ID']; ?>, 1)" value="edit"><i class="far fa-edit"></i> Modifier preuve</button>
                                    <button class="redButton" onclick="updateStatusRemoveQuestionQuestionnaire('audit', this, <?= $question['ID']; ?>, 1)" value="delete"><i class="fas fa-trash-alt"></i> Supprimer</button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } elseif($_POST['auditType'] == 2) {

            $getQuestions = $db->query('SELECT iq.ID, iq.Indicator, iq.Evidences, iq.Active, cq.Name FROM IndicatorsQualiopi iq LEFT JOIN 
            CriteriaQualiopi cq ON iq.Criteria = cq.ID ORDER BY cq.ID, iq.ID ASC');

            $getCategories = $db->query('SELECT * FROM CriteriaQualiopi ORDER BY ID ASC'); ?>

            <div class="userInputBar">
                <button id="auditAddQuestionButton" onclick="showAdminForm('audit')"><i class="fas fa-plus"></i> Ajouter une question</button>
                <div class="audit-param-ajoutquestion" id="auditFormContainer">
                    <input type="text" id="newQuestion" placeholder="Insérez l'indicateur">
                    <input type="text" id="preuvesQuestion" placeholder="Insérez la/les preuve(s) à fournir">
                    <div class="label-container">
                        <div class="add-label">Si vous insérez plusieurs preuves, merci de les séparés par un point.</div>
                        <div class="add-label">Veuillez également ne pas utiliser de doubles quotes (").</div>
                    </div>
                    <select id="categorie">
                        <option value="">Veuillez selectionner le critere associé à la question</option>
                        <?php while($categories = $getCategories->fetch()) { ?>
                            <option value="<?=  $categories['ID']; ?>"><?= $categories['Name']; ?></option>
                        <?php } ?>
                    </select>
                    <div id="message"></div>
                    <div class="audit-param-ajoutquestion-buttonsContainer">
                        <button class="greenButton" onclick="addQuestionQuestionnaire('audit', 2)"><i class="fas fa-check"></i></button>
                        <button class="redButton" onclick="showAdminForm('audit')"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>

            <table id="tableauQuestionnaire">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Indicateur</th>
                        <th>Preuves</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($question = $getQuestions->fetch()) { ?>
                        <tr>
                            <td data-label="Statut" id="questionStatus<?= $question['ID']; ?>"><?php if($question['Active'] == true) { ?>Active<?php } else { ?>Inactive<?php } ?></td>
                            <td data-label="Critere" class="border-black" id="question-label-<?= $question['ID']; ?>"><?= $question['Indicator']; ?></td>
                            <td data-label="Preuve" class="border-black" id="question-evidence-<?= $question['ID']; ?>"><?= $question['Evidences'];?></td>
                            <td data-label="Actions">
                                <div class="buttonsModificationQuestionnaire">
                                    <button <?php if($question['Active'] == true) { ?> class="redButton"<?php } else { ?> class="greenButton"<?php } ?>
                                    <?php if($question['Active'] == true) { ?> id="disable<?= $question['ID']; ?>" <?php } else { ?> id="enable<?= $question['ID']; ?>"<?php } ?>
                                    onclick="updateStatusRemoveQuestionQuestionnaire('audit', this, <?= $question['ID']; ?>, 2)"
                                    <?php if($question['Active'] == true) { ?> value="disable" <?php } else { ?> value="enable" <?php } ?>>
                                    <?php if($question['Active'] == true) { ?> <i class="fas fa-toggle-on"></i> Désactiver<?php } else { ?><i class="fas fa-toggle-off"></i> Activer<?php } ?>
                                    <button onclick="updateQuestionQuestionnaire('audit', <?= $question['ID']; ?>, 2)" value="edit"><i class="far fa-edit"></i> Modifier indicateur</button>
                                    <button onclick="updateEvidenceQuestionnaire(<?= $question['ID']; ?>, 2)" value="edit"><i class="far fa-edit"></i> Modifier preuve</button>
                                    <button class="redButton" onclick="updateStatusRemoveQuestionQuestionnaire('audit', this, <?= $question['ID']; ?>, 2)" value="delete"><i class="fas fa-trash-alt"></i> Supprimer</button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php }

    }

} else {

    echo "Acces refusé !";
    return;

} ?>