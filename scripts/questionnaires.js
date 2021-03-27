page = 1;

function showTip(helperid) {

    let helper = document.getElementById('helper');
    let questionsContainers = document.getElementsByClassName('form-part');

    if(helper.style.display == "flex") {

        helper.style.display = "none";
        for(var i = 0; i<questionsContainers.length; i++) {
            questionsContainers[i].className = "form-part";
        }

    } else {

        helper.style.display = "flex";
        for(var i = 0; i<questionsContainers.length; i++) {
            questionsContainers[i].className = "form-part none";
        }

    }

}

function checkAuditInProgress(user) {

    let spanMessage = document.getElementById('auditSelectMessage');
    let startAuditButton = document.getElementById('startAuditButton');
    let auditedCenterContainer = document.getElementById('auditedCenterContainer');
    let assistantsContainer = document.getElementById('assistantsContainer');

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

            if(data == "Acces refusé ! Veuillez vous connectez !") {

                window.location.replace('index.php');
                return;
                
            }
            
            if(data == "Un audit est déjà en cours !") {

                spanMessage.innerHTML = data;
                spanMessage.style.color = "#FF781B";
                spanMessage.style.display = "block";
                startAuditButton.innerHTML = "Reprendre l'audit";
                startAuditButton.style.display = "block";
                startAuditButton.value = "Continuer";

            } else {

                startAuditButton.innerHTML = "Démarrer l'audit";
                startAuditButton.style.display = "block";
                startAuditButton.value = "Demarrer";
        
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
            
                        if(data == "Acces refusé ! Veuillez vous connectez !") {
            
                            window.location.replace('index.php');
                            return;
                            
                        }
                        
                        auditedCenterContainer.style.display = "block";
                        auditedCenterContainer.innerHTML = data;
                        
                    },
            
                    error: function(xhr, textStatus, error) {
            
                        alert(xhr.status + " " + xhr.statusText);
                        
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
            
                        if(data == "Acces refusé ! Veuillez vous connectez !") {
            
                            window.location.replace('index.php');
                            return;
                            
                        }
                        
                        assistantsContainer.style.display = "block";
                        assistantsContainer.innerHTML = data;
                        
                    },
            
                    error: function(xhr, textStatus, error) {
            
                        alert(xhr.status + " " + xhr.statusText);
                        
                    }
            
                });

            }

        },

        error: function(xhr, textStatus, error) {

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

}

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
            
                        if(data == "Acces refusé ! Veuillez vous connectez !") {
            
                            window.location.replace('index.php');
                            return;
                            
                        }
                        
                        document.getElementById('assistant2Container').className = "selectAssistant";
                        document.getElementById('assistant2Container').innerHTML = data;
                        
                    },
            
                    error: function(xhr, textStatus, error) {
            
                        alert(xhr.status + " " + xhr.statusText);
                        
                    }
            
                });

    } else {

        document.getElementById('assistant2Container').className = "selectAssistant none";
        document.getElementById('assistant2').value = "";

    }

}

function startAudit(buttonvalue) {

    let spanMessage = document.getElementById('auditSelectMessage');
    let startContainer = document.getElementById('startContainer');

    selectauditedUser = document.getElementById('auditedUser');
    auditedUserID = selectauditedUser.value;

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

        if(auditedCenter == "") {

            spanMessage.innerHTML = "Veuillez sélectionner le centre audité !";
            spanMessage.style.color = "#FF781B";
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
            spanMessage.style.color = "#FF781B";
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
            auditedUserID,
            auditedCenter,
            assistantAudit1,
            assistantAudit2
        }

    }

    startContainer.style.opacity = 0;
    startContainer.style.display = "none";
    showLoading(contentWindow);

    $.ajax({

        type: 'POST',
        url: 'traitements/createAudit.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            if(data == "Acces refusé ! Veuillez vous connectez !") {

                window.location.replace('index.php');
                return;
                
            }
            
            contentWindow.innerHTML = data;

        },

        error: function(xhr, textStatus, error) {

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

}

function updateConstat(userID, audit, thematique, constat, textAreaID) {

    let textArea = document.getElementById(textAreaID);

    method = "updateConstat";

    newConstat = {
        method,
        userID,
        audit,
        thematique,
        constat
    }
    
    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAudit.php',
        data: newConstat,
        dataType: 'text',
        success: function(data) {
            
            if(data == "Acces refusé ! Veuillez vous connectez !") {

                window.location.replace('index.php');
                return;
                
            }

            if(constat == "Conforme") {

                if(textArea.value !== "") {

                    textArea.value = "";
                    if(textArea.classList.contains("visible")) {

                        textArea.className = "";

                    }

                }

            }

            if(constat == "NC") {

                $('#'+textAreaID).focus();

            }

        },

        error: function(xhr, textStatus, error) {

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

}

function updateObservation(userID, audit, thematique, observation, textAreaID) {

    if(observation.length <= 3) {

        alert("Le motif n'est pas valable");
        document.getElementById(textareaID).value = "";
        return;

    }

    textArea = document.getElementById(textAreaID);

    method = "updateObservation";

    newObservation = {
        method,
        userID,
        audit,
        thematique,
        observation
    }
    
    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAudit.php',
        data: newObservation,
        dataType: 'text',
        success: function(data) {
            
            if(data == "Acces refusé ! Veuillez vous connectez !") {

                window.location.replace('index.php');
                return;
                
            }

            $('#'+textAreaID).focus();

        },

        error: function(xhr, textStatus, error) {

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

}

function sendAudit(audit, userID, localisation, auditor, assistant1, assistant2) {

    let content = document.getElementById('content');
    let messageWindow = document.getElementById('message');
    let buttonSend = document.getElementById('questionnaire-button-send');
    let allTextAreas = document.getElementsByTagName('textarea');

    for (var i = 0; i < allTextAreas.length; i++) {

        let number = i;
        number++;

        if (!$("input[name='"+number+"']:checked").val()) {
            messageWindow.innerHTML = "Veuillez cochez une réponse pour la question numéro " + number + " !";
            messageWindow.style.display = "flex";
            messageWindow.style.color = "#FF781B";
            buttonSend.style.display = "none";
            setTimeout(() => {

                messageWindow.style.display = "none";
                messageWindow.innerHTML = "";
                buttonSend.style.display = "block";

            }, 2500);
            return false;
         }

        if(allTextAreas[i].style.display == "block" && allTextAreas[i].value == "") {

            messageWindow.innerHTML = "Veuillez insérer la raison de votre réponse négative à la question numéro " + number + " !";
            messageWindow.style.display = "flex";
            messageWindow.style.color = "#FF781B";
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
        audit,
        userID,
        localisation,
        auditor
    }

    if(typeof(assistant1) !== "undefined" && typeof(assistant2) !== "undefined") {
        
        finaliseAudit = {
            method,
            audit,
            userID,
            localisation,
            auditor,
            assistant1,
            assistant2
        }

    } else if(typeof(assistant1) !== "undefined" && typeof(assistant2) == "undefined") {

        finaliseAudit = {
            method,
            audit,
            userID,
            localisation,
            auditor,
            assistant1
        }

    }

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAudit.php',
        data: finaliseAudit,
        dataType: 'JSON',
        success: function(dataGraphique) {
            
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

        },

        error: function(xhr, textStatus, error) {

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

    method = "getNonCompliances";

    finaliseAudit = {
        method,
        audit,
        userID
    }

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAudit.php',
        data: finaliseAudit,
        dataType: 'text',
        success: function(dataTableau) {

            if(dataTableau == "Acces refusé ! Veuillez vous connectez !") {

                window.location.replace('index.php');
                return;
                
            }

            if(dataTableau !== "") {

                setTimeout(() => {

                    document.getElementById('tableauContainer').style.display = "block";
                    document.getElementById('tableauContainer').innerHTML = dataTableau;

                }, 750);

            }

        },
        
        error: function(xhr, textStatus, error) {

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

}

function startEval() {

    let startContainer = document.getElementById('startContainer');

    startContainer.style.opacity = 0;
    startContainer.style.display = "none";
    showLoading(contentWindow);

    $.ajax({

        type: 'POST',
        url: 'traitements/createAutoeval.php',
        data: "createNewEval",
        dataType: 'text',
        success: function(data) {
            
            if(data == "Acces refusé ! Veuillez vous connectez !") {

                window.location.replace('index.php');
                return;
                
            }

            contentWindow.innerHTML = data;

        },

        error: function(xhr, textStatus, error) {

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

}

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

function updateAnswer(evalNumber, question, answer, textAreaID) {

    let textArea = document.getElementById(textAreaID);

    checkReason = "checkReason";

    myAnswer = {
        evalNumber,
        question,
        answer
    };

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

            if(data == "Acces refusé ! Veuillez vous connectez !") {

                window.location.replace('index.php');
                return;
                
            }

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

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

    if(answer == "Oui") {

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAutoeval.php',
            data: checkReason,
            dataType: 'text',
            success: function(data) {
                
                if(data == "Acces refusé ! Veuillez vous connectez !") {

                    window.location.replace('index.php');
                    return;
                    
                }

            },
    
            error: function(xhr, textStatus, error) {
    
                alert(xhr.status + " " + xhr.statusText);
                
            }
    
        });

    }

}

function addReason(evalNumber, question, reason, textareaID) {

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
        
        },

        error: function(xhr, textStatus, error) {

            alert(xhr.status + " " + xhr.statusText);
            
        }

    });

}

function sendEval(eval) {

    $(document).ready(function() {

        let content = document.getElementById('content');
        let messageWindow = document.getElementById('message');
        let buttonSend = document.getElementById('questionnaire-button-send');
        let allTextAreas = document.getElementsByTagName('textarea');
    
        for (var i = 0; i < allTextAreas.length; i++) {

            let number = i;
            number++;

            if (!$("input[name='"+number+"']:checked").val()) {
                messageWindow.innerHTML = "Veuillez cochez une réponse pour la question numéro " + number + " !";
                messageWindow.style.display = "flex";
                messageWindow.style.color = "#FF781B";
                buttonSend.style.display = "none";
                setTimeout(() => {

                    messageWindow.style.display = "none";
                    messageWindow.innerHTML = "";
                    buttonSend.style.display = "block";

                }, 2500);
                return false;
             }

            if(allTextAreas[i].style.display == "block" && allTextAreas[i].value == "") {

                messageWindow.innerHTML = "Veuillez insérer la raison de votre réponse négative à la question numéro " + number + " !";
                messageWindow.style.display = "flex";
                messageWindow.style.color = "#FF781B";
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

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAutoeval.php',
            data: finalisation,
            dataType: 'JSON',
            success: function(data) {
                
                if(data == "Acces refusé ! Veuillez vous connectez !") {

                    window.location.replace('index.php');
                    return;
                    
                }

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
            
            },
            
            error: function(xhr, textStatus, error) {

                alert(xhr.status + " " + xhr.statusText);
                
            }

        });

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAutoeval.php',
            data: {
                'getNonCompliances': true,
                'evalNumber' : eval 
            }, 
            dataType: 'text',
            success: function(dataTableau) {

                if(dataTableau == "Acces refusé ! Veuillez vous connectez !") {

                    window.location.replace('index.php');
                    return;
                    
                }

                if(dataTableau !== "") {

                    setTimeout(() => {

                        document.getElementById('tableauContainer').style.display = "block";
                        document.getElementById('tableauContainer').innerHTML = dataTableau;

                    }, 750);

                }

            },
            
            error: function(xhr, textStatus, error) {

                alert(xhr.status + " " + xhr.statusText);
                
            }

        });

    });

}