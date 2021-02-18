<?php 

require_once('../config/dbConnection.php');
require_once('../config/dateConvert.php');

if( isset($_POST['dashboard'])) { ?>

    <div class="card">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>

<?php } ?>

<?php if(isset($_POST['monespace'])) { ?>

    <?php

    $checkEval = $db->prepare('SELECT DateAndHour FROM Autoevaluations WHERE User_ID = :user GROUP BY DateAndHour ORDER BY DateAndHour DESC');
    $checkEval->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $checkEval->execute();

    $countEvals = $checkEval->rowCount();

    if($countEvals == 0) { ?>

    <div class="card text-center">
        <p>Vous ne vous êtes pas auto-évalué pour le moment !</p>
    </div>

    <?php } else { ?>

        <?php while($eval = $checkEval->fetch()) { ?>
            <div class="card text-center">
                <p>Vous vous êtes autoévalué le <?php echo dateConvert($eval['DateAndHour']); ?></p>
            </div>
        <?php } ?>

     <?php } ?>

<?php } ?>

<?php if(isset($_POST['autoevaluation'])) { ?>

    <?php

    $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM Questions');
    $result = $countQuestions->fetch();
    $questionsNumber = (int) $result['nb_questions'];

    $selectQuestions = $db->query('SELECT ID,Question FROM Questions');

    $compteur = 1;

    ?>

    <form method="POST" id="autoEvaluation">
        <h3>Auto-évaluation</h3>
        <?php while($questions = $selectQuestions->fetch()) { ?>
            <div class="form-part" id="question<?= $compteur; ?>">
                <label for="question<?= $compteur; ?>">Question n°<?= $compteur; ?>: <?= $questions['Question']; ?> ?</label>
                <div class="inputGroup">
                    <div class="checkbox">
                        <input type="checkbox" name="questions" value="Oui"><span class="answer">Oui</span>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" name="questions" value="Non"><span class="answer">Non</span>
                    </div>
                    <input type="hidden" value="<?= $questions['ID']; ?>" name="questionNumber[]" readonly required>
                    <div class="form-buttons">
                        <?php if($compteur > 1) { ?>
                            <button type="button" class="button-previous" onclick="autoEvalPrevious(<?= $compteur; ?>)"><i class="fas fa-long-arrow-alt-left"></i> Question précédente</button>
                        <?php } ?>
                        <?php if($compteur < $questionsNumber) { ?>
                            <button type="button" class="button-next" onclick="autoEvalNext(<?= $compteur; ?>)">Question suivante <i class="fas fa-long-arrow-alt-right"></i></button>
                        <?php } ?>
                        <?php if($compteur == $questionsNumber) { ?>
                        <button type="button" class="button-send" onclick="sendAutoEval()">Envoyer mon auto-évaluation</button>
                        <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php $compteur++; ?>
        <?php } ?>
        <div id="message"></div>
    </form>

<?php } ?>

<?php if(isset($_POST['administration'])) { ?>

    <div class="card">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>

<?php } ?>