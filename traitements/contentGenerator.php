<?php 

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['auditer'])) {

    $getFormateurs = $db->query('SELECT ID, Name, FirstName FROM Users WHERE Role = 1'); ?>

    <div id="startContainer">
        <div class="audit-logos-container">
            <img class="logo-qualite" src="assets/logoQualiteHautsDeFrance.png" alt="logo qualite hauts de france">
            <img class="logo-afpa" src="assets/logoAFPAWhite.png" alt="logo afpa blanc">
        </div>
        <div class="form-title">
            <h3>Audit formation</h3>
        </div>
        <div class="helper-container-start-screen">
            <div class="helper-content">
                Toutes les questions doivent être répondues.
            </div>
            <div class="helper-content">
                Si vous répondez négativement à une question, veuillez insérer la raison (observation) dans le champ qui apparaitra.
            </div>
            <div class="helper-content">
                Les réponses sont sauvegardées automatiquent lors de leurs ajouts et/ou modifications.
            </div>
            <div class="helper-content">
                <div class="help-shortcuts">Raccourcis clavier:</div>
                <div class="shortcuts">
                    <span><i class="far fa-caret-square-left"></i>Question précédente</span>
                    <span><i class="far fa-caret-square-right"></i>Question suivante</span>
                </div>
            </div>
            <div class="helper-content">
                Durée moyenne: 1 heure minimum.
            </div>
        </div>
        <div class="audit-selectuser-buttonstart-container">
            <select id="auditedUser" onchange="checkAuditInProgress(this.value)">
                <option value="">Veuillez selectionner l'utilisateur que vous voulez auditer</option>
                <?php while($allFormateurs = $getFormateurs->fetch()) {?>
                    <option value="<?= $allFormateurs['ID']; ?>"><?= $allFormateurs['Name'] . " " . $allFormateurs['FirstName']; ?></option>
                <?php } ?>
            </select>
            <span id="auditSelectMessage"></span>
            <div id="auditedCenterContainer"></div>
            <div id="assistantsContainer"></div>
            <button id="startAuditButton" class="button-start" onclick="startAudit(this.value)">Démarrer</button>
        </div>
    </div>

<?php }

if(isset($_POST['monespace'])) { 

    $checkEval = $db->prepare('SELECT Evaluation_Number, DateAndHour FROM Autoevaluations WHERE User_ID = :user AND Completed = true ORDER BY DateAndHour DESC');
    $checkEval->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $checkEval->execute();

    $countEvals = $checkEval->rowCount();

    if($countEvals == 0) { ?>

        <div id="errorMessage">
            Vous ne vous êtes pas encore autoévalué !
        </div>

    <?php } else { ?>

        <div id="titleContainer">
            <h3>Mes résultats</h3>
        </div>

        <table id="tableauQuestionnaire">
            <thead>
                <tr>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while($eval = $checkEval->fetch()) { ?>
                    <tr>
                        <td data-label="Date">Autoévaluation du <?php echo dateConvert($eval['DateAndHour']); ?></td>
                        <td data-label="Action"><button class="button-show-autoeval-result" type="button" onclick="showChart('<?= $eval['Evaluation_Number']; ?>','<?php echo dateConvert($eval['DateAndHour']); ?>')">Voir mes résultats</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

     <?php } ?>

<?php } ?>

<?php if(isset($_POST['autoevaluation'])) { ?>

    <?php

    $selectEvalInProgress = $db->prepare('SELECT Evaluation_Number FROM Autoevaluations WHERE User_ID = :user AND Completed = false');
    $selectEvalInProgress->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $selectEvalInProgress->execute();

    $countResult = $selectEvalInProgress->rowCount();

    if($countResult == 0) {

        $evaluationNumber = 1;

    } else {

        $evalInProgress = $selectEvalInProgress->fetch();
        $evaluationNumber = $evalInProgress['Evaluation_Number'];

    }

    if($countResult == 1) {

        $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM QuestionsAutoevaluation WHERE Active = true');
        $result = $countQuestions->fetch();
        $questionsNumber = (int) $result['nb_questions'];

        $selectQuestions = $db->prepare('SELECT qa.ID, qa.Question, ra.Answer, cqa.Name, rnca.Reason 
        FROM QuestionsAutoevaluation qa 
        LEFT JOIN CategoriesQuestionsAutoevaluation cqa ON qa.Category = cqa.Category 
        LEFT JOIN ResultatsAutoevaluations ra ON ra.Question = qa.ID  
                                             AND ra.User_ID = :user
                                             AND ra.Evaluation_Number = :evalNumber
        LEFT JOIN RaisonsNonConformitesAutoevaluation rnca ON rnca.Question_ID = qa.ID
                                           AND rnca.User = :user 
                                           AND rnca.Eval_Number = :evalNumber
        WHERE qa.Active = true 
        ORDER BY qa.ID;');
        $selectQuestions->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $selectQuestions->bindParam(':evalNumber', $evaluationNumber, PDO::PARAM_INT);
        $selectQuestions->execute();

        $evalNumber = $selectEvalInProgress->fetch();

        $compteur = 1; ?>

        <form method="POST" id="questionnaire">
            <div class="autoevaluation-logos-container">
                <img class="logo-qualite" src="assets/logoQualiteHautsDeFrance.png" alt="logo qualite hauts de france">
                <img class="logo-afpa" src="assets/logoAFPAWhite.png" alt="logo afpa blanc">
            </div>
            <?php while($questions = $selectQuestions->fetch()) { ?>
                <div class="form-part" id="question<?= $compteur; ?>">
                    <div class="question-number">
                        <?php if($compteur == $questionsNumber) { echo "Question finale"; } else { echo "Question n°$compteur"; } ?><img src="assets/tooltip.png" alt="infobulle" title="cliquez ici pour afficher l'aide" onclick="showTip('helper<?= $compteur; ?>')">
                    </div>
                    <div class="helper-container-questions" id="helper<?= $compteur; ?>">
                        <div class="helper-content">
                            Toutes les questions doivent être répondues.
                        </div>
                        <div class="helper-content">
                            Si vous répondez négativement à une question, veuillez insérer la raison dans le champ qui apparaitra.
                        </div>
                        <div class="helper-content">
                            Les réponses sont sauvegardées automatiquent lors de leurs ajouts et/ou modifications.
                        </div>
                        <div class="helper-content">
                            <div class="help-shortcuts">Raccourcis clavier:</div>
                            <div class="shortcuts">
                                <span><i class="far fa-caret-square-left"></i>Question précédente</span>
                                <span><i class="far fa-caret-square-right"></i>Question suivante</span>
                            </div>
                        </div>
                        <div class="helper-content">
                            Durée moyenne: 10 à 20 minutes.
                        </div>
                    </div>
                    <div class="category-question">
                        <?= $questions['Name']; ?>
                    </div>
                    <span class="question-label"><?= $questions['Question']; ?></span>
                    <div class="inputGroup">
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Oui" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Oui" <?php if($questions['Answer'] == "Oui") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>Oui">Oui</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Non" name="<?= $compteur; ?>" value="Non" onchange="showTextArea(<?= $compteur; ?>, true); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" <?php if($questions['Answer'] == "Non") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>Non">Non</label>
                        </div>
                        <textarea <?php if($questions['Answer'] == "Non") {?> class="visible" <?php } ?> name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez vous répondu non ?" cols="60" rows="5" onchange="addReason(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, this.id)"><?= $questions['Reason']; ?></textarea>
                        <?php if($compteur == $questionsNumber) { ?>
                            <div id="message"></div>
                        <?php } ?>
                        <div class="form-buttons">
                            <?php if($compteur > 1) { ?>
                                <button type="button" class="button-previous" onclick="previousQuestion(<?= $compteur; ?>)"><i class="fas fa-long-arrow-alt-left"></i> Précédent</button>
                            <?php } ?>
                            <?php if($compteur < $questionsNumber) { ?>
                                <button type="button" class="button-next" onclick="nextQuestion(<?= $compteur; ?>)">Suivant <i class="fas fa-long-arrow-alt-right"></i></button>
                            <?php } ?>
                            <?php if($compteur == $questionsNumber) { ?>
                                <button type="button" class="button-send greenButton" id="questionnaire-button-send" onclick="sendEval(<?= $evaluationNumber; ?>)">Finaliser mon auto-évaluation</button>
                                <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $compteur++; ?>
            <?php } ?>
        </form>


    <?php } else { ?>
    
        <div id="startContainer">
            <div class="autoevaluation-logos-container">
                <img class="logo-qualite" src="assets/logoQualiteHautsDeFrance.png" alt="logo qualite hauts de france">
                <img class="logo-afpa" src="assets/logoAFPAWhite.png" alt="logo afpa blanc">
            </div>
            <div class="form-title">
                <h3>Auto-évaluation</h3>
            </div>
            <div class="helper-container-start-screen">
                <div class="helper-content">
                    Toutes les questions doivent être répondues.
                </div>
                <div class="helper-content">
                    Si vous répondez négativement à une question, veuillez insérer la raison dans le champ qui apparaitra.
                </div>
                <div class="helper-content">
                    Les réponses sont sauvegardées automatiquent lors de leurs ajouts et/ou modifications.
                </div>
                <div class="helper-content">
                    <div class="help-shortcuts">Raccourcis clavier:</div>
                    <div class="shortcuts">
                        <span><i class="far fa-caret-square-left"></i>Question précédente</span>
                        <span><i class="far fa-caret-square-right"></i>Question suivante</span>
                    </div>
                </div>
                <div class="helper-content">
                    Durée moyenne: 10 à 20 minutes.
                </div>
            </div>
            <button class="button-start" onclick="startEval()">Démarrer</button>
        </div>

    <?php } ?>

<?php } ?>