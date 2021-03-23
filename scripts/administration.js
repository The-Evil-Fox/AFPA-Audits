// Administration content generator

function showAdministration(category) {
    
    $(document).ready(function() {

        showLoading(contentWindow);

        $.ajax({

            type: 'POST',
            url: 'traitements/administrationGenerator.php',
            data: category,
            dataType: 'text',
            success: function(data) {

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

                alert(error);
                alert(xhr);
                alert(textStatus);
                
            }

        });

    });

}

// Edit autoeval functions

function showUserResultEval(userID, eval, userName, userFirstName, date) {

    userData = { 
        userID,
        eval 
    };

    contentWindow.innerHTML = "";
    contentWindow.innerHTML = "<img class='contentLoadingImage' src='assets/loading2.gif'>";

    $.ajax({

        type: 'POST',
        url: 'traitements/getUserResultEval.php',
        data: userData,
        dataType: 'JSON',
        success: function(datagraphique) {

            if(datagraphique == "Acces refusé ! Veuillez vous connectez !") {

                window.location.replace('index.php');
                return;
                
            }

            content.innerHTML = "<div id='dateGraphique'>le "+date+"</div><div id='graphique-resultats'></div><div id='tableauContainer'></div>";

            createChart("graphique-resultats", "Résultats de l'autoévaluation de " + userName + " " + userFirstName, datagraphique);


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
            'userID': userID
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

        error: function(xhr, textStatus, error){

            alert(error);
            alert(xhr);
            alert(textStatus);
            
        }

    });

}

function showUserResultAudit(userID, audit, userName, userFirstName, date, centre) {

    userData = { 
        userID,
        audit 
    };

    $.ajax({

        type: 'POST',
        url: 'traitements/getUserResultAudit.php',
        data: userData,
        dataType: 'JSON',
        success: function(dataGraphique) {

            content.innerHTML = "<div id='dateGraphique'>Effectué au centre de " + centre + " le "+date+"</div><div id='graphique-resultats'></div><div id='tableauContainer'></div>";

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
        
        }

    });

    $.ajax({

        type: 'POST',
        url: 'traitements/getUserResultAudit.php',
        data: {
            'getNonCompliances': true,
            'audit' : audit,
            'userID': userID
        },
        dataType: 'text',
        success: function(dataTableau) {

            if(dataTableau !== "") {

                setTimeout(() => {

                    document.getElementById('tableauContainer').style.display = "block";
                    document.getElementById('tableauContainer').innerHTML = dataTableau;
    
                }, 750);

            }
        
        },

        error: function(xhr, textStatus, error){

            alert(error);
            alert(xhr);
            alert(textStatus);
            
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

function addQuestionQuestionnaire(questionnaire) {

    newQuestion = document.getElementById('newQuestion').value;
    categorieQuestion = document.getElementById('categorie').value;

    if(questionnaire == "audit") {

        preuvesQuestion = document.getElementById('preuvesQuestion').value;

    }

    if(newQuestion == "") {

        alert('Veuillez insérer votre question !');
        return;

    }

    if(categorieQuestion == "") {

        alert('Veuillez sélectionner la catégorie de la question');
        return;

    }

    if(questionnaire == "audit" && preuvesQuestion == "") {

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
            preuvesQuestion
        };

    }

    if(questionnaire == "autoevaluation") {

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAutoeval.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                alert("La question a bien été ajoutée !");

            },

            error: function(xhr, textStatus, error) {

                alert(error);
                alert(xhr);
                alert(textStatus);

            }

        });

        $.ajax({

            type: 'POST',
            url: 'traitements/administrationGenerator.php',
            data: "autoEvalution",
            dataType: 'text',
            success: function(data) {
    
                setTimeout(() => {
    
                    contentWindow.innerHTML = data;
    
                }, 500);
    
            },
    
            error: function(xhr, textStatus, error) {
    
                alert(error);
                alert(xhr);
                alert(textStatus);
                
            }
    
        });

    } else if(questionnaire == "audit") {

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAudit.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                alert("La question a bien été ajoutée !");

            },

            error: function(xhr, textStatus, error) {

                alert(error);
                alert(xhr);
                alert(textStatus);

            }

        });

        $.ajax({

            type: 'POST',
            url: 'traitements/administrationGenerator.php',
            data: "audit",
            dataType: 'text',
            success: function(data) {
    
                setTimeout(() => {
    
                    contentWindow.innerHTML = data;
    
                }, 500);
    
            },
    
            error: function(xhr, textStatus, error) {
    
                alert(error);
                alert(xhr);
                alert(textStatus);
                
            }
    
        });

    }

}


function updateStatusRemoveQuestionQuestionnaire(questionnaire, button, questionID) {


    let operation = button.value;

    let questionStatus = document.getElementById('questionStatus'+questionID);

    dataSend = {
        operation,
        questionID
    };

    if(questionnaire == "autoeval") {

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAutoeval.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                if(operation == "delete") {

                    $.ajax({

                        type: 'POST',
                        url: 'traitements/administrationGenerator.php',
                        data: "autoEvalution",
                        dataType: 'text',
                        success: function(data) {
                
                            setTimeout(() => {
                
                                contentWindow.innerHTML = data;
                
                            }, 500);
                
                        },
                
                        error: function(xhr, textStatus, error) {
                
                            alert(error);
                            alert(xhr);
                            alert(textStatus);
                            
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

                alert(error);
                alert(xhr);
                alert(textStatus);

            }

        });

    } else if(questionnaire == "audit") {

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAdministrationAudit.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                if(operation == "delete") {

                    $.ajax({

                        type: 'POST',
                        url: 'traitements/administrationGenerator.php',
                        data: "audit",
                        dataType: 'text',
                        success: function(data) {
                
                            setTimeout(() => {
                
                                contentWindow.innerHTML = data;
                
                            }, 500);
                
                        },
                
                        error: function(xhr, textStatus, error) {
                
                            alert(error);
                            alert(xhr);
                            alert(textStatus);
                            
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

                alert(error);
                alert(xhr);
                alert(textStatus);

            }

        });

    }
}

function updateQuestionQuestionnaire(questionnaire, questionID) {

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

        operation = "edit";

        dataSend = {
            operation,
            questionID,
            updatedQuestion
        };

        if(questionnaire == "autoeval") {

            $.ajax({

                type: 'POST',
                url: 'traitements/traitementAdministrationAutoeval.php',
                data: dataSend,
                dataType: 'text',
                success: function(data) {

                    if(data == "La question a bien été éditée !") {
                    
                        question.innerHTML = updatedQuestion;
                        return;
                    
                    }

                },

                error: function(xhr, textStatus, error) {

                    alert(error);
                    alert(xhr);
                    alert(textStatus);

                }

            });

        } else if(questionnaire == "audit") {

            $.ajax({

                type: 'POST',
                url: 'traitements/traitementAdministrationAudit.php',
                data: dataSend,
                dataType: 'text',
                success: function(data) {

                    if(data == "La question a bien été éditée !") {
                    
                        question.innerHTML = updatedQuestion;
                        return;
                    
                    }

                },

                error: function(xhr, textStatus, error) {

                    alert(error);
                    alert(xhr);
                    alert(textStatus);

                }

            });

        }

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

                if(data == "Acces refusé ! Veuillez vous connectez !") {

                    alert('something strange is happening :)');
                    
                }

                if(data.includes("Erreur:")) {
                    
                    alert(data);
                    document.getElementById('addDocument').value = "";
                    
                    
                } else {

                refreshDocuments();

                }
    
            },

            error: function(xhr, textStatus, error){

                alert(error);
                alert(xhr);
                alert(textStatus);
                
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

            refreshDocuments();

        },

        error: function(xhr, textStatus, error){

            alert(error);
            alert(xhr);
            alert(textStatus);
            
        }

    });

}

function refreshDocuments() {

    showLoading(contentWindow);

    $.ajax({

        type: 'POST',
        url: 'traitements/administrationGenerator.php',
        data: "documents",
        dataType: 'text',
        success: function(data) {

            contentWindow.innerHTML = data;

        },

        error: function(xhr, textStatus, error){

            alert(error);
            alert(xhr);
            alert(textStatus);
            
        }

    });

}

// global stats function

function showStats() {

    $(document).ready(function() {

        showLoading(contentWindow);

        let getStats = true;

        dataSend = {

            getStats
            
        }

        $.ajax({

            type: 'POST',
            url: 'traitements/getGlobalStats.php',
            data: dataSend,
            dataType: 'JSON',
            success: function(data) {

                if(data == "Acces refusé ! Veuillez vous connectez !") {

                    window.location.replace('index.php');
                    return;
                    
                }

                if($('#mobileNavDropdown').attr('class').indexOf('active') > -1) {

                    $('#mobileNavDropdown').removeClass('active');

                }

                contentWindow.className = "content";

                contentWindow.style.opacity = 0;

                setTimeout(function() {

                    contentWindow.innerHTML = "<div id='graphique-resultats'></div>";

                    createChart("graphique-resultats", "Statistiques globales des audits", data);

                    contentWindow.style.opacity = 1;

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

            },
            error: function(xhr, textStatus, error){

                alert(error);

            }

        });

        // $.ajax({

        //     type: 'POST',
        //     url: 'traitements/chartGenerator.php',
        //     data: {
        //         'getNonCompliances': true,
        //         'evalNumber' : evalNumber 
        //     }, 
        //     dataType: 'text',
        //     success: function(data) {

        //         if(data == "Acces refusé ! Veuillez vous connectez !") {

        //             window.location.replace('index.php');
        //             return;
                    
        //         }

        //         if(data !== "") {

        //             setTimeout(() => {

        //                 document.getElementById('tableau-graphique').style.display = "block";
        //                 document.getElementById('tableau-graphique').innerHTML = data;

        //             }, 250);

        //         }

        //     },
            
        //     error: function(xhr, textStatus, error){

        //         alert(error);
        //         alert(xhr);
        //         alert(textStatus);
                
        //     }

        // });

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
        
        showLoading(evalsContainer);
        showLoading(auditsContainer);
        
        getEvals = true;

        dataSend = {
            getEvals,
            user
        }
    
        $.ajax({

            type: 'POST',
            url: 'traitements/getUserEvalAndAuditsList.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                evalsContainer.style.opacity = 0;

                setTimeout(function() {

                    evalsContainer.innerHTML = data;
                    evalsContainer.style.opacity = 1;

                }, 500)
    
            },
    
            error: function(xhr, textStatus, error){
    
                alert(error);
                alert(xhr);
                alert(textStatus);
                
            }
    
        });

        getAudits = true;

        dataSend = {
            getAudits,
            user
        }

        $.ajax({

            type: 'POST',
            url: 'traitements/getUserEvalAndAuditsList.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {

                auditsContainer.style.opacity = 0;

                setTimeout(function() {

                    auditsContainer.innerHTML = data;
                    auditsContainer.style.opacity = 1;

                }, 500)
    
            },
    
            error: function(xhr, textStatus, error){
    
                alert(error);
                alert(xhr);
                alert(textStatus);
                
            }
    
        });

    }

}