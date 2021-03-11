<?php 

require_once('../config/dbConnection.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['createNewAudit']) && !empty($_POST['createNewAudit']) && isset($_POST['auditedUserID']) && !empty($_POST['auditedUserID'])) {

    $selectAuditInProgress = $db->prepare('SELECT Audit_Number, User_ID FROM Audits WHERE User_ID = :user AND Completed = false');
    $selectAuditInProgress->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
    $selectAuditInProgress->execute();

    $countResult = $selectAuditInProgress->rowCount();

    if($countResult == 0) {

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

    } else {

        $auditInProgress = $selectAuditInProgress->fetch();
        $auditNumber = $auditInProgress['Audit_Number'];

    }

    if($countResult == 1) {

        $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM QuestionsAudit WHERE Active = true');
        $result = $countQuestions->fetch();
        $questionsNumber = (int) $result['nb_questions'];

        $selectQuestions = $db->prepare('SELECT qa.ID, qa.Thematique, qa.Preuves, ca.Constat, ca.Observation, cqa.Name 
        FROM QuestionsAudit qa 
        LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.Category 
        LEFT JOIN ConstatsAudits ca ON ca.Thematique = qa.ID  
                                            AND ca.User_ID = :user
                                            AND ca.Audit_Number = :auditNumber
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
                    <span class="question-label"><?= $questions['Thematique']; ?></span>
                    <div class="preuves-container">
                        <span class="preuves-label">Preuve(s) attendue(s):</span><span><?= $questions['Preuves']; ?></span>
                    </div>
                    <div class="inputGroup">
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateConstat(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme" <?php if($questions['Constat'] == "Conforme") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NC" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateConstat(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NC" <?php if($questions['Constat'] == "NC") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NC">NC</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateConstat(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NA" <?php if($questions['Constat'] == "NA") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NA">NA</label>
                        </div>
                        <div class="radiobox">
                            <input type="radio" id="<?= $questions['ID']; ?>NDA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateConstat(<?= $auditInProgress['User_ID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NDA" <?php if($questions['Constat'] == "NDA") {?> checked <?php } ?>>
                            <label for="<?= $questions['ID']; ?>NDA">NDA</label>
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
                                <button type="button" class="button-send" id="questionnaire-button-send" onclick="sendAudit(<?= $auditNumber; ?>, <?= $auditInProgress['User_ID']; ?>)">Finaliser l'audit</button>
                                <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $compteur++; ?>
            <?php } ?>
        </form>

    <?php } elseif($countResult == 0) {

        $completed = false;

        $insertNewAudit = $db->prepare('INSERT INTO Audits(Audit_Number, User_iD, Completed, Auditor) VALUES(:auditNumber, :user, :completed, :auditor)');
        $insertNewAudit->bindParam(':auditNumber', $auditNumber, PDO::PARAM_INT);
        $insertNewAudit->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
        $insertNewAudit->bindParam(':completed', $completed, PDO::PARAM_BOOL);
        $insertNewAudit->bindParam(':auditor', $_SESSION['ID'], PDO::PARAM_INT);
        $insertNewAudit->execute();

        $countThematiques = $db->query('SELECT COUNT(*) AS nb_thematiques FROM QuestionsAudit WHERE Active = true');
        $result = $countThematiques->fetch();
        $ThematiquesNumber = (int) $result['nb_thematiques'];

        $selectThematiques = $db->query('SELECT qa.Thematique, qa.ID, qa.Preuves, cqa.Name FROM QuestionsAudit qa LEFT JOIN CategoriesQuestionsAudit cqa ON qa.Category = cqa.Category WHERE qa.Active = true ORDER BY qa.ID ASC');

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
                        <span class="question-label"><?= $questions['Thematique']; ?></span>
                        <div class="preuves-container">
                            <span class="preuves-label">Preuve(s) attendue(s):</span><span><?= $questions['Preuves']; ?></span>
                        </div>
                        <div class="inputGroup">
                            <div class="radiobox">
                                <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateConstat(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme">
                                <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                            </div>
                            <div class="radiobox">
                                <input type="radio" id="<?= $questions['ID']; ?>NC" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateConstat(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NC">
                                <label for="<?= $questions['ID']; ?>NC">NC</label>
                            </div>
                            <div class="radiobox">
                                <input type="radio" id="<?= $questions['ID']; ?>NA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateConstat(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NA">
                                <label for="<?= $questions['ID']; ?>NA">NA</label>
                            </div>
                            <div class="radiobox">
                                <input type="radio" id="<?= $questions['ID']; ?>NDA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateConstat(<?= $_POST['auditedUserID']; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NDA">
                                <label for="<?= $questions['ID']; ?>NDA">NDA</label>
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
                                    <button type="button" class="button-send" id="questionnaire-button-send" onclick="sendAudit(<?= $auditNumber; ?>, <?= $_POST['auditedUserID']; ?>)">Finaliser l'audit</button>
                                    <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php $compteur++; ?>
                <?php } ?>
            </form>

        <?php } 

    }

?>