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

    // First gets the data to display on the chart

    if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['eval']) && !empty($_POST['eval']) && !isset($_POST['getNonCompliances'])) {

        // Store the value of the selected user and selected evaluation the user want the result of

        $user = intval($_POST['userID']);
        $evalNumber = intval($_POST['eval']);

        // Gets the number of compliances and non compliances and store them in variables as a int

        $countCompliances = $db->prepare(
            'SELECT count(*) as nb_compliance FROM ResultatsAutoevaluations 
            WHERE Evaluation_Number = :eval AND Answer = "Oui" AND User_ID = :user'
        );
        $countCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $countCompliances->bindParam(':user', $user, PDO::PARAM_INT);
        $countCompliances->execute();

        $resultCount1 = $countCompliances->fetch();

        $compliances = (int) $resultCount1['nb_compliance'];

        $countNonCompliances = $db->prepare('SELECT count(*) as nb_noncompliance FROM ResultatsAutoevaluations WHERE Evaluation_Number = :eval AND Answer = "Non" AND User_ID = :user');
        $countNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $countNonCompliances->bindParam(':user', $user, PDO::PARAM_INT);
        $countNonCompliances->execute();

        $resultCount2 = $countNonCompliances->fetch();

        $nonCompliances = (int) $resultCount2['nb_noncompliance'];

        /* 
        Checks each result defined above and if it's not equal to 0: 
        Create an array containing the data to display on the chart with the styling parameters
        */

        if($compliances !== 0) {

            $compliancesTab = array(
                "label"=> "Conformité(s)", "y"=> $compliances, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
            );

        }

        if($nonCompliances !== 0) {

            $nonCompliancesTab = array(
                "label"=> "Non-conformité(s)", "y"=> $nonCompliances, "indexLabelFontColor" => "#000000", "indexLabelFontWeight" => "bolder"
            );

        }

        // Creates a empty array wich will contain the data to send as a response

        $dataPoints = array(

        );

        /*
        Checks if each of our expected results exists and if they are stored in a array
        If they do, pushs the array into the $datapoints array that will be sent back as a response
        */

        if(isset($compliancesTab) && is_array($compliancesTab)) {

            array_push($dataPoints, $compliancesTab);

        }

        if(isset($nonCompliancesTab) && is_array($nonCompliancesTab)) {

            array_push($dataPoints, $nonCompliancesTab);

        }

        // Returns the $datapoints array, encoded in JSON

        echo json_encode($dataPoints, JSON_NUMERIC_CHECK);

    }

    // Creates and shows a table containing all the questions reported as non compliance for the selected user and selected autoevaluation

    if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['evalNumber']) && !empty($_POST['evalNumber']) && isset($_POST['getNonCompliances'])) {

        $evalNumber = intval($_POST['evalNumber']);
        $user = intval($_POST['userID']);

        $numberAllNonCompliances = $db->prepare('SELECT COUNT(qa.Question) as nbr_non_compliances FROM RaisonsNonConformitesAutoevaluation rnca JOIN QuestionsAutoevaluation qa ON rnca.Question_ID = qa.ID WHERE rnca.User = :user AND Eval_Number = :eval ');
        $numberAllNonCompliances->bindParam(':user', $user, PDO::PARAM_INT);
        $numberAllNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
        $numberAllNonCompliances->execute();

        $result = $numberAllNonCompliances->fetch();

        $numberNonCompliances = (int) $result['nbr_non_compliances'];

        if($numberNonCompliances !== 0) {

            $getUserNonCompliances = $db->prepare('SELECT qa.Question, rnca.Reason FROM RaisonsNonConformitesAutoevaluation rnca JOIN QuestionsAutoevaluation qa ON rnca.Question_ID = qa.ID WHERE rnca.User = :user AND Eval_Number = :eval');
            $getUserNonCompliances->bindParam(':user', $user, PDO::PARAM_INT);
            $getUserNonCompliances->bindParam(':eval', $evalNumber, PDO::PARAM_INT);
            $getUserNonCompliances->execute();

            ?>

            <!-- Base of the table -->

            <table id="tableauNonConformites">
                <thead>
                    <tr>
                        <th><?php if($numberNonCompliances > 1) { ?> Questions non-conformes <?php } else { ?> Question non-conforme <?php } ?></th>
                        <th><?php if($numberNonCompliances > 1) { ?> Raisons <?php } else { ?> Raison <?php } ?></th>
                    </tr>
                </thead>
                <!-- Inserting the data in the table -->
                <tbody>
                    <?php while($nonCompliances = $getUserNonCompliances->fetch()) { ?>
                        <tr>
                            <td data-label="Non conformité"><?= str_replace('?', '', $nonCompliances['Question']); ?></td>
                            <td data-label="Raison"><?= $nonCompliances['Reason']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php }

    }

} else {

    echo "Acces refusé !";
    return;

} ?>