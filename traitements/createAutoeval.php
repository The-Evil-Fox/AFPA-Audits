<?php 

require_once('../config/dbConnection.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

// Creating a new autoevaluation

if(isset($_POST['createNewEval'])) {

    // Select the last autoevaluation number of the selected user in the database

    $selectLastEval = $db->prepare('SELECT Evaluation_Number FROM Autoevaluations WHERE User_ID = :user AND Completed = true');
    $selectLastEval->bindParam(':user',$_SESSION['ID'], PDO::PARAM_INT);
    $selectLastEval->execute();

    $countResult = $selectLastEval->rowCount();

    // If there is a result: stock the number in a variable and increment it by 1

    if($countResult == 0) {

        $evaluationNumber = 1;

    // Else sets the autoevaluation number to 1

    } else {

        $evaluationNumber = ($countResult + 1);

    }

    $completed = false;

    // Insert the autoevaluation in the database as a autoevaluation in progress

    $insertNewEval = $db->prepare('INSERT INTO Autoevaluations(Evaluation_Number, User_iD, Completed) VALUES(:evalNumber, :user, :completed)');
    $insertNewEval->bindParam(':evalNumber', $evaluationNumber, PDO::PARAM_INT);
    $insertNewEval->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $insertNewEval->bindParam(':completed', $completed, PDO::PARAM_BOOL);
    $insertNewEval->execute();

    $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM QuestionsAutoevaluation WHERE Active = true');
    $result = $countQuestions->fetch();
    $questionsNumber = (int) $result['nb_questions'];

    $selectQuestions = $db->query('SELECT * FROM QuestionsAutoevaluation INNER JOIN CategoriesQuestionsAutoevaluation ON QuestionsAutoevaluation.Category = CategoriesQuestionsAutoevaluation.Category WHERE QuestionsAutoevaluation.Active = true ORDER BY QuestionsAutoevaluation.Category ASC');

    $selectQuestionsID = $db->query('SELECT QuestionsAutoevaluation.ID FROM QuestionsAutoevaluation WHERE QuestionsAutoevaluation.Active = true ORDER BY QuestionsAutoevaluation.ID ASC');
    $questionID = array();
    
    while($question = $selectQuestionsID->fetch()) {

        array_push($questionID, $question['ID']);

    }

    $compteur = 1; ?>

        <form method="POST" id="questionnaire">
            <!-- Form logos -->
            <div class="autoevaluation-logos-container">
                <img class="logo-qualite" src="assets/logoQualiteHautsDeFrance.png" alt="logo qualite hauts de france">
                <img class="logo-afpa" src="assets/logoAFPABlack.png" alt="logo afpa blanc">
            </div>
            <!-- Audit helper content -->
            <div class="helper-container-questions" id="helper">
                <img src="assets/close_button.png" title="Cliquez ici pour fermer l'aide" onclick="showTip()">
                <div class="helper-content">
                    Toutes les questions doivent être répondues.
                </div>
                <div class="helper-content">
                    Si vous répondez négativement à une question, veuillez insérer la raison dans le champ qui apparaitra.
                </div>
                <div class="helper-content">
                    Les réponses sont sauvegardées automatiquement lors de leurs ajouts et/ou modifications.
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
            <?php while($questions = $selectQuestions->fetch()) { ?>
                <!-- Questions -->
                <div class="form-part" id="question<?= $compteur; ?>">
                    <div class="question-number">
                        <?php if($compteur == $questionsNumber) { echo "Question finale"; } else { echo "Question n°$compteur"; } ?><img src="assets/tooltip.png" alt="infobulle" title="cliquez ici pour afficher l'aide" onclick="showTip('helper<?= $compteur; ?>')">
                    </div>
                    <div class="category-question">
                        <?= $questions['Name']; ?>
                    </div>
                    <span class="question-label"><?= $questions['Question']; ?></span>
                    <!-- Answers -->
                    <div class="inputGroup">
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Oui" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Oui">
                            <label for="<?= $questions['ID']; ?>Oui">Oui</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Non" name="<?= $compteur; ?>" value="Non" onchange="showTextArea(<?= $compteur; ?>, true); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);">
                            <label for="<?= $questions['ID']; ?>Non">Non</label>
                        </div>
                        <!-- Textarea for the reasons -->
                        <textarea name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez-vous répondu non ?" cols="60" rows="5" onchange="addReason(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, this.id)"></textarea>
                        <?php if($compteur == $questionsNumber) { ?>
                            <div id="message"></div>
                        <?php } ?>
                        <!-- Buttons -->
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

<?php } ?>