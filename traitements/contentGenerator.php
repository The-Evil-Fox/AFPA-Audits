<?php 

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');
require_once('../config/roles.php');

if(isset($_POST['monespace'])) { 

    $checkEval = $db->prepare('SELECT Evaluation_Number, DateAndHour FROM Autoevaluations WHERE User_ID = :user GROUP BY DateAndHour, Evaluation_Number ORDER BY DateAndHour DESC');
    $checkEval->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $checkEval->execute();

    $countEvals = $checkEval->rowCount();

    if($countEvals == 0) { ?>

    <div class="card text-center">
    <div class="card-content">Vous ne vous êtes pas auto-évalué pour le moment !</div>
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

    $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM Questions WHERE Active = true');
    $result = $countQuestions->fetch();
    $questionsNumber = (int) $result['nb_questions'];

    $selectQuestions = $db->query('SELECT * FROM Questions INNER JOIN CategoriesQuestions ON Questions.Category = CategoriesQuestions.Category WHERE Questions.Active = true ORDER BY Questions.ID ASC');

    $compteur = 1;

    ?>

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
                Toutes les questions doivent être réponduent.
            </div>
            <div class="helper-content">
                Si vous répondez négativement à une question, veuillez insérer la raison dans le champ qui apparaitra.
            </div>
            <div class="helper-content">
                Durée moyenne: 10 à 20 minutes.
            </div>
        </div>
        <button class="button-start" onclick="startEval()">Démarrer</button>
    </div>
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
                        Toutes les questions doivent être réponduent.
                    </div>
                    <div class="helper-content">
                        Si vous répondez négativement à une question, veuillez insérer la raison dans le champ qui apparaitra.
                    </div>
                    <div class="helper-content">
                        Durée moyenne: 10 à 20 minutes.
                    </div>
                </div>
                <div class="category-question">
                    <?= $questions['Name']; ?>
                </div>
                <label for="question<?= $compteur; ?>"><?= $questions['Question']; ?> ?</label>
                <div class="inputGroup">
                    <div class="checkbox">
                        <input type="checkbox" name="questions" value="Oui"><span class="answer">Oui</span>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" onclick="showTextArea('<?= $compteur; ?>')" name="questions" value="Non"><span class="answer">Non</span>
                    </div>
                    <textarea name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez vous répondu non ?" cols="60" rows="5"></textarea>
                    <input type="hidden" value="<?= $questions['ID']; ?>" name="questionNumber[]" readonly required>
                    <?php if($compteur == $questionsNumber) { ?>
                        <div id="message"></div>
                    <?php } ?>
                    <div class="form-buttons">
                        <?php if($compteur > 1) { ?>
                            <button type="button" class="button-previous" onclick="autoEvalPrevious(<?= $compteur; ?>)"><i class="fas fa-long-arrow-alt-left"></i> Précédent</button>
                        <?php } ?>
                        <?php if($compteur < $questionsNumber) { ?>
                            <button type="button" class="button-next" onclick="autoEvalNext(<?= $compteur; ?>)">Suivant <i class="fas fa-long-arrow-alt-right"></i></button>
                        <?php } ?>
                        <?php if($compteur == $questionsNumber) { ?>
                            <button type="button" class="button-send" id="autoeval-button-send" onclick="sendAutoEval()">Envoyer mon auto-évaluation <i class="fas fa-long-arrow-alt-right"></i></button>
                            <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php $compteur++; ?>
        <?php } ?>
    </form>
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
                <button class="button-show-administration" onclick="showAdministration('autoEvalution')">Modifier le questionnaire d'auto-évaluation</button>
            </div>
        </div>

    <?php } ?>

<?php } ?>