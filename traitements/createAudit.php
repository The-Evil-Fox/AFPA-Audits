<?php 

require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['getCenters'])) {

    $getCenters = $db->query('SELECT ID, Localisation FROM CentresAFPA'); ?>
    
    <select id="auditedCenter">

        <option value="">Veuillez sélectionner le centre audité</option>

        <?php while($centers = $getCenters->fetch()) { ?>

            <option value="<?= $centers['ID']; ?>"><?= $centers['Localisation']; ?></option>

        <?php } ?>

    </select>

    <?php return;

}

if(isset($_POST['getAuditeurs'])) {

    $getAuditeurs = $db->prepare('SELECT ID, Name, FirstName FROM Users WHERE ID != :user AND Role = 2');
    $getAuditeurs->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $getAuditeurs->execute();

    while($allAuditeurs = $getAuditeurs->fetch()) { $auditeurs[] = $allAuditeurs; } ?>

    <div class="selectAssistant">
        <select id="assistant1">
            <option value="">Selectionnez un premier assistant</option>
            <?php foreach($auditeurs as $row) { ?>
                <option value="<?= $row['ID']; ?>"><?= $row['Name'] . " " . $row['FirstName']; ?></select>
            <?php } ?>
        </select><span class="facultatif">(Facultatif)</span>
    </div>
    <div class="selectAssistant">
        <select id="assistant2">
            <option value="">Selectionnez un deuxième assistant</option>
            <?php foreach($auditeurs as $row) { ?>
                <option value="<?= $row['ID']; ?>"><?= $row['Name'] . " " . $row['FirstName']; ?></select>
            <?php } ?>
        </select><span class="facultatif">(Facultatif)</span>
    </div>
    
<?php }

if(isset($_POST['checkAudit']) && isset($_POST['user']) && !empty($_POST['user'])) {

    $user = (int) $_POST['user'];

    $checkAuditInProgress = $db->prepare('SELECT Completed FROM Audits WHERE User_ID = :user AND Completed = 0 ORDER BY ID DESC LIMIT 1');
    $checkAuditInProgress->bindParam(':user', $user, PDO::PARAM_INT);
    $checkAuditInProgress->execute();
    $countAuditInProgress = $checkAuditInProgress->rowCount();

    if($countAuditInProgress == 1) {

        echo "Un audit est déjà en cours !";
        return;

    } else {

        echo "nope";
        return;

    }

}

if(isset($_POST['continueAudit']) && !empty($_POST['continueAudit']) && isset($_POST['auditedUserID']) && !empty($_POST['auditedUserID'])) {

    $selectAuditInProgress = $db->prepare('SELECT Audit_Number, User_ID FROM Audits WHERE User_ID = :user AND Completed = false');
    $selectAuditInProgress->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
    $selectAuditInProgress->execute();

    $auditInProgress = $selectAuditInProgress->fetch();
    $auditNumber = $auditInProgress['Audit_Number'];

    $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM QuestionsAudit WHERE Active = true');
    $result = $countQuestions->fetch();
    $questionsNumber = (int) $result['nb_questions'];

    $selectQuestions = $db->prepare('SELECT a.Centre, qa.ID, qa.Thematique, qa.Preuves, ca.Constat, ca.Observation, cqa.Name 
    FROM QuestionsAudit qa 
    LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID 
    LEFT JOIN ConstatsAudits ca ON ca.Thematique = qa.ID  
                                        AND ca.User_ID = :user
                                        AND ca.Audit_Number = :auditNumber
    LEFT JOIN Audits a ON a.User_ID = :user
                       AND a.Audit_Number = :auditNumber
    WHERE qa.Active = true 
    ORDER BY qa.ID;');
    $selectQuestions->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
    $selectQuestions->bindParam(':auditNumber', $auditNumber, PDO::PARAM_INT);
    $selectQuestions->execute();

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
                        Si vous répondez "non conforme" à une question, veuillez insérer la raison (observation) dans le champ qui apparaitra.
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
                <div class="category-question">
                    <?= $questions['Name']; ?>
                </div>
                <span class="question-label"><?= $questions['Thematique']; ?></span>
                <div class="preuves-container">
                    <span class="preuves-label">Preuve(s) attendue(s):</span><span><?= $questions['Preuves']; ?></span>
                </div>
                <div class="inputGroup">
                    <div id="radioDiv">
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateConstat(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme" <?php if($questions['Constat'] == "Conforme") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NC" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateConstat(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NC" <?php if($questions['Constat'] == "NC") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NC">NC</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateConstat(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NA" <?php if($questions['Constat'] == "NA") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NA">NA</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NDA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateConstat(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NDA" <?php if($questions['Constat'] == "NDA") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NDA">NDA</label>
                        </div>
                    </div>
                    <textarea <?php if($questions['Constat'] == "NC" || $questions['Constat'] == "NA"|| $questions['Constat'] == "NDA" ) {?> class="visible" <?php } ?> name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez vous répondu négativement ?" cols="60" rows="5" onchange="updateObservation(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>)"><?php if($questions['Observation'] !== null) { echo $questions['Observation']; } ?></textarea>
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
                            <button type="button" class="button-send greenButton" id="questionnaire-button-send" onclick="sendAudit(<?= $auditNumber; ?>, <?= $auditInProgress['User_ID']; ?>)">Finaliser l'audit</button>
                            <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                            <input type="hidden" id="localisation" value="<?= $questions['Centre']; ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php $compteur++; ?>
        <?php } ?>
    </form>

<?php } ?>

<?php

if(isset($_POST['demarrerAudit']) && !empty($_POST['demarrerAudit']) && isset($_POST['auditedUserID']) && !empty($_POST['auditedUserID']) && isset($_POST['auditedCenter']) && !empty($_POST['auditedCenter']) && isset($_POST['assistantAudit1']) && !empty($_POST['assistantAudit1']) && isset($_POST['assistantAudit2']) && !empty($_POST['assistantAudit2'])) {

    $selectLastAudit = $db->prepare('SELECT Audit_Number, User_ID FROM Audits WHERE User_ID = :user ORDER BY Audit_Number DESC LIMIT 1');
    $selectLastAudit->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
    $selectLastAudit->execute();

    $countRowLastAudit = $selectLastAudit->rowCount();

    if($countRowLastAudit !== 0) {

        $lastAudit = $selectLastAudit->fetch();
        $auditNumber = (int) $lastAudit['Audit_Number'] + 1 ;

    } else {

        $auditNumber = 1;

    }

    $completed = false;

    $auditAssistant1 = htmlspecialchars($_POST['assistantAudit1']);
    $auditAssistant2 = htmlspecialchars($_POST['assistantAudit2']);

    $insertNewAudit = $db->prepare('INSERT INTO Audits(Audit_Number, User_iD, Completed, Auditor, Assistant1, Assistant2, Centre) VALUES(:auditNumber, :user, :completed, :auditor, :assistant1, :assistant2, :centre)');
    $insertNewAudit->bindParam(':auditNumber', $auditNumber, PDO::PARAM_INT);
    $insertNewAudit->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
    $insertNewAudit->bindParam(':completed', $completed, PDO::PARAM_BOOL);
    $insertNewAudit->bindParam(':auditor', $_SESSION['ID'], PDO::PARAM_INT);

    if($auditAssistant1 == "false") {

        $insertNewAudit->bindValue(':assistant1', NULL, PDO::PARAM_NULL);

    } else {

        $insertNewAudit->bindParam(':assistant1', $auditAssistant1, PDO::PARAM_INT);

    }

    if($auditAssistant2 == "false") {

        $insertNewAudit->bindValue(':assistant2', NULL, PDO::PARAM_NULL);

    } else {

        $insertNewAudit->bindParam(':assistant2', $auditAssistant2, PDO::PARAM_INT);

    }

    $insertNewAudit->bindParam(':centre', $_POST['auditedCenter'], PDO::PARAM_INT);
    $insertNewAudit->execute();

    $countThematiques = $db->query('SELECT COUNT(*) AS nb_thematiques FROM QuestionsAudit WHERE Active = true');
    $result = $countThematiques->fetch();
    $ThematiquesNumber = (int) $result['nb_thematiques'];

    $selectThematiques = $db->query('SELECT qa.Thematique, qa.ID, qa.Preuves, cqa.Name FROM QuestionsAudit qa LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.ID WHERE qa.Active = true ORDER BY qa.ID ASC');

    $selectThematiquesID = $db->query('SELECT QuestionsAudit.ID FROM QuestionsAudit WHERE QuestionsAudit.Active = true ORDER BY QuestionsAudit.ID ASC');
    $thematiquesID = array();
    
    while($thematiqueID = $selectThematiquesID->fetch()) {

        array_push($thematiquesID, $thematiqueID['ID']);

    }

    $insertResultats = $db->prepare('INSERT INTO ConstatsAudits(Audit_Number, Thematique, User_ID, Auditor) VALUES(:auditNumber, :thematique, :user, :auditor)');
    
    foreach($thematiquesID as $value) {

        $insertResultats->bindParam(':auditNumber', $auditNumber, PDO::PARAM_INT);
        $insertResultats->bindParam(':thematique', $value, PDO::PARAM_INT);
        $insertResultats->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
        $insertResultats->bindParam(':auditor', $_SESSION['ID'], PDO::PARAM_INT);
        $insertResultats->execute();

    }

    $compteur = 1; ?>

    <form method="POST" id="questionnaire">
        <div class="autoevaluation-logos-container">
            <img class="logo-qualite" src="assets/logoQualiteHautsDeFrance.png" alt="logo qualite hauts de france">
            <img class="logo-afpa" src="assets/logoAFPAWhite.png" alt="logo afpa blanc">
        </div>
        <?php while($questions = $selectThematiques->fetch()) { ?>
            <div class="form-part" id="question<?= $compteur; ?>">
                <div class="question-number">
                    <?php if($compteur == $ThematiquesNumber) { echo "Question finale"; } else { echo "Question n°$compteur"; } ?><img src="assets/tooltip.png" alt="infobulle" title="cliquez ici pour afficher l'aide" onclick="showTip('helper<?= $compteur; ?>')">
                </div>
                <div class="helper-container-questions" id="helper<?= $compteur; ?>">
                    <div class="helper-content">
                        Toutes les questions doivent être répondues.
                    </div>
                    <div class="helper-content">
                        Si vous répondez "non conforme" à une question, veuillez insérer la raison (observation) dans le champ qui apparaitra.
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
                <div class="category-question">
                    <?= $questions['Name']; ?>
                </div>
                <span class="question-label"><?= $questions['Thematique']; ?></span>
                <div class="preuves-container">
                    <span class="preuves-label">Preuve(s) attendue(s):</span><span><?= $questions['Preuves']; ?></span>
                </div>
                <div class="inputGroup">
                    <div id="radioDiv">
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateConstat(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme">
                            <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NC" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateConstat(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NC">
                            <label for="<?= $questions['ID']; ?>NC">NC</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateConstat(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NA">
                            <label for="<?= $questions['ID']; ?>NA">NA</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NDA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateConstat(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NDA">
                            <label for="<?= $questions['ID']; ?>NDA">NDA</label>
                        </div>
                    </div>
                    <textarea name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez vous répondu négativement ?" cols="60" rows="5" onchange="updateObservation(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>)"></textarea>
                    <?php if($compteur == $ThematiquesNumber) { ?>
                        <div id="message"></div>
                    <?php } ?>
                    <div class="form-buttons">
                        <?php if($compteur > 1) { ?>
                            <button type="button" class="button-previous" onclick="previousQuestion(<?= $compteur; ?>)"><i class="fas fa-long-arrow-alt-left"></i> Précédent</button>
                        <?php } ?>
                        <?php if($compteur < $ThematiquesNumber) { ?>
                            <button type="button" class="button-next" onclick="nextQuestion(<?= $compteur; ?>)">Suivant <i class="fas fa-long-arrow-alt-right"></i></button>
                        <?php } ?>
                        <?php if($compteur == $ThematiquesNumber) { ?>
                            <button type="button" class="button-send greenButton" id="questionnaire-button-send" onclick="sendAudit(<?= $auditNumber; ?>, <?= $_POST['auditedUserID']; ?>)">Finaliser l'audit</button>
                            <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                            <input type="hidden" id="localisation" value="<?= htmlspecialchars($_POST['auditedCenter']); ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php $compteur++; ?>
        <?php } ?>
    </form>

<?php } ?>