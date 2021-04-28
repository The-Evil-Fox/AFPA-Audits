// Initialisation the variable page to 1 when the user log in the application
page = 1;

// Shows the help during the autoevaluation or audit

function showTip() {

    let helper = document.getElementById('helper');
    let questionsContainers = document.getElementsByClassName('form-part');

    // If the helper is displayed: hide it and shows the questions

    if(helper.style.display == "flex") {

        helper.style.display = "none";
        for(var i = 0; i<questionsContainers.length; i++) {
            questionsContainers[i].className = "form-part";
        }

    // Else shows the helper and hide the questions

    } else {

        helper.style.display = "flex";
        for(var i = 0; i<questionsContainers.length; i++) {
            questionsContainers[i].className = "form-part none";
        }

    }

}

/* 
   Checks if a audit is in progress
   if true returns a "continue audit" button
   else returns a form to create a audit
*/

function checkAuditInProgress(user) {

    let spanMessage = document.getElementById('auditSelectMessage');
    let startAuditButton = document.getElementById('startAuditButton');
    let auditTypeContainer = document.getElementById('auditTypeContainer');
    let auditedCenterContainer = document.getElementById('auditedCenterContainer');
    let assistantsContainer = document.getElementById('assistantsContainer');

    if(auditTypeContainer.style.display == "block") {

        auditTypeContainer.style.display = "none";
        auditTypeContainer.innerHTML = "";

    }

    if(assistantsContainer.style.display == "block") {
        
        assistantsContainer.style.display = "none";
        assistantsContainer.innerHTML = "";

    }

    if(startAuditButton.style.display == "block") {

        startAuditButton.style.display = "none";
        startAuditButton.innerHTML = "";

    }

    if(spanMessage.style.display == "block") {

        spanMessage.style.display = "none";
        spanMessage.innerHTML = "";
        
    }

    if(auditedCenterContainer.style.display == "block") {

        auditedCenterContainer.style.display = "none";
        auditedCenterContainer.innerHTML = "";

    }

    if(user == "") {

        return;

    }

    checkAudit = true;

    dataSend = {
        checkAudit,
        user
    }

    $.ajax({

        type: 'POST',
        url: 'traitements/createAudit.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);
            
            if(data == "Un audit est déjà en cours !") {

                spanMessage.innerHTML = data;
                spanMessage.style.color = "#EE5046";
                spanMessage.style.display = "block";
                startAuditButton.innerHTML = "Reprendre l'audit";
                startAuditButton.style.display = "block";
                startAuditButton.value = "Continuer";

            } else {

                startAuditButton.innerHTML = "Démarrer l'audit";
                startAuditButton.style.display = "block";
                startAuditButton.value = "Demarrer";

                getAuditsType = true;

                dataSend = {
                    getAuditsType
                };

                $.ajax({
        
                    type: 'POST',
                    url: 'traitements/createAudit.php',
                    data: dataSend,
                    dataType: 'text',
                    success: function(data) {
            
                        myFunctions.checkAuthentifiedUser(data);
                        
                        auditTypeContainer.style.display = "block";
                        auditTypeContainer.innerHTML = data;
                        
                    },
            
                    error: function(xhr, textStatus, error) {
            
                        myFunctions.showError(contentWindow, xhr);
                        
                    }
            
                });
        
                getCenters = true;
        
                dataSend = {
                    getCenters
                };
        
                $.ajax({
        
                    type: 'POST',
                    url: 'traitements/createAudit.php',
                    data: dataSend,
                    dataType: 'text',
                    success: function(data) {
            
                        myFunctions.checkAuthentifiedUser(data);
                        
                        auditedCenterContainer.style.display = "block";
                        auditedCenterContainer.innerHTML = data;
                        
                    },
            
                    error: function(xhr, textStatus, error) {
            
                        myFunctions.showError(contentWindow, xhr);
                        
                    }
            
                });

                getAuditeurs1 = true;
        
                dataSend = {
                    getAuditeurs1
                };

                $.ajax({
        
                    type: 'POST',
                    url: 'traitements/createAudit.php',
                    data: dataSend,
                    dataType: 'text',
                    success: function(data) {
            
                        myFunctions.checkAuthentifiedUser(data);
                        
                        assistantsContainer.style.display = "block";
                        assistantsContainer.innerHTML = data;
                        
                    },
            
                    error: function(xhr, textStatus, error) {
            
                        myFunctions.showError(contentWindow, xhr);
                        
                    }
            
                });

            }

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

// Shows a second select while creating a audit if a first assistant is selected

function showSecondAssistantSelect(assistant1) {

    if(assistant1 !== "") {

        document.getElementById('assistant2Container').className = "selectAssistant";
        
        getAuditeurs2 = true;
        
                dataSend = {
                    assistant1,
                    getAuditeurs2
                };

                $.ajax({
        
                    type: 'POST',
                    url: 'traitements/createAudit.php',
                    data: dataSend,
                    dataType: 'text',
                    success: function(data) {
            
                        myFunctions.checkAuthentifiedUser(data);
                        
                        document.getElementById('assistant2Container').className = "selectAssistant";
                        document.getElementById('assistant2Container').innerHTML = data;
                        
                    },
            
                    error: function(xhr, textStatus, error) {
            
                        myFunctions.showError(contentWindow, xhr);
                        
                    }
            
                });

    } else {

        document.getElementById('assistant2Container').className = "selectAssistant none";
        document.getElementById('assistant2').value = "";

    }

}

// Starts or resume a audit

function startAudit(buttonvalue) {

    let spanMessage = document.getElementById('auditSelectMessage');
    let startContainer = document.getElementById('startContainer');

    selectauditedUser = document.getElementById('auditedUser');
    auditedUserID = selectauditedUser.value;

    /* 
       The datasend array is different if a user continue a audit
       Because creating a new one requires differents parameters
    */

    if(buttonvalue == "Continuer") {

        continueAudit = "continueAudit";

        dataSend = {
            continueAudit,
            auditedUserID
        }

    } else if(buttonvalue == "Demarrer") {

        auditedCenter = document.getElementById('auditedCenter').value;
        let assistantAudit1 = document.getElementById('assistant1').value;
        if(typeof(document.getElementById('assistant2')) !== "undefined" && document.getElementById('assistant2') != null) {
        
            assistantAudit2 = document.getElementById('assistant2').value;

        } else {

            assistantAudit2 = "";

        }

        let auditType = document.getElementById('auditType').value;

        // Shows a error message if the user didn't select a type of audit while creating one

        if(auditType == "") {

            spanMessage.innerHTML = "Veuillez sélectionner le type d'audit !";
            spanMessage.style.color = "#EE5046";
            spanMessage.style.display = "block";
            setTimeout(() => {

                spanMessage.innerHTML = "";
                spanMessage.style.display = "none";

            }, 2500);
            return;
            
        }

        // Shows a error message if no localisation is selected by user while creating a audit

        if(auditedCenter == "") {

            spanMessage.innerHTML = "Veuillez sélectionner le centre audité !";
            spanMessage.style.color = "#EE5046";
            spanMessage.style.display = "block";
            setTimeout(() => {

                spanMessage.innerHTML = "";
                spanMessage.style.display = "none";

            }, 2500);
            return;
            
        }

        demarrerAudit = "demarrerAudit";

        if(assistantAudit1 !== "" && assistantAudit2 !== "" && assistantAudit1 == assistantAudit2) {

            spanMessage.innerHTML = "Vous ne vous pas selectionner deux fois le même assistant !";
            spanMessage.style.color = "#EE5046";
            spanMessage.style.display = "block";
            setTimeout(() => {

                spanMessage.innerHTML = "";
                spanMessage.style.display = "none";

            }, 2500);
            return;
            
        }

        if(assistantAudit1 == "") {

            assistantAudit1 = false;

        }

        if(assistantAudit2 == "") {

            assistantAudit2 = false;

        }

        dataSend = {
            demarrerAudit,
            auditType,
            auditedUserID,
            auditedCenter,
            assistantAudit1,
            assistantAudit2
        }

    }

    startContainer.style.opacity = 0;
    startContainer.style.display = "none";
    myFunctions.showLoading(contentWindow);

    // Get the audit (created or resumed) and inject it in the content window

    $.ajax({

        type: 'POST',
        url: 'traitements/createAudit.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);
            
            contentWindow.innerHTML = data;

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

// Update the report on a question in the audit

function updateReport(userID, auditType, auditNumber, question, report, textAreaID) {

    let textArea = document.getElementById(textAreaID);

    method = "updateReport";

    newReport = {
        method,
        userID,
        auditType,
        auditNumber,
        question,
        report
    }
    
    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAudit.php',
        data: newReport,
        dataType: 'text',
        success: function(data) {
            
            myFunctions.checkAuthentifiedUser(data);

            if(report == "Conforme") {

                if(textArea.value !== "") {

                    textArea.value = "";
                    if(textArea.classList.contains("visible")) {

                        textArea.className = "";

                    }

                }

            }

            if(report == "NC") {

                $('#'+textAreaID).focus();

            }

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

// Insert or update the observation on a question in the audit

function updateObservation(userID, auditType, auditNumber, question, observation, textAreaID) {

    if(observation.length <= 3) {

        alert("Le motif n'est pas valable");
        document.getElementById(textAreaID).value = "";
        return;

    }

    textArea = document.getElementById(textAreaID);

    method = "updateObservation";

    newObservation = {
        method,
        userID,
        auditType,
        auditNumber,
        question,
        observation
    }
    
    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAudit.php',
        data: newObservation,
        dataType: 'text',
        success: function(data) {
            
            myFunctions.checkAuthentifiedUser(data);

            $('#'+textAreaID).focus();

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

// Finalise the audit and get the chart witch the tables containing the non compliances, etc...

function sendAudit(auditType, auditNumber, userID, localisation, auditor, assistant1, assistant2) {

    let content = document.getElementById('content');
    let messageWindow = document.getElementById('message');
    let buttonSend = document.getElementById('questionnaire-button-send');
    let allTextAreas = document.getElementsByTagName('textarea');

    for (var i = 0; i < allTextAreas.length; i++) {

        let number = i;
        number++;

        /* Checks if all the radio button (Reports) are checked. 
           If not then a error message appears to the user. The function is also quitted
        */

        if (!$("input[name='"+number+"']:checked").val()) {
            messageWindow.innerHTML = "Veuillez cochez une réponse pour la question numéro " + number + " !";
            messageWindow.style.display = "flex";
            buttonSend.style.display = "none";
            setTimeout(() => {

                messageWindow.style.display = "none";
                messageWindow.innerHTML = "";
                buttonSend.style.display = "block";

            }, 2500);
            return false;
         }

        /* Checks if all the textarea (Observations) displayed are filled. 
           If not then a error message appears to the user. The function is also quitted
        */

        if(allTextAreas[i].style.display == "block" && allTextAreas[i].value == "") {

            messageWindow.innerHTML = "Veuillez insérer la raison de votre réponse négative à la question numéro " + number + " !";
            messageWindow.style.display = "flex";
            buttonSend.style.display = "none";
            setTimeout(() => {

                messageWindow.style.display = "none";
                messageWindow.innerHTML = "";
                buttonSend.style.display = "block";

            }, 2500);
            return false;

        }

    }

    method = "finaliseAudit";

    finaliseAudit = {
        method,
        auditType,
        auditNumber,
        userID,
        localisation,
        auditor
    }

    // Insert the first and second assistants in the datasend if they exist

    if(typeof(assistant1) !== "undefined" && typeof(assistant2) !== "undefined") {
        
        finaliseAudit = {
            method,
            auditType,
            auditNumber,
            userID,
            localisation,
            auditor,
            assistant1,
            assistant2
        }

    // Insert the first assistant in the datasend if he exist
    } else if(typeof(assistant1) !== "undefined" && typeof(assistant2) == "undefined") {

        finaliseAudit = {
            method,
            auditType,
            auditNumber,
            userID,
            localisation,
            auditor,
            assistant1
        }

    }

    // First ajax request to get the chart data
    // Datatype must be JSON or it will not work

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAudit.php',
        data: finaliseAudit,
        dataType: 'JSON',
        success: function(dataGraphique) {

            myFunctions.checkAuthentifiedUser(dataGraphique);
            
            content.innerHTML = "<div id='graphique-resultats'></div><div id='tableauContainer'></div>";

            createChart("graphique-resultats", "Résultats de l'audit", dataGraphique);


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

            // Second ajax request if the first one was successful, to get the tab containing the non compliances, etc...

            method = "getNonCompliances";

            finaliseAudit = {
                method,
                auditType,
                auditNumber,
                userID
            }
        
            $.ajax({
        
                type: 'POST',
                url: 'traitements/traitementAudit.php',
                data: finaliseAudit,
                dataType: 'text',
                success: function(dataTableau) {
        
                    myFunctions.checkAuthentifiedUser(dataTableau);
        
                    if(dataTableau !== "") {
        
                        setTimeout(() => {
        
                            document.getElementById('tableauContainer').style.display = "block";
                            document.getElementById('tableauContainer').innerHTML = dataTableau;
        
                        }, 1500);
        
                    }
        
                },
                
                error: function(xhr, textStatus, error) {
        
                    myFunctions.showError(document.getElementById('tableauContainer'), xhr);
                    
                }
        
            });

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(document.getElementById('graphique-resultats'), xhr);
            
        }

    });

}

// Starts a autoevaluation

function startEval() {

    let startContainer = document.getElementById('startContainer');

    startContainer.style.opacity = 0;
    startContainer.style.display = "none";
    myFunctions.showLoading(contentWindow);

    // Gets the autoevaluation and inject it in the content window

    $.ajax({

        type: 'POST',
        url: 'traitements/createAutoeval.php',
        data: "createNewEval",
        dataType: 'text',
        success: function(data) {
            
            myFunctions.checkAuthentifiedUser(data);

            contentWindow.innerHTML = data;

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

// Show the textarea in the autoevaluation if the user pressed the 'non' radio button so he can fill in the reason

function showTextArea(textAreaId, mustBeDisplayed) {

    let questionTextArea = document.getElementById(textAreaId);

    if(mustBeDisplayed == true) {

        questionTextArea.style.display = "block";

    } else {

        if(questionTextArea.classList.contains('visible')) {

            questionTextArea.className = "";

        }

        questionTextArea.style.display = "none";
        

    }
    
}

// Shows the previous question

function previousQuestion(partNumber) {

    if(document.getElementById('helper').style.display == "flex") {

        return;

    }

    let content = document.getElementById('content');

    const questionId = "question";
    let questionNumber = partNumber;

    content.style.opacity = "0";

    document.getElementById(questionId+questionNumber).style.display = "none";

    questionNumber--;
    page--;

    document.getElementById(questionId+questionNumber).style.display = "block";

    content.style.opacity = "1";

}

// Shows the next question

function nextQuestion(partNumber) {

    if(document.getElementById('helper').style.display == "flex") {

        return;

    }

    let content = document.getElementById('content');
    
    const questionId = "question";
    let questionNumber = partNumber;

    content.style.opacity = "0";

    document.getElementById(questionId+questionNumber).style.display = "none";

    questionNumber++;
    page++;

    document.getElementById(questionId+questionNumber).style.display = "block";

    content.style.opacity = "1";

}

// Add or update the answer on the autoevaluation 

function updateAnswer(evalNumber, question, answer, textAreaID) {

    let textArea = document.getElementById(textAreaID);

    checkReason = "checkReason";

    myAnswer = {
        evalNumber,
        question,
        answer
    };

    /* This datasend will be used with ajax to see if the question was already answered
       as 'non' and if a reason exists in the database
    */

    checkReason = {
        evalNumber,
        question,
        checkReason
    }

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAutoeval.php',
        data: myAnswer,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            if(textArea.value !== "") {

                textArea.value = "";
                if(textArea.classList.contains("visible")) {

                    textArea.className = "";

                }

            }

            if(answer == "Non") {

                $('#'+textAreaID).focus();

            }

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

    if(answer == "Oui") {

        // Check with ajax if the answer had no reason associated to it and delete it if it's true

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAutoeval.php',
            data: checkReason,
            dataType: 'text',
            success: function(data) {
                
                myFunctions.checkAuthentifiedUser(data);

            },
    
            error: function(xhr, textStatus, error) {
    
                myFunctions.showError(contentWindow, xhr);
                
            }
    
        });

    }

}

// Add or update the reason to a non compliance when the user fill in the textarea

function addReason(evalNumber, question, reason, textareaID) {

    // If the reason is lower than 3 letters a error message is displayed in a alert box

    if(reason.length <= 3) {

        alert("Le motif n'est pas valable");
        document.getElementById(textareaID).value = "";
        return;

    }


    myReason = {
        evalNumber,
        question,
        reason
    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAutoeval.php',
        data: myReason,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);
        
        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

// Finalise the autoevaluation and get the charts with the table showing the non compliances

function sendEval(eval) {

    $(document).ready(function() {

        let content = document.getElementById('content');
        let messageWindow = document.getElementById('message');
        let buttonSend = document.getElementById('questionnaire-button-send');
        let allTextAreas = document.getElementsByTagName('textarea');
    
        for (var i = 0; i < allTextAreas.length; i++) {

            let number = i;
            number++;

            /* Checks if all the radio button (Reports) are checked. 
               If not then a error message appears to the user. The function is also quitted
            */

            if (!$("input[name='"+number+"']:checked").val()) {
                messageWindow.innerHTML = "Veuillez cochez une réponse pour la question numéro " + number + " !";
                messageWindow.style.display = "flex";
                buttonSend.style.display = "none";
                setTimeout(() => {

                    messageWindow.style.display = "none";
                    messageWindow.innerHTML = "";
                    buttonSend.style.display = "block";

                }, 2500);
                return false;
             }
            /* Checks if all the textarea (Reasons) displayed are filled. 
               If not then a error message appears to the user. The function is also quitted
            */

            if(allTextAreas[i].style.display == "block" && allTextAreas[i].value == "") {

                messageWindow.innerHTML = "Veuillez insérer la raison de votre réponse négative à la question numéro " + number + " !";
                messageWindow.style.display = "flex";
                buttonSend.style.display = "none";
                setTimeout(() => {

                    messageWindow.style.display = "none";
                    messageWindow.innerHTML = "";
                    buttonSend.style.display = "block";

                }, 2500);
                return false;

            }

        }

        finalisation = {eval};

        // First ajax request to get the chart data
        // Datatype must be JSON or it will not work

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAutoeval.php',
            data: finalisation,
            dataType: 'JSON',
            success: function(data) {
                
                myFunctions.checkAuthentifiedUser(data);

                content.innerHTML = "<div id='graphique-resultats'></div><div id='tableauContainer'></div>";

                createChart("graphique-resultats", "Vos résultats", data);


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

                // Second ajax request if the first one was successful, to get the tab containing the non compliances, etc...

                $.ajax({

                    type: 'POST',
                    url: 'traitements/traitementAutoeval.php',
                    data: {
                        'getNonCompliances': true,
                        'evalNumber' : eval 
                    }, 
                    dataType: 'text',
                    success: function(dataTableau) {
        
                        myFunctions.checkAuthentifiedUser(dataTableau);
        
                        if(dataTableau !== "") {
        
                            setTimeout(() => {
        
                                document.getElementById('tableauContainer').style.display = "block";
                                document.getElementById('tableauContainer').innerHTML = dataTableau;
        
                            }, 1500);
        
                        }
        
                    },
                    
                    error: function(xhr, textStatus, error) {
        
                        myFunctions.showError(contentWindow, xhr);
                        
                    }
        
                });
            
            },
            
            error: function(xhr, textStatus, error) {

                myFunctions.showError(contentWindow, xhr);
                
            }

        });

    });

}