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

    if(isset($_POST['auditType']) && !empty($_POST['auditType'])) {

    // Modification of the audit type 1

        if($_POST['auditType'] == 1) {

            // If a question is edited:

            if(isset($_POST['operation']) && !empty($_POST['operation'])) {

                if(isset($_POST['questionID']) && !empty($_POST['questionID'])) {

                    $question = intval($_POST['questionID']);

                    // If a question is deleted

                    if($_POST['operation'] == "delete") {

                        // Delete the question from the database and the auditsreports associated to it

                        $deleteQuestion = $db->prepare('DELETE FROM QuestionsAuditFormateur WHERE ID = :questionID');
                        $deleteQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $deleteQuestion->execute();

                        $deleteConstatsAudits = $db->prepare('DELETE FROM AuditsReports WHERE Question = :questionID AND Audit_Type = :auditType');
                        $deleteConstatsAudits->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $deleteConstatsAudits->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
                        $deleteConstatsAudits->execute();

                        $message = "La question a bien été supprimée !";

                    // If a question is disabled
                    
                    } else if($_POST['operation'] == "disable") {

                        $active = 0;

                        // Disable the question in the dabase

                        $updateQuestion = $db->prepare('UPDATE QuestionsAuditFormateur SET Active = :active WHERE ID = :questionID');
                        $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
                        $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $updateQuestion->execute();
                        
                        $message = "La question a bien été désactivée !";
                    
                    // If a question is enabled
                    
                    } else if($_POST['operation'] == "enable") {

                        $active = 1;

                        // Activate the question in the database
                        
                        $updateQuestion = $db->prepare('UPDATE QuestionsAuditFormateur SET Active = :active WHERE ID = :questionID');
                        $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
                        $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $updateQuestion->execute();

                        $message = "La question a bien été activée !";

                    // If a question label is updated

                    } else if($_POST['operation'] == "editQuestion" && isset($_POST['updatedQuestion']) && !empty($_POST['updatedQuestion'])) {

                        // Update the question label in the database

                        $editQuestion = $db->prepare('UPDATE QuestionsAuditFormateur SET Question = :question WHERE ID = :questionID');
                        $editQuestion->bindParam(':question', $_POST['updatedQuestion'], PDO::PARAM_STR);
                        $editQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $editQuestion->execute();

                        $message = "La question a bien été éditée !";

                    // If a evidence label is updated
                    
                    } else if($_POST['operation'] == "editEvidence" && isset($_POST['updatedEvidence']) && !empty($_POST['updatedEvidence'])) {

                        // Update the evidence label in the database

                        $editQuestion = $db->prepare('UPDATE QuestionsAuditFormateur SET Evidence = :evidence WHERE ID = :questionID');
                        $editQuestion->bindParam(':evidence', $_POST['updatedEvidence'], PDO::PARAM_STR);
                        $editQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $editQuestion->execute();

                        $message = "La preuve à fournir a bien été éditée !";

                    }

                }

            }

            // If a new question is created

            if(isset($_POST['newQuestion']) && !empty($_POST['newQuestion'])) {

                if(isset($_POST['categorieQuestion']) && !empty($_POST['categorieQuestion'])) {

                    if(isset($_POST['evidenceQuestion'])) {

                        $evidenceQuestion = htmlspecialchars($_POST['evidenceQuestion']);

                        if($evidenceQuestion == "false") {

                            $evidenceQuestion = NULL;

                        }

                        try {

                            // Inserts the new question in the database

                            $addQuestion = $db->prepare('INSERT INTO QuestionsAuditFormateur(CreatedBy, Question, Evidence, Category) VALUES(:user, :question, :evidence, :category)');
                            $addQuestion->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
                            $addQuestion->bindParam(':question', $_POST['newQuestion'], PDO::PARAM_STR);
                            if($evidenceQuestion !== NULL) {

                                $addQuestion->bindParam(':evidence', $evidenceQuestion, PDO::PARAM_STR);

                            } else {

                                $addQuestion->bindValue(':evidence', $evidenceQuestion, PDO::PARAM_NULL);

                            }
                            $addQuestion->bindParam(':category', $_POST['categorieQuestion'], PDO::PARAM_INT);
                            $addQuestion->execute();

                            $message = "La question a bien été ajoutée !";

                        } catch(PDOException $e) {

                            echo $e;

                        }

                    }

                }

            }

        } elseif($_POST['auditType'] == 2) {

            if(isset($_POST['operation']) && !empty($_POST['operation'])) {

                if(isset($_POST['questionID']) && !empty($_POST['questionID'])) {

                    $question = intval($_POST['questionID']);

                    if($_POST['operation'] == "delete") {

                        $deleteQuestion = $db->prepare('DELETE FROM IndicatorsQualiopi WHERE ID = :questionID');
                        $deleteQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $deleteQuestion->execute();

                        $deleteConstatsAudits = $db->prepare('DELETE FROM AuditsReports WHERE Question = :questionID AND Audit_Type = :auditType');
                        $deleteConstatsAudits->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $deleteConstatsAudits->bindParam(':auditType', $_POST['auditType'], PDO::PARAM_INT);
                        $deleteConstatsAudits->execute();

                        $message = "La question a bien été supprimée !";

                    } else if($_POST['operation'] == "disable") {

                        $active = 0;

                        // Disable the question in the dabase

                        $updateQuestion = $db->prepare('UPDATE IndicatorsQualiopi SET Active = :active WHERE ID = :questionID');
                        $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
                        $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $updateQuestion->execute();
                        
                        $message = "La question a bien été désactivée !";
                    
                    // If a question is enabled
                    
                    } else if($_POST['operation'] == "enable") {

                        $active = 1;

                        // Activate the question in the database
                        
                        $updateQuestion = $db->prepare('UPDATE IndicatorsQualiopi SET Active = :active WHERE ID = :questionID');
                        $updateQuestion->bindParam(':active', $active, PDO::PARAM_BOOL);
                        $updateQuestion->bindParam(':questionID', $question, PDO::PARAM_INT);
                        $updateQuestion->execute();

                        $message = "La question a bien été activée !";

                    // If a question label is updated

                    } else if($_POST['operation'] == "editQuestion" && isset($_POST['updatedQuestion']) && !empty($_POST['updatedQuestion'])) {

                        // Update the question label in the database

                        $editQuestion = $db->prepare('UPDATE IndicatorsQualiopi SET Indicator = :indicator WHERE ID = :indicatorID');
                        $editQuestion->bindParam(':indicator', $_POST['updatedQuestion'], PDO::PARAM_STR);
                        $editQuestion->bindParam(':indicatorID', $question, PDO::PARAM_INT);
                        $editQuestion->execute();

                        $message = "La question a bien été éditée !";

                    // If a evidence label is updated
                    
                    } else if($_POST['operation'] == "editEvidence" && isset($_POST['updatedEvidence']) && !empty($_POST['updatedEvidence'])) {

                        // Update the evidence label in the database

                        $editQuestion = $db->prepare('UPDATE IndicatorsQualiopi SET Evidences = :evidences WHERE ID = :indicatorID');
                        $editQuestion->bindParam(':evidences', $_POST['updatedEvidence'], PDO::PARAM_STR);
                        $editQuestion->bindParam(':indicatorID', $question, PDO::PARAM_INT);
                        $editQuestion->execute();

                        $message = "La preuve à fournir a bien été éditée !";

                    }

                }

            }

            if(isset($_POST['newQuestion']) && !empty($_POST['newQuestion']) && isset($_POST['categorieQuestion']) && !empty($_POST['categorieQuestion']) && isset($_POST['evidenceQuestion']) && !empty($_POST['evidenceQuestion'])) {

                $newQuestion = htmlspecialchars($_POST['newQuestion']);
                $evidenceQuestion = htmlspecialchars($_POST['evidenceQuestion']);

                try {

                    // Inserts the new question in the database

                    $addQuestion = $db->prepare('INSERT INTO IndicatorsQualiopi(CreatedBy, Indicator, Criteria, Evidences) VALUES(:user, :indicator, :criteria, :evidences)');
                    $addQuestion->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
                    $addQuestion->bindParam(':indicator', $newQuestion, PDO::PARAM_STR);
                    $addQuestion->bindParam(':criteria', $_POST['categorieQuestion'], PDO::PARAM_INT);
                    $addQuestion->bindParam(':evidences', $evidenceQuestion, PDO::PARAM_STR);
                    $addQuestion->execute();

                    $message = "La question a bien été ajoutée !";

                } catch(PDOException $e) {

                    echo $e;

                }

            }

        }

    }

    if(isset($message) && !empty($message)) {

        echo $message;

    }

} else {

    echo "Acces refusé !";
    return;

} ?>