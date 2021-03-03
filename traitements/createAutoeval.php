<?php 

require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    header('Location: index.php');
    exit();

}

if(isset($_POST['createNewEval'])) {

    $selectLastEval = $db->prepare('SELECT Evaluation_Number FROM Autoevaluations WHERE User_ID = :user AND Completed = true');
    $selectLastEval->bindParam(':user',$_SESSION['ID'], PDO::PARAM_INT);
    $selectLastEval->execute();

    $countResult = $selectLastEval->rowCount();

    if($countResult == 0) {

        $evaluationNumber = 1;

    } else {

        $evaluationNumber = ($countResult + 1);

    }

    $completed = false;

    $insertNewEval = $db->prepare('INSERT INTO Autoevaluations(Evaluation_Number, User_iD, Completed) VALUES(:evalNumber, :user, :completed)');
    $insertNewEval->bindParam(':evalNumber', $evaluationNumber, PDO::PARAM_INT);
    $insertNewEval->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $insertNewEval->bindParam(':completed', $completed, PDO::PARAM_BOOL);
    $insertNewEval->execute();

    $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM Questions WHERE Active = true');
    $result = $countQuestions->fetch();
    $questionsNumber = (int) $result['nb_questions'];

    $selectQuestions = $db->query('SELECT * FROM Questions INNER JOIN CategoriesQuestions ON Questions.Category = CategoriesQuestions.Category WHERE Questions.Active = true ORDER BY Questions.ID ASC');

    $selectQuestionsID = $db->query('SELECT Questions.ID FROM Questions INNER JOIN CategoriesQuestions ON Questions.Category = CategoriesQuestions.Category WHERE Questions.Active = true ORDER BY Questions.ID ASC');
    $questionID = array();
    
    while($question = $selectQuestionsID->fetch()) {

        array_push($questionID, $question['ID']);

    }

    $insertResultats = $db->prepare('INSERT INTO ResultatsAutoevaluations(Evaluation_Number, Question, User_ID) VALUES(:evalNumber, :question, :user)');
    
    foreach($questionID as $value) {

        $insertResultats->bindParam(':evalNumber', $evaluationNumber, PDO::PARAM_INT);
        $insertResultats->bindParam(':question', $value, PDO::PARAM_INT);
        $insertResultats->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $insertResultats->execute();

    }

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
                            Durée moyenne: 10 à 20 minutes.
                        </div>
                    </div>
                    <div class="category-question">
                        <?= $questions['Name']; ?>
                    </div>
                    <label for="question<?= $compteur; ?>"><?= $questions['Question']; ?> ?</label>
                    <div class="inputGroup">
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Oui" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Oui">
                            <label for="<?= $questions['ID']; ?>Oui">Oui</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Non" name="<?= $compteur; ?>" value="Non" onchange="showTextArea(<?= $compteur; ?>, true); updateAnswer(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);">
                            <label for="<?= $questions['ID']; ?>Non">Non</label>
                        </div>
                        <textarea name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez-vous répondu non ?" cols="60" rows="5" onchange="addReason(<?= $evaluationNumber; ?>, <?= $questions['ID']; ?>, this.value)"></textarea>
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
                                <button type="button" class="button-send" id="autoeval-button-send" onclick="sendEval(<?= $evaluationNumber; ?>)">Finaliser mon auto-évaluation <i class="fas fa-long-arrow-alt-right"></i></button>
                                <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $compteur++; ?>
            <?php } ?>
        </form>

<?php } ?>