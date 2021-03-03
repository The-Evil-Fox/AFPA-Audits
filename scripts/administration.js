function showAdministration(category) {
    
    $(document).ready(function() {

        let contentWindow = document.getElementById('content');

        $.ajax({

            type: 'POST',
            url: 'traitements/administrationGenerator.php',
            data: category,
            dataType: 'text',
            success: function(data) {

                contentWindow.className = "content";

                contentWindow.style.opacity = 0;

                setTimeout(function() {

                    contentWindow.innerHTML = data;
                    contentWindow.style.opacity = 1;

                }, 500);

                if ($('#mobileNavDropdown').attr('class').indexOf('active') > -1) {

                    $('#mobileNavDropdown').removeClass('active');

                }

            },
            error: function(xhr, textStatus, error){
                alert(error);
            }

        });

    });

}

function showUserResultEval(userID, eval, userName, userFirstName) {

    userData = { 
        userID,
        eval 
    };

    $.ajax({

        type: 'POST',
        url: 'traitements/getUserResultEval.php',
        data: userData,
        dataType: 'JSON',
        success: function(data) {

            content.innerHTML = "<div id='resultat-autoevaluation'></div><div id='tableau-nonconformites'></div>";

            createChart("resultat-autoevaluation", "Résultats de l'autoévaluation de " + userName + " " + userFirstName, data);


            function hideMessages() {

                var x = document.getElementsByClassName("canvasjs-chart-credit");
                var i;
                for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
                }

            }
        
            setInterval(() => {

                hideMessages();
                
            }, 15);
        
        }

    });

    $.ajax({

        type: 'POST',
        url: 'traitements/getUserResultEval.php',
        data: {
            'getNonCompliances': true,
            'evalNumber' : eval,
            'user': userID
        },
        dataType: 'text',
        success: function(data) {

            setTimeout(() => {

                document.getElementById('tableau-nonconformites').innerHTML = data;

            }, 250);
        
        }

    });

}

function updateStatusRemoveQuestionAutoEval(button, questionID) {

    let operation = button.value;

    let questionDiv = document.getElementById('question'+questionID);
    let questionStatus = document.getElementById('questionStatus'+questionID);

    dataSend = {
        operation,
        questionID
    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAdministrationAutoeval.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            if(operation == "delete") {

                questionDiv.parentNode.removeChild(questionDiv);

            }

            if(operation == "disable") {

                questionStatus.innerHTML = "[INACTIVE] ";
                button.value="enable";

            }

            if(operation == "enable") {

                questionStatus.innerHTML = "[ACTIVE] ";
                button.value="disable";

            }

            // setTimeout(() => {
            
            //     alert(data);
            
            // }, 250);

        },
        error: function(xhr, textStatus, error){
            alert(error);
        }

    });

}

function updateQuestionAutoEval(button, questionID) {

    let questionDiv = document.getElementById('question'+questionID);
    let questionContainer = document.getElementById('question-container-'+questionID);
    let question = document.getElementById('question-label-'+questionID);

    questionText = question.innerHTML;

    question.innerHTML = "<input class='autoeval-input-edit' type='text' id='input"+questionID+"' value='"+questionText+"'><span class='edit-notification'>Appuyez sur ECHAP ou cliquez en dehors du champ pour annuler.</span>";

    questionInput = document.getElementById('input'+questionID);

    questionInput.focus();

    function cancelModification() {

        question.innerHTML = questionText;
        return false;

    }

    enterDetect = (e) => {

        keyPressed = e.which || e.keyCode;
        
        if(keyPressed === 13) {
            
            sendUpdatedQuestion(questionInput.value);

        }

        if(keyPressed === 27) {

            cancelModification();

        }
    
    }

    $('#input'+questionID).on('focusout', function() {

        cancelModification();

    });

    question.addEventListener('keydown', enterDetect);

    function sendUpdatedQuestion(updatedQuestion) {

        operation = "edit";

        dataSend = {
            operation,
            questionID,
            updatedQuestion
        };

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAutoeval.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                question.innerHTML = updatedQuestion;

                // setTimeout(() => {
            
                //     alert(data);
                
                // }, 250);

            },
            error: function(xhr, textStatus, error){
                alert(error);
            }

        });

    }

}

function addQuestion() {

    let newQuestion = document.getElementById('newQuestion').value;
    let categorieQuestion = document.getElementById('categorie').value;

    if(newQuestion == "") {

        alert('Veuillez insérer votre question !');
        return;

    }

    if(categorieQuestion == "") {

        alert('Veuillez sélectionner la catégorie de la question');
        return;

    }

    dataSend = {
        newQuestion,
        categorieQuestion
    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAdministrationAutoeval.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            alert(data);

            // setTimeout(() => {
        
            //     alert(data);
            
            // }, 250);

        },
        error: function(xhr, textStatus, error){
            alert(error);
        }

    });

}