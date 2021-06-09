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

    } else if(questionnaire == "adduser") {

        let formContainer = document.getElementById('addUserFormContainer');
        let button = document.getElementById('addUserButton');

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
    
    let newQuestion = document.getElementById('newQuestion').value;

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

        dataSend = {

            newQuestion,
            categorieQuestion,
            evidenceQuestion,
            auditType

        };

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

    question.innerHTML = `<input class="questionnaires-input-edit" type="text" id="input`+questionID+`" value="`+questionText+`">
    <span class="edit-notification">Appuyez sur ECHAP ou cliquez en dehors du champ pour annuler.</span>`;

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

        updatedQuestion = myFunctions.escapeHtml(updatedQuestion);

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

    if(auditType == 2) {

        evidence.innerHTML = `<input class="questionnaires-input-edit" spellcheck="false" type="text" id="input`+questionID+`" value="`+evidenceText+`">
        <span class="edit-notification">Veuillez séparer chaque preuve par un point et ne pas utiliser de doubles quotes (").<br>Appuyez sur ECHAP ou cliquez en dehors du champ pour annuler.</span>`;

    } else {

        evidence.innerHTML = `<input class="questionnaires-input-edit" type="text" id="input`+questionID+`" value="`+evidenceText+`">
        <span class="edit-notification">Appuyez sur ECHAP ou cliquez en dehors du champ pour annuler.</span>`;
    
    }

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

        updatedEvidence = myFunctions.escapeHtml(updatedEvidence);

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

function editUser(editedUserID) {

    let userInfosContainer = document.getElementById('userModifications');

    if(editedUserID == "") {

        userInfosContainer.innerHTML = "";
        return;

    }


    let getEditedUserInfo = true;

    dataSend = {

        getEditedUserInfo,
        editedUserID

    }

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementUserModification.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            userInfosContainer.style.opacity = 0;

            setTimeout(function() {

                userInfosContainer.style.display = "block";
                userInfosContainer.innerHTML = data;
                userInfosContainer.style.opacity = 1;

            }, 500)

        },

        error: function(xhr, textStatus, error){

            myFunctions.showError(userInfosContainer, xhr);
            
        }

    });

}

function updateEmail(editedUserID) {

    let email = document.getElementById('user-email-'+editedUserID);

    emailText = email.innerHTML;

    email.innerHTML = "<input class='questionnaires-input-edit' type='email' id='input"+editedUserID+"' value='"+emailText+"'><span class='edit-notification'>Appuyez sur ECHAP ou cliquez en dehors du champ pour annuler.</span>";

    emailInput = document.getElementById('input'+editedUserID);

    emailInput.focus();

    function cancelModification() {

        email.innerHTML = emailText;
        return false;

    }

    enterDetect = (e) => {

        keyPressed = e.which || e.keyCode;
        
        if(keyPressed === 13) {
            
            sendUpdatedEmail(emailInput.value);

        }

        if(keyPressed === 27) {

            cancelModification();

        }
    
    }

    $('#input'+editedUserID).on('focusout', function() {

        cancelModification();

    });

    email.addEventListener('keydown', enterDetect);

    function sendUpdatedEmail(updatedEmail) {

        operation = "editEmail";

        dataSend = {

            operation,
            editedUserID,
            updatedEmail

        };

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementUserModification.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                if(data == "L'adresse email a bien été modifiée !") {
                
                    email.innerHTML = updatedEmail;
                    return;
                
                }

            },

            error: function(xhr, textStatus, error) {

                myFunctions.showError(document.getElementById('userModifications'), xhr);

            }

        });

    }

}

function updateLocalisation(editedUserID) {

    let localisation = document.getElementById('user-localisation-'+editedUserID);

    localisationText = localisation.innerHTML;

    getLocalisations = true;

    dataSend = {

        getLocalisations,
        localisationText,
        editedUserID

    }

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementUserModification.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            if(data !== "") {
            
                localisation.innerHTML = data;
                document.getElementById('updateLocalisationButton').removeAttribute('onclick');
            
            } else {

                return;

            }

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(document.getElementById('userModifications'), xhr);

        }

    });
    
}

function editLocalisation(editedUserID, newLocalisationID) {

    operation = "editLocalisation";

    dataSend = {

        operation,
        editedUserID,
        newLocalisationID

    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementUserModification.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            if(data == "La localisation a bien été modifiée !") {

                let localisation = document.getElementById('user-localisation-'+editedUserID);
                let newLocalisation = $('#changeLocalisation option:selected').text();
            
                localisation.innerHTML = newLocalisation;
                document.getElementById('updateLocalisationButton').setAttribute('onclick', 'updateLocalisation('+editedUserID+')');

                return;
            
            }

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(document.getElementById('userModifications'), xhr);

        }

    });

}

function updateRole(editedUserID, role) {

    let roleContainer = document.getElementById('user-role-'+editedUserID);

    roleText = roleContainer.innerHTML;

    if(role == 1) {

        roleContainer.innerHTML = 
            `<select onchange="editRole(`+editedUserID+`, this.value)" id="changeRole">
                <option value="1" selected>Utilisateur</option>
                <option value="2">Auditeur</option>
                <option value="4">Administrateur</option>
                <option value="14">Superadministrateur</option>
            </select>`
        ;

    } else if(role == 2) {

        roleContainer.innerHTML = 
            `<select onchange="editRole(`+editedUserID+`, this.value)" id="changeRole">
                <option value="1">Utilisateur</option>
                <option value="2" selected>Auditeur</option>
                <option value="4">Administrateur</option>
                <option value="14">Superadministrateur</option>
            </select>`
        ;

    } else if(role == 4) {

        roleContainer.innerHTML = 
            `<select onchange="editRole(`+editedUserID+`, this.value)" id="changeRole">
                <option value="1">Utilisateur</option>
                <option value="2">Auditeur</option>
                <option value="4" selected>Administrateur</option>
                <option value="14">Superadministrateur</option>
            </select>`
        ;

    } else if(role == 14 ) {

        roleContainer.innerHTML = 
            `<select onchange="editRole(`+editedUserID+`, this.value)" id="changeRole">
                <option value="1">Utilisateur</option>
                <option value="2">Auditeur</option>
                <option value="4">Administrateur</option>
                <option value="14" selected>Superadministrateur</option>
            </select>`
        ;

    }

    document.getElementById('updateRoleButton').removeAttribute('onclick');

}

function editRole(editedUserID, newRole) {

    operation = "editRole";

    dataSend = {

        operation,
        editedUserID,
        newRole

    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementUserModification.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            if(data == "Le role a bien été modifié !") {

                let roleContainer = document.getElementById('user-role-'+editedUserID);
                let newLocalisation = $('#changeRole option:selected').text();
            
                roleContainer.innerHTML = newLocalisation;
                document.getElementById('updateRoleButton').setAttribute('onclick', 'updateRole('+editedUserID+', '+newRole+')');

                return;
            
            }

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(document.getElementById('userModifications'), xhr);

        }

    });

}

function updateStatus(operation, editedUserID) {

    let button = document.getElementById('updateStatus');
    
    dataSend = {

        operation,
        editedUserID

    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementUserModification.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            myFunctions.checkAuthentifiedUser(data);

            if(data == "Opération réussie !") {

                if(operation == "disable") {

                    button.value = "enable";
                    button.className = "greenButton";
                    button.innerHTML = "<i class='fas fa-toggle-off'></i> Activer";

                } else if(operation == "enable") {

                    button.value = "disable";
                    button.className = "redButton";
                    button.innerHTML = "<i class='fas fa-toggle-on'></i> Désactiver";

                }

                return;
            
            }

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(document.getElementById('userModifications'), xhr);

        }

    });

}

function deleteBugReport(bugReportID) {

    operation = "deleteBugReport";

    dataSend = {

        operation,
        bugReportID

    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementBugReport.php',
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

function addUser() {

    let usernameinput = document.getElementById('username');
    let userfirstnameinput = document.getElementById('userfirstname');
    let useremailinput = document.getElementById('useremail');
    let userlocalisationinput = document.getElementById('userlocalisation');
    let userroleinput = document.getElementById('userrole');
    let userPasswordinput = document.getElementById('userpassword');

    let messageWindow = document.getElementById('message');

    if(usernameinput.value == "") {

        messageWindow.innerHTML = "Veuillez insérer le prénom de l'utilisateur !";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    if(userfirstnameinput.value == "") {

        messageWindow.innerHTML = "Veuillez insérer le nom de l'utilisateur !";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    if(useremailinput.value == "") {

        messageWindow.innerHTML = "Veuillez insérer l'adresse e-mail de l'utilisateur !";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    if(!myFunctions.validateEmail(useremailinput.value)) {

        messageWindow.innerHTML = "Veuillez insérer une adresse e-mail valide !";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    if(userlocalisationinput.value == "") {

        messageWindow.innerHTML = "Veuillez sélectionner la localisation de l'utilisateur !";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    if(userroleinput.value == "") {

        messageWindow.innerHTML = "Veuillez sélectionner le role de l'utilisateur !";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    if(userPasswordinput.value == "") {

        messageWindow.innerHTML = "Veuillez insérer le mot de passe de l'utilisateur";
        messageWindow.style.display = "flex";
        setTimeout(() => {

            messageWindow.style.display = "none";
            messageWindow.innerHTML = "";

        }, 2500);
        return false;

    }

    var sha256 = function sha256(ascii) {
        
        function rightRotate(value, amount) {
            
            return (value>>>amount) | (value<<(32 - amount));
            
        }
        
        var mathPow = Math.pow;
        var maxWord = mathPow(2, 32);
        var lengthProperty = 'length';
        var i, j; // Used as a counter across the whole file
        var result = '';
    
        var words = [];
        var asciiBitLength = ascii[lengthProperty]*8;
        
        //* caching results is optional - remove/add slash from front of this line to toggle
        // Initial hash value: first 32 bits of the fractional parts of the square roots of the first 8 primes
        // (we actually calculate the first 64, but extra values are just ignored)
        var hash = sha256.h = sha256.h || [];
        // Round constants: first 32 bits of the fractional parts of the cube roots of the first 64 primes
        var k = sha256.k = sha256.k || [];
        var primeCounter = k[lengthProperty];
        /*/
        var hash = [], k = [];
        var primeCounter = 0;
        //*/
    
        var isComposite = {};
        for (var candidate = 2; primeCounter < 64; candidate++) {
            
            if (!isComposite[candidate]) {
                
                for (i = 0; i < 313; i += candidate) {
                    isComposite[i] = candidate;
                }
                hash[primeCounter] = (mathPow(candidate, .5)*maxWord)|0;
                k[primeCounter++] = (mathPow(candidate, 1/3)*maxWord)|0;
                
            }
            
        }
        
        ascii += '\x80'; // Append Ƈ' bit (plus zero padding)
        while (ascii[lengthProperty]%64 - 56) ascii += '\x00'; // More zero padding
        for (i = 0; i < ascii[lengthProperty]; i++) {
            
            j = ascii.charCodeAt(i);
            if (j>>8) return; // ASCII check: only accept characters in range 0-255
            words[i>>2] |= j << ((3 - i)%4)*8;
            
        }
        words[words[lengthProperty]] = ((asciiBitLength/maxWord)|0);
        words[words[lengthProperty]] = (asciiBitLength);
        
        // process each chunk
        for (j = 0; j < words[lengthProperty];) {
            
            var w = words.slice(j, j += 16); // The message is expanded into 64 words as part of the iteration
            var oldHash = hash;
            // This is now the undefinedworking hash", often labelled as variables a...g
            // (we have to truncate as well, otherwise extra entries at the end accumulate
            hash = hash.slice(0, 8);
            
            for (i = 0; i < 64; i++) {
                
                var i2 = i + j;
                // Expand the message into 64 words
                // Used below if 
                var w15 = w[i - 15], w2 = w[i - 2];
    
                // Iterate
                var a = hash[0], e = hash[4];
                var temp1 = hash[7]
                    + (rightRotate(e, 6) ^ rightRotate(e, 11) ^ rightRotate(e, 25)) // S1
                    + ((e&hash[5])^((~e)&hash[6])) // ch
                    + k[i]
                    // Expand the message schedule if needed
                    + (w[i] = (i < 16) ? w[i] : (
                            w[i - 16]
                            + (rightRotate(w15, 7) ^ rightRotate(w15, 18) ^ (w15>>>3)) // s0
                            + w[i - 7]
                            + (rightRotate(w2, 17) ^ rightRotate(w2, 19) ^ (w2>>>10)) // s1
                        )|0
                    );
                // This is only used once, so *could* be moved below, but it only saves 4 bytes and makes things unreadble
                var temp2 = (rightRotate(a, 2) ^ rightRotate(a, 13) ^ rightRotate(a, 22)) // S0
                    + ((a&hash[1])^(a&hash[2])^(hash[1]&hash[2])); // maj
                
                hash = [(temp1 + temp2)|0].concat(hash); // We don't bother trimming off the extra ones, they're harmless as long as we're truncating when we do the slice()
                hash[4] = (hash[4] + temp1)|0;
                
            }
            
            for (i = 0; i < 8; i++) {
                
                hash[i] = (hash[i] + oldHash[i])|0;
            }
            
        }
        
        for (i = 0; i < 8; i++) {
            
            for (j = 3; j + 1; j--) {
                
                var b = (hash[i]>>(j*8))&255;
                result += ((b < 16) ? 0 : '') + b.toString(16);
                
            }
        }
        
        // Retourne le hash
        return result;
        
    };

    let username = document.getElementById('username').value;
    let userfirstname = document.getElementById('userfirstname').value;
    let useremail = document.getElementById('useremail').value;
    let userlocalisation = document.getElementById('userlocalisation').value;
    let userrole = document.getElementById('userrole').value;
    let userPassword = document.getElementById('userpassword').value;
    
    // Create a empty variable wich will hold the encrypted password
    let password;

    // Insert the hash into the variable
    password = sha256(userPassword);

    operation = "addUser";

    dataSend = {

        operation,
        username,
        userfirstname,
        useremail,
        userlocalisation,
        userrole,
        password

    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementUserModification.php',
        data: dataSend,
        dataType: 'text',
        success: function(data) {

            if(data == "L'utilisateur a bien été ajouté !") {

                usernameinput.value = "";
                userfirstnameinput.value = "";
                useremailinput.value = "";
                userlocalisationinput.value = "";
                userroleinput.value = "";
                userPasswordinput.value = "";

            }

            alert(data);

        },

        error: function(xhr, textStatus, error) {

            myFunctions.showError(contentWindow, xhr);

        }

    });    

}