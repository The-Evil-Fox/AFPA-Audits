// Administration content generator

function showAdministration(category) {
    
    $(document).ready(function() {

        // Shows the loading image
        myFunctions.showLoading(contentWindow);

        $.ajax({

            type: 'POST',
            url: 'contentGenerators/administrationGenerator.php',
            data: category,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                if(category == "exitAdministration") {

                    document.getElementById('sidebar').innerHTML = data;
                    return;

                }

                contentWindow.className = "content";

                contentWindow.style.opacity = 0;

                setTimeout(function() {

                    contentWindow.innerHTML = data;
                    contentWindow.style.opacity = 1;

                }, 500);

                if($('#mobileNavDropdown').attr('class').indexOf('active') > -1) {

                    $('#mobileNavDropdown').removeClass('active');

                }

            },
            
            error: function(xhr, textStatus, error){

                myFunctions.showError(contentWindow, xhr);
                
            }

        });

    });

}

// Show the result of the autoeval of the selected user

function showUserResultEval(userID, eval, userName, userFirstName, date) {

    userData = { 
        userID,
        eval 
    };

    myFunctions.showLoading(contentWindow);

    $.ajax({

        type: 'POST',
        url: 'contentGenerators/userResultEval.php',
        data: userData,
        dataType: 'JSON',
        success: function(dataGraphique) {

            myFunctions.checkAuthentifiedUser(dataGraphique);

            content.innerHTML = "<div id='graphique-resultats'></div><div id='dateGraphique'>Effectuée le "+date+".</div><div id='tableauContainer'></div>";

            createChart("graphique-resultats", "Résultats de l'autoévaluation de " + userName + " " + userFirstName, dataGraphique);


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

            $.ajax({

                type: 'POST',
                url: 'contentGenerators/userResultEval.php',
                data: {
                    'getNonCompliances': true,
                    'evalNumber' : eval,
                    'userID': userID
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
        
                error: function(xhr, textStatus, error){
        
                    myFunctions.showError(contentWindow, xhr);
                    
                }
        
            });
        
        },

        error: function(xhr, textStatus, error){

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

function showUserResultAudit(userID, auditType, auditNumber, userName, userFirstName, auditeurName, auditeurFirstName, date, centre, assistant1Name, assistant1FirstName, assistant2Name, assistant2FirstName) {

    userData = {

        userID,
        auditType,
        auditNumber
        
    };

    $.ajax({

        type: 'POST',
        url: 'contentGenerators/userResultAudit.php',
        data: userData,
        dataType: 'JSON',
        success: function(dataGraphique) {

            myFunctions.checkAuthentifiedUser(dataGraphique);

            if(typeof(assistant1Name) && typeof(assistant1FirstName) == "undefined") {

                content.innerHTML = `
                    <div id='graphique-resultats'></div>
                    <div id='dateGraphique'>
                        Effectué au centre de ` + centre + ` le ` + date + ` par ` + auditeurName + " " + auditeurFirstName + `.
                    </div>
                    <div id='tableauContainer'></div>`;
            
            } else {

                if(typeof(assistant1Name) && typeof(assistant1FirstName) !== "undefined" && typeof(assistant2Name) && typeof(assistant2FirstName) == "undefined") {

                    content.innerHTML = `
                    <div id='graphique-resultats'></div>
                    <div id='dateGraphique'>
                        Effectué au centre de ` + centre + ` le ` + date + ` <br>par ` + auditeurName + " " + auditeurFirstName + `.
                    </div>
                    <table id='tableauAssistants'>
                        <thead>
                            <tr>
                                <th>Assistant</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td data-label='Assitant'>` + assistant1Name + " " + assistant1FirstName + `</td>
                        </tr>
                    </table>
                    <div id='tableauContainer'></div>`;

                } else if(typeof(assistant1Name) && typeof(assistant1FirstName) && typeof(assistant2Name) && typeof(assistant2FirstName) !== "undefined") {

                    content.innerHTML = `
                    <div id='graphique-resultats'></div>
                    <div id='dateGraphique'>
                        Effectué au centre de ` + centre + ` le ` + date + ` par ` + auditeurName + " " + auditeurFirstName + `.
                    </div>
                    <table id='tableauAssistants'>
                        <thead>
                            <tr>
                                <th>Assistants</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td data-label='Assitant n°1'>` + assistant1Name + " " + assistant1FirstName + `</td>
                            <td data-label='Assitant n°2'>` + assistant2Name + " " + assistant2FirstName + `</td>
                        </tr>
                    </table>
                    <div id='tableauContainer'></div>`;

                }

            }

            createChart("graphique-resultats", "Résultats de l'audit de " + userName + " " + userFirstName, dataGraphique);


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

            $.ajax({

                type: 'POST',
                url: 'contentGenerators/userResultAudit.php',
                data: {
                    'getNonCompliances': true,
                    'auditType' : auditType,
                    'auditNumber' : auditNumber,
                    'userID': userID
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
        
                error: function(xhr, textStatus, error){
        
                    myFunctions.showError(contentWindow, xhr);
                    
                }
        
            });
        
        },

        error: function(xhr, textStatus, error){

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

// Edition of the questionnaires

function showAdminForm(questionnaire) {

    if(questionnaire == "autoeval") {

        let formContainer = document.getElementById('autoevalFormContainer');
        let button = document.getElementById('autoevalAddQuestionButton');

        if(formContainer.style.display == "flex") {

            formContainer.style.display = "none";
            button.style.display = "block";

        } else {

            formContainer.style.display = "flex";
            button.style.display = "none";

        }

    } else if(questionnaire == "audit") {

        let formContainer = document.getElementById('auditFormContainer');
        let button = document.getElementById('auditAddQuestionButton');

        if(formContainer.style.display == "flex") {

            formContainer.style.display = "none";
            button.style.display = "block";

        } else {

            formContainer.style.display = "flex";
            button.style.display = "none";

        }

    }

}

function addQuestionQuestionnaire(questionnaire, auditType) {

    if(auditType == 1) {
    
        let newQuestion = document.getElementById('newQuestion').value;
    
    }

    let categorieQuestion = document.getElementById('categorie').value;
    let messageWindow = document.getElementById('message');

    if(questionnaire == "audit") {

        evidenceQuestion = document.getElementById('preuvesQuestion').value;

    }

    if(auditType == 1) {

        if(newQuestion == "") {

            messageWindow.innerHTML = "Veuillez insérer votre question !";
            messageWindow.style.display = "flex";
            setTimeout(() => {

                messageWindow.style.display = "none";
                messageWindow.innerHTML = "";

            }, 2500);
            return false;

        }

    }

    if(auditType == 2 && evidenceQuestion == "") {

        messageWindow.innerHTML = "Veuillez insérer la nouvelle question !";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    if(categorieQuestion == "") {

        messageWindow.innerHTML = "Veuillez sélectionner la catégorie de la question !";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    if(questionnaire == "audit" && auditType == 1 && preuvesQuestion == "") {

        preuvesQuestion = false;

    }

    if(questionnaire == "autoevaluation") {

        dataSend = {
            newQuestion,
            categorieQuestion
        };

    } else if(questionnaire == "audit") {

        if(auditType == 1) {

            dataSend = {

                newQuestion,
                categorieQuestion,
                evidenceQuestion,
                auditType

            };

        }

        if(auditType == 2) {

            dataSend = {

                categorieQuestion,
                evidenceQuestion,
                auditType

            };

        }

    }

    if(questionnaire == "autoevaluation") {

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAutoeval.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

            },

            error: function(xhr, textStatus, error) {

                myFunctions.showError(contentWindow, xhr);

            }

        });

        $.ajax({

            type: 'POST',
            url: 'contentGenerators/administrationGenerator.php',
            data: "autoevaluation",
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);
    
                setTimeout(() => {
    
                    contentWindow.innerHTML = data;
    
                }, 750);
    
            },
    
            error: function(xhr, textStatus, error) {
    
                myFunctions.showError(contentWindow, xhr);
                
            }
    
        });

    } else if(questionnaire == "audit") {

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAudit.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

            },

            error: function(xhr, textStatus, error) {

                myFunctions.showError(contentWindow, xhr);

            }

        });

        let modificationAudit = true;

        dataSend = {

            modificationAudit,
            auditType

        };

        $.ajax({

            type: 'POST',
            url: 'contentGenerators/auditModificatorGenerator.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);
    
                setTimeout(() => {
    
                    document.getElementById('modifyingToolContainer').innerHTML = data;
    
                }, 750);
    
            },
    
            error: function(xhr, textStatus, error) {
    
                myFunctions.showError(contentWindow, xhr);
                
            }
    
        });

    }

}


function updateStatusRemoveQuestionQuestionnaire(questionnaire, button, questionID, auditType) {


    let operation = button.value;

    let questionStatus = document.getElementById('questionStatus'+questionID);

    dataSend = {

        operation,
        questionID,
        
    };

    if(questionnaire == "autoeval") {

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAutoeval.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                if(operation == "delete") {

                    $.ajax({

                        type: 'POST',
                        url: 'contentGenerators/administrationGenerator.php',
                        data: "autoevaluation",
                        dataType: 'text',
                        success: function(data) {

                            myFunctions.checkAuthentifiedUser(data);
                
                            setTimeout(() => {
                
                                contentWindow.innerHTML = data;
                
                            }, 500);
                
                        },
                
                        error: function(xhr, textStatus, error) {
                
                            myFunctions.showError(contentWindow, xhr);
                            
                        }
                
                    });

                } else if(operation == "disable") {

                    questionStatus.innerHTML = "Inactive";
                    button.value = "enable";
                    button.innerHTML = "<i class='fas fa-toggle-off'></i> Activer";
                    button.className = "greenButton";

                } else if(operation == "enable") {

                    questionStatus.innerHTML = "Active";
                    button.value = "disable";
                    button.innerHTML = "<i class='fas fa-toggle-on'></i> Désactiver";
                    button.className = "redButton";

                }

            },

            error: function(xhr, textStatus, error){

                myFunctions.showError(contentWindow, xhr);

            }

        });

    } else if(questionnaire == "audit") {

        dataSend = {

            operation,
            questionID,
            auditType
            
        };

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAudit.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                let modificationAudit = true;

                if(operation == "delete") {

                    dataSend = {

                        modificationAudit,
                        auditType

                    };

                    $.ajax({

                        type: 'POST',
                        url: 'contentGenerators/auditModificatorGenerator.php',
                        data: dataSend,
                        dataType: 'text',
                        success: function(data) {

                            myFunctions.checkAuthentifiedUser(data);
                
                            setTimeout(() => {
                
                                document.getElementById('modifyingToolContainer').innerHTML = data;
                
                            }, 500);
                
                        },
                
                        error: function(xhr, textStatus, error) {
                
                            myFunctions.showError(contentWindow, xhr);

                        }
                
                    });

                }

                if(operation == "disable") {

                    questionStatus.innerHTML = "Inactive";
                    button.value = "enable";
                    button.innerHTML = "<i class='fas fa-toggle-off'></i> Activer";
                    button.className = "greenButton";

                }

                if(operation == "enable") {

                    questionStatus.innerHTML = "Active";
                    button.value = "disable";
                    button.innerHTML = "<i class='fas fa-toggle-on'></i> Désactiver";
                    button.className = "redButton";

                }

            },
            error: function(xhr, textStatus, error){

                myFunctions.showError(contentWindow, xhr);

            }

        });

    }

}

function updateQuestionQuestionnaire(questionnaire, questionID, auditType) {

    let question = document.getElementById('question-label-'+questionID);

    questionText = question.innerHTML;

    question.innerHTML = "<input class='questionnaires-input-edit' type='text' id='input"+questionID+"' value='"+questionText+"'><span class='edit-notification'>Appuyez sur ECHAP ou cliquez en dehors du champ pour annuler.</span>";

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

        operation = "editQuestion";

        dataSend = {
            operation,
            questionID,
            updatedQuestion,
        };

        if(questionnaire == "autoeval") {

            $.ajax({

                type: 'POST',
                url: 'traitements/traitementAdministrationAutoeval.php',
                data: dataSend,
                dataType: 'text',
                success: function(data) {

                    myFunctions.checkAuthentifiedUser(data);

                    if(data == "La question a bien été éditée !") {
                    
                        question.innerHTML = updatedQuestion;
                        return;
                    
                    }

                },

                error: function(xhr, textStatus, error) {

                    myFunctions.showError(contentWindow, xhr);

                }

            });

        } else if(questionnaire == "audit") {

            dataSend = {
                operation,
                questionID,
                updatedQuestion,
                auditType
            };

            $.ajax({

                type: 'POST',
                url: 'traitements/traitementAdministrationAudit.php',
                data: dataSend,
                dataType: 'text',
                success: function(data) {

                    myFunctions.checkAuthentifiedUser(data);

                    if(data == "La question a bien été éditée !") {
                    
                        question.innerHTML = updatedQuestion;
                        return;
                    
                    }

                },

                error: function(xhr, textStatus, error) {

                    myFunctions.showError(contentWindow, xhr);

                }

            });

        }

    }

}

function updateEvidenceQuestionnaire(questionID, auditType) {

    let evidence = document.getElementById('question-evidence-'+questionID);

    evidenceText = evidence.innerHTML;

    evidence.innerHTML = "<input class='questionnaires-input-edit' type='text' id='input"+questionID+"' value='"+evidenceText+"'><span class='edit-notification'>Appuyez sur ECHAP ou cliquez en dehors du champ pour annuler.</span>";

    evidenceInput = document.getElementById('input'+questionID);

    evidenceInput.focus();

    function cancelModification() {

        evidence.innerHTML = evidenceText;
        return false;

    }

    enterDetect = (e) => {

        keyPressed = e.which || e.keyCode;
        
        if(keyPressed === 13) {
            
            sendUpdatedEvidence(evidenceInput.value);

        }

        if(keyPressed === 27) {

            cancelModification();

        }
    
    }

    $('#input'+questionID).on('focusout', function() {

        cancelModification();

    });

    evidence.addEventListener('keydown', enterDetect);

    function sendUpdatedEvidence(updatedEvidence) {

        operation = "editEvidence";

        dataSend = {
            operation,
            questionID,
            updatedEvidence,
            auditType
        };

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAudit.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                if(data == "La preuve à fournir a bien été éditée !") {
                
                    evidence.innerHTML = updatedEvidence;
                    return;
                
                }

            },

            error: function(xhr, textStatus, error) {

                myFunctions.showError(contentWindow, xhr);

            }

        });

    }

}

// Documents functions

function addDocument() {
    
    $('#addDocument').click();
    $('#addDocument').on('change', function() {

        let userfile = $(this).val();
        if(userfile) {

            $('#formDocument').submit();
        
        } else {

            return;

        }

    });

    $('#formDocument').on('submit', function(e) {
    
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'traitements/traitementDocuments.php',
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                if(data.includes("Erreur:")) {
                    
                    alert(data);
                    document.getElementById('addDocument').value = "";
                    
                    
                } else {

                refreshDocuments();

                }
    
            }
    
        });

    });

}

function deleteDocument(action, id, link) {

    operation = {
        action,
        id,
        link
    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementDocuments.php',
        data: operation,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            refreshDocuments();

        },

        error: function(xhr, textStatus, error){

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

function refreshDocuments() {

    myFunctions.showLoading(contentWindow);

    $.ajax({

        type: 'POST',
        url: 'contentGenerators/administrationGenerator.php',
        data: "documents",
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            contentWindow.innerHTML = data;

        },

        error: function(xhr, textStatus, error){

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

// global stats function

function getSelectTypeList() {

    getAuditsTypes = true;

    dataSend = {

        getAuditsTypes

    }

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementStats.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            if(data !== "") {

                document.getElementById('selectTypeContainer').innerHTML = data;

            }

        },

        error: function(xhr, textStatus, error){

            myFunctions.showError(contentWindow, xhr);

        }

    });

}   

function showTab(auditType) {

    document.getElementById('tableauContainer').style.display = "block";

    myFunctions.showLoading(document.getElementById('tableauContainer'));

    let getTab = true;
    let type = auditType;

    dataSend = {

        getTab,
        type

    }

    $.ajax({

        type: 'POST',
        url: 'contentGenerators/globalStats.php',
        data: dataSend, 
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            if(data !== "") {

                document.getElementById('tableauContainer').innerHTML = data;

            }

        },
        
        error: function(xhr, textStatus, error){

            myFunctions.showError(contentWindow, xhr);
            
        }

    });

}

function showStats(auditType) {

    $(document).ready(function() {

        let globalStatsContainer = document.getElementById('globalStats-container');

        if(auditType == "") {
            
            globalStatsContainer.style.opacity = 0;
            globalStatsContainer.innerHTML = "";
            return;

        }

        myFunctions.showLoading(globalStatsContainer);

        let countAudits = true;

        dataSend = {

            countAudits,
            auditType

        };

        noDataForStats = false;

        $.ajax({

            type: 'POST',
            url: 'contentGenerators/globalStats.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                if(data !== "") {

                    if($('#mobileNavDropdown').attr('class').indexOf('active') > -1) {

                        $('#mobileNavDropdown').removeClass('active');
    
                    }

                    globalStatsContainer.innerHTML = data;
                    globalStatsContainer.style.opacity = 1;
                    noDataForStats = true;

                }

            },

            error: function(xhr, textStatus, error){

                myFunctions.showError(globalStatsContainer, xhr);

            }

        });

        setTimeout(() => {

            if(noDataForStats == true) {

                return;

            } else {

                if(auditType == 1) {

                    titleChart = "Stastiques globales audits formateur"

                } else if(auditType == 2) {

                    titleChart = "Statistiques globales audits Qualiopi"

                }

                let getStats = true;

                dataSend = {

                    getStats,
                    auditType
                    
                }

                $.ajax({

                    type: 'POST',
                    url: 'contentGenerators/globalStats.php',
                    data: dataSend,
                    dataType: 'JSON',
                    success: function(data) {

                        myFunctions.checkAuthentifiedUser(data);

                        if($('#mobileNavDropdown').attr('class').indexOf('active') > -1) {

                            $('#mobileNavDropdown').removeClass('active');

                        }

                        setTimeout(function() {

                            globalStatsContainer.innerHTML = "<div id='graphique-resultats'></div><div id='tableauContainer'></div>";

                            createChart("graphique-resultats", titleChart, data);

                            globalStatsContainer.style.opacity = 1;

                        }, 500);

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

                        setTimeout(() => {

                            getTab(auditType);

                        }, 1500);

                    },

                    error: function(xhr, textStatus, error){

                        myFunctions.showError(contentWindow, xhr);

                    }

                });

            }

        }, 750);

    });

}

function getTab(auditType) {

    let tableauContainer = document.getElementById('tableauContainer');
    let getTab = true;

    dataSend = {
        
        getTab,
        auditType

    };

    tableauContainer.style.display = "block";

    myFunctions.showLoading(tableauContainer);

    $.ajax({

        type: 'POST',
        url: 'contentGenerators/globalStats.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            setTimeout(function() {

                tableauContainer.innerHTML = data;

            }, 750);

        },

        error: function(xhr, textStatus, error){

            myFunctions.showError(tableauContainer, xhr);

        }

    });

}

function statsOrderBy(auditType, orderBy) {

    let tableauContainer = document.getElementById('tableauContainer');
    myFunctions.showLoading(tableauContainer);

    let orderByParam = orderBy;
    let type = auditType;

    if(orderBy !== "") {

        dataSend = {

            orderByParam,
            type

        }

    }

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementStats.php',
        data: dataSend, 
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            if(data !== "") {

                document.getElementById('tableauContainer').innerHTML = data;

            }

        },
        
        error: function(xhr, textStatus, error){

            myFunctions.showError(tableauContainer, xhr);
            
        }

    });

}

// all users list results functions 

function getAllResultsUser(user) {

    let evalsContainer = document.getElementById('userAutoevals');
    let auditsContainer = document.getElementById('userAudits');

    if(user == "") {

        evalsContainer.style.opacity = 0;
        evalsContainer.innerHTML = "";
        auditsContainer.style.opacity = 0;
        auditsContainer.innerHTML = "";

    }

    if(user !== "") {
        
        myFunctions.showLoading(evalsContainer);
        myFunctions.showLoading(auditsContainer);
        
        getEvals = true;

        dataSend = {
            getEvals,
            user
        }
    
        $.ajax({

            type: 'POST',
            url: 'contentGenerators/userEvalAndAuditsList.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                evalsContainer.style.opacity = 0;

                setTimeout(function() {

                    evalsContainer.innerHTML = data;
                    evalsContainer.style.opacity = 1;

                }, 500)
    
            },
    
            error: function(xhr, textStatus, error){
    
                myFunctions.showError(evalsContainer, xhr);
                
            }
    
        });

        getAudits = true;

        dataSend = {
            getAudits,
            user
        }

        $.ajax({

            type: 'POST',
            url: 'contentGenerators/userEvalAndAuditsList.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                auditsContainer.style.opacity = 0;

                setTimeout(function() {

                    auditsContainer.innerHTML = data;
                    auditsContainer.style.opacity = 1;

                }, 500)
    
            },
    
            error: function(xhr, textStatus, error){
    
                myFunctions.showError(auditsContainer, xhr);
                
            }
    
        });

    }

}

function generateModifyingAuditTool(auditType) {

    let modifyingToolContainer = document.getElementById('modifyingToolContainer');

    if(auditType == "") {

        modifyingToolContainer.innerHTML = "";
        modifyingToolContainer.style.opacity = 0;
        modifyingToolContainer.style.display = 'none';
        return;

    }

    myFunctions.showLoading(modifyingToolContainer);

    let modificationAudit = true;

    let dataSend = {

        modificationAudit,
        auditType

    };

    $.ajax({

        type: 'POST',
        url: 'contentGenerators/auditModificatorGenerator.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            modifyingToolContainer.style.opacity = 0;

            setTimeout(function() {

                modifyingToolContainer.style.display = "block";
                modifyingToolContainer.innerHTML = data;
                modifyingToolContainer.style.opacity = 1;

            }, 500)

        },

        error: function(xhr, textStatus, error){

            myFunctions.showError(modifyingToolContainer, xhr);
            
        }

    });

}