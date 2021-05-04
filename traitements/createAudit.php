<?php 

require_once('../config/dbConnection.php');
require_once('../config/reqUser.php');
require_once('../config/roles.php');
require_once('../config/dateConvert.php');

// Returns a non-authorised access message to ajax, which will head the user to the index(login) page if that happens.

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé !";
    return;

}

// Checks if the user is authorised to access this, if not disconnect the user

if(isAdmin($userInfos['Role']) || isAuditeur($userInfos['Role'])) {

    // If ajax ask to get the localisations:

    if(isset($_POST['getCenters'])) {

        // Select all the localisation available in the database and returns them in a selector

        $getCenters = $db->query('SELECT ID, Localisation FROM Facilities'); ?>
        
        <select id="auditedCenter">

            <option value="">Veuillez sélectionner le centre audité</option>

            <?php while($centers = $getCenters->fetch()) { ?>

                <option value="<?= $centers['ID']; ?>"><?= $centers['Localisation']; ?></option>

            <?php } ?>

        </select>

        <?php return;

    }

    // If ajax ask to get the auditors

    if(isset($_POST['getAuditeurs1'])) {

        // Select all the auditors who are not the actual user and return them in a selector

        $getAuditeurs = $db->prepare('SELECT ID, Name, FirstName FROM Users WHERE ID != :user AND Role = 2');
        $getAuditeurs->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $getAuditeurs->execute(); ?>

        <div class="selectAssistant">
            <select id="assistant1" onchange="showSecondAssistantSelect(this.value)">
                <option value="">Selectionnez un assistant</option>
                <?php while($allAuditeurs = $getAuditeurs->fetch()) { ?>
                    <option value="<?= $allAuditeurs['ID']; ?>"><?= $allAuditeurs['Name'] . " " . $allAuditeurs['FirstName']; ?></option>
                <?php } ?>
            </select><span class="facultatif">(Facultatif)</span>
        </div>
        <div id="assistant2Container" class="selectAssistant none"></div>
        
    <?php }

    // If ajax ask to get a second assistant list

    if(isset($_POST['assistant1']) && !empty($_POST['assistant1']) && isset($_POST['getAuditeurs2'])) {

        /*
        Get the list of all the auditors who are not the actual user and not 
        the first assistant selected by him. Then returns the selector
        */

        $getAuditeurs2 = $db->prepare('SELECT ID, Name, FirstName FROM Users WHERE ID != :user AND ID != :assistant AND Role = 2');
        $getAuditeurs2->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
        $getAuditeurs2->bindParam(':assistant', $_POST['assistant1'], PDO::PARAM_INT);
        $getAuditeurs2->execute(); ?>

        <select id="assistant2">
            <option value="">Selectionnez un deuxième assistant</option>
                <?php while($allAuditeurs2 = $getAuditeurs2->fetch()) { ?>
                    <option value="<?= $allAuditeurs2['ID']; ?>"><?= $allAuditeurs2['Name'] . " " . $allAuditeurs2['FirstName']; ?></option>
                <?php } ?>
        </select><span class="facultatif">(Facultatif)</span>
        
    <?php }

    if(isset($_POST['getAuditsType'])) {

        $getAuditsTypes = $db->query('SELECT * FROM TypesAudits');?>

        <select id="auditType">
            <option value="">Veuillez selectionner un type d'audit</option>
            <?php while($auditsTypes = $getAuditsTypes->fetch()) {?>
                <option value="<?= $auditsTypes['ID']; ?>"><?= $auditsTypes['Name']; ?></option>
            <?php } ?>
        </select>

        

    <?php }

    // If ajax ask to check if a audit is in progress:

    if(isset($_POST['checkAudit']) && isset($_POST['user']) && !empty($_POST['user'])) {

        $user = (int) $_POST['user'];

        // Select the last completed audit of the user inserted in the database

        $checkAuditInProgress = $db->prepare('SELECT Completed FROM Audits WHERE User_ID = :user AND Completed = 0 ORDER BY ID DESC LIMIT 1');
        $checkAuditInProgress->bindParam(':user', $user, PDO::PARAM_INT);
        $checkAuditInProgress->execute();
        $countAuditInProgress = $checkAuditInProgress->rowCount();

        /*
        If there is a result then a audit is in progress actually: 
        returns a message to ajax so he know what to show to the user
        */

        if($countAuditInProgress == 1) {

            echo "Un audit est déjà en cours !";
            return;

        }

    }

    // Resuming a audit:

    if(isset($_POST['continueAudit']) && !empty($_POST['continueAudit']) && isset($_POST['auditedUserID']) && !empty($_POST['auditedUserID'])) {

        // Select the audit number of the selected user in the database

        $selectAuditInProgress = $db->prepare('SELECT Type, Audit_Number, User_ID FROM Audits WHERE User_ID = :user AND Completed = false');
        $selectAuditInProgress->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
        $selectAuditInProgress->execute();

        $auditInProgress = $selectAuditInProgress->fetch();
        $auditNumber = $auditInProgress['Audit_Number'];
        $auditType = $auditInProgress['Type'];

        if($auditType == 1) {

            // Count the number of questions active in the database

            $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM QuestionsAuditFormateur WHERE Active = true');
            $result = $countQuestions->fetch();
            $questionsNumber = (int) $result['nb_questions'];

            // Select the questions and the answers already submitted
            
            $selectQuestions = $db->prepare('SELECT a.Facility, a.Auditor, a.Assistant1, a.Assistant2, qaf.ID, qaf.Question, qaf.Evidence, ar.Report, ar.Observation, cqaf.Name 
            FROM QuestionsAuditFormateur qaf
            LEFT JOIN CategoriesQuestionsAuditFormateur cqaf ON qaf.Category = cqaf.ID 
            LEFT JOIN AuditsReports ar ON ar.Question = qaf.ID  
                AND ar.User_ID = :user
                AND ar.Audit_Number = :auditNumber
            LEFT JOIN Audits a ON a.User_ID = :user
                AND a.Audit_Number = :auditNumber
            WHERE qaf.Active = true 
            ORDER BY cqaf.ID, qaf.ID');
            $selectQuestions->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
            $selectQuestions->bindParam(':auditNumber', $auditNumber, PDO::PARAM_INT);
            $selectQuestions->execute();

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
                        Si vous répondez "non conforme" à une question, veuillez insérer la raison (observation) dans le champ qui apparaitra.
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
                        <div class="help-acronyms">Signification des sigles:</div>
                        <div class="acronyms">
                            <span>NC: non conforme</span>
                            <span>NA: non applicable</span>
                            <span>NDA: non disponible actuellement</span>
                        </div>
                    </div>
                    <div class="helper-content">
                        Durée moyenne: 1 heure minimum.
                    </div>
                </div>
                <?php while($questions = $selectQuestions->fetch()) { ?>
                    <!-- Questions -->
                    <div class="form-part" id="question<?= $compteur; ?>">
                        <button class="buttonHelp" type="button" onclick="showTip()"><i class="fas fa-info-circle"></i> Afficher l'aide</button>
                        <div class="question-number">
                            <?php if($compteur == $questionsNumber) { echo "Question finale"; } else { echo "Question n°$compteur"; } ?>
                        </div>
                        <div class="category-question">
                            <?= $questions['Name']; ?>
                        </div>
                        <span class="question-label"><?= $questions['Question']; ?></span>
                        <?php if($questions['Evidence'] !== NULL) { ?>
                            <div class="preuves-container">
                                <span class="preuves-label">Preuve(s) attendue(s):</span><span><?= $questions['Evidence']; ?></span>
                            </div>
                        <?php } ?>
                        <!-- Answers -->
                        <div class="inputGroup">
                            <div id="radioDiv">
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateReport(<?= $auditInProgress['User_ID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme" <?php if($questions['Report'] == "Conforme") {?> checked <?php } ?>>
                                    <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NC" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateReport(<?= $auditInProgress['User_ID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NC" <?php if($questions['Report'] == "NC") {?> checked <?php } ?>>
                                    <label for="<?= $questions['ID']; ?>NC">NC</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateReport(<?= $auditInProgress['User_ID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NA" <?php if($questions['Report'] == "NA") {?> checked <?php } ?>>
                                    <label for="<?= $questions['ID']; ?>NA">NA</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NDA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateReport(<?= $auditInProgress['User_ID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NDA" <?php if($questions['Report'] == "NDA") {?> checked <?php } ?>>
                                    <label for="<?= $questions['ID']; ?>NDA">NDA</label>
                                </div>
                            </div>
                            <!-- Textarea for the observation -->
                            <textarea <?php if($questions['Report'] == "NC") {?> class="visible" <?php } ?> name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez vous répondu négativement ?" cols="60" rows="5" onchange="updateObservation(<?= $auditInProgress['User_ID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>)"><?php if($questions['Observation'] !== null) { echo $questions['Observation']; } ?></textarea>
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
                                    <button type="button" class="button-send greenButton" id="questionnaire-button-send" onclick="sendAudit(<?= $auditInProgress['Type']; ?>, <?= $auditNumber; ?>, <?= $auditInProgress['User_ID']; ?>, <?= $questions['Facility']; ?>, <?= $questions['Auditor']; ?><?php if($questions['Assistant1'] != null) {?><?= ', ' . $questions['Assistant1']; ?><?php } ?><?php if($questions['Assistant2'] != null) {?><?= ', ' . $questions['Assistant2']; ?><?php } ?>)">Finaliser l'audit</button>
                                    <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php $compteur++;
                } ?>
            </form>
            <div class="paginationContainer" id="summaryContainer">
                <?php $i = 1; while($i < $questionsNumber +1 ) { ?>
                    <button id="paginationButton<?= $i;?>" value="<?= $i; ?>" class="paginationButton <?php if($i == 1) { ?> paginationActive <?php } ?>" type="button" onclick="goToQuestion(this.value)"><?= $i; ?></button>
                    <?php $i++; ?>
                <?php } ?>
            </div>
        <?php } else if($auditType == 2) {

            $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM IndicatorsQualiopi');
            $result = $countQuestions->fetch();
            $questionsNumber = (int) $result['nb_questions'];

            $selectQuestions = $db->prepare('SELECT a.Facility, a.Auditor, a.Assistant1, a.Assistant2, cq.Name, iq.ID, iq.Indicator, ar.Report, ar.Observation, iq.Evidences
            FROM IndicatorsQualiopi iq
            LEFT JOIN CriteriaQualiopi cq ON iq.Criteria = cq.ID 
            LEFT JOIN AuditsReports ar ON ar.Question = iq.ID  
                AND ar.User_ID = :user
                AND ar.Audit_Number = :auditNumber
            LEFT JOIN Audits a ON a.User_ID = :user
                AND a.Audit_Number = :auditNumber
            WHERE iq.Active = true 
            ORDER BY iq.Criteria, iq.ID');
            $selectQuestions->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
            $selectQuestions->bindParam(':auditNumber', $auditNumber, PDO::PARAM_INT);
            $selectQuestions->execute();

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
                        Les observations et remarques doivent obligatoirement être renseigné si la question a été répondu non conforme mineure ou majeure. Et sont optionnelles en cas de conformité à la question.
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
                        <div class="help-acronyms">Signification des sigles:</div>
                        <div class="acronyms">
                            <span>NCm: non conformité mineure</span>
                            <span>NCM: non conformité majeure</span>
                        </div>
                    </div>
                    <div class="helper-content">
                        Durée moyenne: 1 heure minimum.
                    </div>
                </div>
                <?php while($questions = $selectQuestions->fetch()) { ?>
                    <!-- Questions -->
                    <div class="form-part" id="question<?= $compteur; ?>">
                        <button class="buttonHelp" type="button" onclick="showTip()"><i class="fas fa-info-circle"></i> Afficher l'aide</button>
                        <div class="question-number">
                            <h3>Critère: <?= $questions['Name']; ?></h3>
                        </div>
                        <div class="category-question">
                            <?= $questions['Indicator']; ?>
                        </div>
                        <div class="preuves-container-qualiopi">
                           <div class="preuves-label-qualiopi">Preuve(s) attendue(s):</div>
                           <div class="preuves-qualiopi"><?= str_replace(".", "<br>", $questions['Evidences']); ?></div>
                        </div>
                        <!-- Textarea for the observations -->
                        <div class="inputGroup">
                            <div id="radioDiv">
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme" <?php if($questions['Report'] == "Conforme") { ?> checked <?php } ?>>
                                    <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NCm" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NCmineure" <?php if($questions['Report'] == "NCmineure") { ?> checked <?php } ?>>
                                    <label for="<?= $questions['ID']; ?>NCm">NCm</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NCM" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NCmajeure" <?php if($questions['Report'] == "NCmajeure") { ?> checked <?php } ?>>
                                    <label for="<?= $questions['ID']; ?>NCM">NCM</label>
                                </div>
                            </div>
                            <textarea class="visible" name="textAreas" id="<?= $compteur; ?>" placeholder="Insérer les remarques et observations ici." cols="60" rows="5" onchange="updateObservation(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>)"><?php if($questions['Observation'] !== null) { echo $questions['Observation']; } ?></textarea>
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
                                    <button type="button" class="button-send greenButton" id="questionnaire-button-send" onclick="sendAudit(<?= $auditInProgress['Type']; ?>, <?= $auditNumber; ?>, <?= $auditInProgress['User_ID']; ?>, <?= $questions['Facility']; ?>, <?= $questions['Auditor']; ?><?php if($questions['Assistant1'] != null) {?><?= ', ' . $questions['Assistant1']; ?><?php } ?><?php if($questions['Assistant2'] != null) {?><?= ', ' . $questions['Assistant2']; ?><?php } ?>)">Finaliser l'audit</button>
                                    <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                                <?php } ?>
                            </div>
                        </div>
                        <?php $compteur++;  ?>
                    </div>
                <?php } ?>
            </form>
            <div class="paginationContainer" id="summaryContainer">
                <?php $i = 1; while($i < $questionsNumber +1 ) { ?>
                    <button id="paginationButton<?= $i;?>" value="<?= $i; ?>" class="paginationButton <?php if($i == 1) { ?> paginationActive <?php } ?>" type="button" onclick="goToQuestion(this.value)"><?= $i; ?></button>
                    <?php $i++; ?>
                <?php } ?>
            </div>
        <?php }

    }

    // Creating a audit

    if(isset($_POST['demarrerAudit']) && !empty($_POST['demarrerAudit']) && isset($_POST['auditType']) && !empty($_POST['auditType']) && isset($_POST['auditedUserID']) && !empty($_POST['auditedUserID']) && isset($_POST['auditedCenter']) && !empty($_POST['auditedCenter']) && isset($_POST['assistantAudit1']) && !empty($_POST['assistantAudit1']) && isset($_POST['assistantAudit2']) && !empty($_POST['assistantAudit2'])) {

        // Select the last audit number of the selected user in the database

        $selectLastAudit = $db->prepare('SELECT Audit_Number, User_ID FROM Audits WHERE User_ID = :user ORDER BY Audit_Number DESC LIMIT 1');
        $selectLastAudit->bindParam(':user', $_POST['auditedUserID'], PDO::PARAM_INT);
        $selectLastAudit->execute();

        $countRowLastAudit = $selectLastAudit->rowCount();

        // If there is a result: stock the number in a variable and increment it by 1

        if($countRowLastAudit !== 0) {

            $lastAudit = $selectLastAudit->fetch();
            $auditNumber = (int) $lastAudit['Audit_Number'] + 1 ;
        
        // Else sets the audit number to 1

        } else {

            $auditNumber = 1;

        }

        $completed = false;

        $auditAssistant1 = htmlspecialchars($_POST['assistantAudit1']);
        $auditAssistant2 = htmlspecialchars($_POST['assistantAudit2']);
        $auditType = htmlspecialchars($_POST['auditType']);

        // Insert the audit in the database as a audit in progress

        $insertNewAudit = $db->prepare('INSERT INTO Audits(Audit_Number, User_iD, Completed, Auditor, Assistant1, Assistant2, Facility, Type) VALUES(:auditNumber, :user, :completed, :auditor, :assistant1, :assistant2, :facility, :type)');
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

        $insertNewAudit->bindParam(':facility', $_POST['auditedCenter'], PDO::PARAM_INT);
        $insertNewAudit->bindParam(':type', $auditType, PDO::PARAM_INT);
        $insertNewAudit->execute();

        // If the audit created is a type 1

        if($auditType == 1) {

            // Count the total number of questions of the audit type 1

            $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM QuestionsAuditFormateur WHERE Active = true');
            $result = $countQuestions->fetch();
            $questionsNumber = (int) $result['nb_questions'];

            // Get the questions for the audit type 1

            $selectQuestions = $db->query('SELECT qaf.Question, qaf.ID, qaf.Evidence, cqa.Name FROM QuestionsAuditFormateur qaf
            LEFT JOIN CategoriesQuestionsAuditFormateur cqa ON qaf.Category = cqa.ID WHERE qaf.Active = true ORDER BY cqa.ID, qaf.ID');

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
                        Si vous répondez "non conforme" à une question, veuillez insérer la raison (observation) dans le champ qui apparaitra.
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
                        <div class="help-acronyms">Signification des sigles:</div>
                        <div class="acronyms">
                            <span>NC: non conforme</span>
                            <span>NA: non applicable</span>
                            <span>NDA: non disponible actuellement</span>
                        </div>
                    </div>
                    <div class="helper-content">
                        Durée moyenne: 1 heure minimum.
                    </div>
                </div>
                <?php while($questions = $selectQuestions->fetch()) { ?>
                    <!-- Questions -->
                    <div class="form-part" id="question<?= $compteur; ?>">
                        <button class="buttonHelp" type="button" onclick="showTip()"><i class="fas fa-info-circle"></i> Afficher l'aide</button>
                        <div class="question-number">
                            <?php if($compteur == $questionsNumber) { echo "Question finale"; } else { echo "Question n°$compteur"; } ?>
                        </div>
                        <div class="category-question">
                            <?= $questions['Name']; ?>
                        </div>
                        <span class="question-label"><?= $questions['Question']; ?></span>
                        <?php if($questions['Evidence'] !== NULL) { ?>
                            <div class="preuves-container">
                                <span class="preuves-label">Preuve(s) attendue(s):</span><span><?= $questions['Evidence']; ?></span>
                            </div>
                        <?php } ?>
                        <!-- Answers -->
                        <div class="inputGroup">
                            <div id="radioDiv">
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme">
                                    <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NC" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NC">
                                    <label for="<?= $questions['ID']; ?>NC">NC</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NA">
                                    <label for="<?= $questions['ID']; ?>NA">NA</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NDA" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, false); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NDA">
                                    <label for="<?= $questions['ID']; ?>NDA">NDA</label>
                                </div>
                            </div>
                            <!-- Textarea for the observations -->
                            <textarea name="textAreas" id="<?= $compteur; ?>" placeholder="Pourquoi avez vous répondu négativement ?" cols="60" rows="5" onchange="updateObservation(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>)"></textarea>
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
                                    <button type="button" class="button-send greenButton" id="questionnaire-button-send" onclick="sendAudit(<?= $auditType; ?>, <?= $auditNumber; ?>, <?= $_POST['auditedUserID']; ?>, <?= $_POST['auditedCenter']; ?>, <?= $_SESSION['ID'] ?> <?php if($auditAssistant1 !== 'false') {?> <?= ', ' . $auditAssistant1; ?> <?php } ?> <?php if($auditAssistant2 !== 'false') {?> <?= ', ' . $auditAssistant2; ?> <?php } ?>)">Finaliser l'audit</button>
                                    <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php $compteur++; ?>
                <?php } ?>
            </form>
            <div class="paginationContainer" id="summaryContainer">
                <?php $i = 1; while($i < $questionsNumber +1 ) { ?>
                    <button id="paginationButton<?= $i;?>" value="<?= $i; ?>" class="paginationButton <?php if($i == 1) { ?> paginationActive <?php } ?>" type="button" onclick="goToQuestion(this.value)"><?= $i; ?></button>
                    <?php $i++; ?>
                <?php } ?>
            </div>
        <?php } else if($auditType == 2) {

            $countQuestions = $db->query('SELECT COUNT(*) AS nb_questions FROM IndicatorsQualiopi');
            $result = $countQuestions->fetch();
            $questionsNumber = (int) $result['nb_questions'];

            $selectQuestions = $db->query('SELECT cq.Name, iq.ID, iq.Indicator, iq.Evidences FROM IndicatorsQualiopi iq
            LEFT JOIN CriteriaQualiopi cq ON iq.Criteria = cq.ID 
            WHERE iq.Active = true 
            ORDER BY iq.Criteria, iq.ID');

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
                        Les observations et remarques doivent obligatoirement être renseigné si la question a été répondu non conforme mineure ou majeure. Et sont optionnelles en cas de conformité à la question.
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
                        <div class="help-acronyms">Signification des sigles:</div>
                        <div class="acronyms">
                            <span>NCm: non conformité mineure</span>
                            <span>NCM: non conformité majeure</span>
                        </div>
                    </div>
                    <div class="helper-content">
                        Durée moyenne: 1 heure minimum.
                    </div>
                </div>
                <?php while($questions = $selectQuestions->fetch()) { ?>
                    <!-- Questions -->
                    <div class="form-part" id="question<?= $compteur; ?>">
                        <button class="buttonHelp" type="button" onclick="showTip()"><i class="fas fa-info-circle"></i> Afficher l'aide</button>
                        <div class="question-number">
                            <h3>Critère: <?= $questions['Name']; ?></h3>
                        </div>
                        <div class="category-question">
                            <?= $questions['Indicator']; ?>
                        </div>
                        <div class="preuves-container-qualiopi">
                           <div class="preuves-label-qualiopi">Preuve(s) attendue(s):</div>
                           <div class="preuves-qualiopi"><?= str_replace(".", "<br>", $questions['Evidences']); ?></div>
                        </div>
                        <!-- Textarea for the observations -->
                        <div class="inputGroup">
                            <div id="radioDiv">
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>Conforme" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="Conforme">
                                    <label for="<?= $questions['ID']; ?>Conforme">Conforme</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NCm" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NCmineure">
                                    <label for="<?= $questions['ID']; ?>NCm">NCm</label>
                                </div>
                                <div class="radiobox">
                                    <input type="radio" id="<?= $questions['ID']; ?>NCM" name="<?= $compteur; ?>" onchange="showTextArea(<?= $compteur; ?>, true); updateReport(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>);" value="NCmajeure">
                                    <label for="<?= $questions['ID']; ?>NCM">NCM</label>
                                </div>
                            </div>
                            <textarea class="visible" name="textAreas" id="<?= $compteur; ?>" placeholder="Insérer les remarques et observations ici." cols="60" rows="5" onchange="updateObservation(<?= $_POST['auditedUserID']; ?>, <?= $auditType; ?>, <?= $auditNumber; ?>, <?= $questions['ID']; ?>, this.value, <?= $compteur; ?>)"></textarea>
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
                                    <button type="button" class="button-send greenButton" id="questionnaire-button-send" onclick="sendAudit(<?= $auditType; ?>, <?= $auditNumber; ?>, <?= $_POST['auditedUserID']; ?>, <?= $_POST['auditedCenter']; ?>, <?= $_SESSION['ID'] ?> <?php if($auditAssistant1 !== 'false') {?> <?= ', ' . $auditAssistant1; ?> <?php } ?> <?php if($auditAssistant2 !== 'false') {?> <?= ', ' . $auditAssistant2; ?> <?php } ?>)">Finaliser l'audit</button>
                                    <input type="hidden" id="questionsNumber" value="<?= $compteur; ?>">
                                <?php } ?>
                            </div>
                        </div>
                        <?php $compteur++;  ?>
                    </div>
                <?php } ?>
            </form>
            <div class="paginationContainer" id="summaryContainer">
                <?php $i = 1; while($i < $questionsNumber +1 ) { ?>
                    <button id="paginationButton<?= $i;?>" value="<?= $i; ?>" class="paginationButton <?php if($i == 1) { ?> paginationActive <?php } ?>" type="button" onclick="goToQuestion(this.value)"><?= $i; ?></button>
                    <?php $i++; ?>
                <?php } ?>
            </div>
        <?php }

    }

} else {

    echo "Acces refusé !";
    return;

} ?>