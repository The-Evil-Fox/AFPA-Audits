<?php 

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');
require_once('../config/roles.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['auditer'])) { ?>

    <?php

    $selectAuditInProgress = $db->prepare('SELECT Audit_Number, User_ID FROM Audits WHERE Auditor = :auditor AND Completed = false');
    $selectAuditInProgress->bindParam(':auditor', $_SESSION['ID'], PDO::PARAM_INT);
    $selectAuditInProgress->execute();

    $countResult = $selectAuditInProgress->rowCount();

    if($countResult == 0) {

        $auditNumber = 1;

    } else {

        $auditInProgress = $selectAuditInProgress->fetch();
        $auditNumber = $auditInProgress['Audit_Number'];

    }

    if($countResult == 1) {

        $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM QuestionsAudit WHERE Active = true');
        $result = $countQuestions->fetch();
        $questionsNumber = (int) $result['nb_questions'];

        $selectQuestions = $db->prepare('SELECT qa.ID, qa.Thematique, ca.Constat, cqa.Name 
        FROM QuestionsAudit qa 
        LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.Category 
        LEFT JOIN ConstatsAudits ca ON ca.Thematique = qa.ID  
                                            AND ca.User_ID = :user
                                            AND ca.Audit_Number = :auditNumber
        WHERE qa.Active = true 
        ORDER BY qa.ID;');
        $selectQuestions->bindParam(':user', $auditInProgress['User_ID'], PDO::PARAM_INT);
        $selectQuestions->bindParam(':auditNumber', $auditNumber, PDO::PARAM_INT);
        $selectQuestions->execute();

        $auditNumber = $selectAuditInProgress->fetch();

        $compteur = 1; ?>

        <form method="POST" id="autoEvaluation">
            <div class="autoevaluation-logos-container">
                <img class="logo-qualite" src="assets/logoQualiteHautsDeFrance.png" alt="logo qualite hauts de france">
                <img class="logo-afpa" src="assets/logoAFPAWhite.png" alt="logo afpa blanc">
            </div>
            <?php while($questions = $selectQuestions->fetch()) { ?>
                <div class="form-part" id="question<?= $compteur; ?>">
                    <div class="question-number">
                        <?php if($compteur == $questionsNumber) { echo "Question finale"; } else { echo "Question n°$compteur"; } ?><img src="assets/tooltip.png" alt="infobulle" onmouseover="showTip('helper<?= $compteur; ?>')" onclick="showTip('helper<?= $compteur; ?>')" onmouseout="showTip('helper<?= $compteur; ?>')">
                    </div>
                    <div class="helper-container-questions" id="helper<?= $compteur; ?>">
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
                            Durée moyenne: 10 à 20 minutes.
                        </div>
                    </div>
                    <div class="category-question">
                        <?= $questions['Name']; ?>
                    </div>
                    <label class="question-label" for="question<?= $compteur; ?>"><?= $questions['Thematique']; ?></label>
                    <div class="inputGroup">
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme" <?php if($questions['Constat'] == "Conforme") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NC" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NC" <?php if($questions['Constat'] == "NC") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NC">NC</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NA" <?php if($questions['Constat'] == "NA") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NA">NA</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NDA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NDA" <?php if($questions['Constat'] == "NDA") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NDA">NDA</label>
                        </div>
                        <textarea <?php if($questions[''] == "Non") {?> class="visible" <?php } ?> name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez vous répondu non ?" cols="60" rows="5" onchange="addReason(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, this.id)"><?= $questions['Reason']; ?></textarea>
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
                                <button type="button" class="button-send" id="autoeval-button-send" onclick="sendEval(<?= $auditNumber; ?>)">Finaliser mon auto-évaluation</button>
                                <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $compteur++; ?>
            <?php } ?>
        </form>


    <?php } else {

        $getUsers = $db->query('SELECT ID, Name, FirstName FROM Users WHERE Role = 1');

    ?>

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
                    Durée moyenne: 10 à 20 minutes.
                </div>
            </div>
            <div class="audit-selectuser-buttonstart-container">
                <select id="auditedUser">
                    <option value="">Veuillez selectionner l'utilisateur que vous voulez auditer</option>
                    <?php while($allUsers = $getUsers->fetch()) {?>
                        <option value="<?= $allUsers['ID']; ?>"><?= $allUsers['Name'] . " " . $allUsers['FirstName']; ?></option>
                    <?php } ?>
                </select>
                <button class="button-start" onclick="startAudit()">Démarrer</button>
            </div>
        </div>

    <?php } ?>

<?php }

if(isset($_POST['monespace'])) { 

    $checkEval = $db->prepare('SELECT Evaluation_Number, DateAndHour FROM Autoevaluations WHERE User_ID = :user AND Completed = true ORDER BY DateAndHour DESC');
    $checkEval->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $checkEval->execute();

    $countEvals = $checkEval->rowCount();

    if($countEvals == 0) { ?>

    <div class="card text-center">
        <div class="card-content">Aucun résultats à afficher pour le moment !</div>
    </div>

    <?php } else { ?>

        <?php while($eval = $checkEval->fetch()) { ?>
            <div class="card text-center">
                <div class="card-content">
                    Vous vous êtes autoévalué le <?php echo dateConvert($eval['DateAndHour']); ?>
                    <button class="button-show-autoeval-result" type="button" onclick="showChart('<?php echo dateConvert($eval['DateAndHour']); ?>','<?= $eval['Evaluation_Number']; ?>')">Voir mes résultats</button>
                </div>
            </div>
        <?php } ?>

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

        <form method="POST" id="autoEvaluation">
            <div class="autoevaluation-logos-container">
                <img class="logo-qualite" src="assets/logoQualiteHautsDeFrance.png" alt="logo qualite hauts de france">
                <img class="logo-afpa" src="assets/logoAFPAWhite.png" alt="logo afpa blanc">
            </div>
            <?php while($questions = $selectQuestions->fetch()) { ?>
                <div class="form-part" id="question<?= $compteur; ?>">
                    <div class="question-number">
                        <?php if($compteur == $questionsNumber) { echo "Question finale"; } else { echo "Question n°$compteur"; } ?><img src="assets/tooltip.png" alt="infobulle" onmouseover="showTip('helper<?= $compteur; ?>')" onclick="showTip('helper<?= $compteur; ?>')" onmouseout="showTip('helper<?= $compteur; ?>')">
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
                    <label class="question-label" for="question<?= $compteur; ?>"><?= $questions['Question']; ?></label>
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
                                <button type="button" class="button-send" id="autoeval-button-send" onclick="sendEval(<?= $evaluationNumber; ?>)">Finaliser mon auto-évaluation</button>
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

<?php if(isset($_POST['administration'])) {

    $checkRole = $db->prepare('SELECT Role FROM Users WHERE ID = :user');
    $checkRole->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $checkRole->execute();

    $countResult = $checkRole->rowCount();

    if($countResult != 1) {

        header('Location: ../logout.php');
        exit();

    }

    $userRole = $checkRole->fetch();

    if(isAuditeur($userRole['Role'] || isAuditeur($userinfos['Role']) == false)) { ?>

        <div class="card text-center">
            <div class="card-content">
                Vous n'êtes pas autorisé à accéder à ce contenu !
            </div>
        </div>

    <?php } else { ?>

        <div class="card text-center">
            <div class="card-content">
                <button class="button-show-administration" onclick="showAdministration('actualites')">Fil d'actualité</button>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-content">
                <button class="button-show-administration" onclick="showAdministration('documents')">Consulter et gérer les documents</button>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-content">
                <button class="button-show-administration" onclick="showAdministration('autoEvalution')">Modifier le questionnaire d'auto-évaluation</button>
            </div>
        </div>

    <?php } ?>

<?php } ?>