page = 1;

function showTip(helperid) {

    let helper = document.getElementById(helperid);

    if(helper.style.display == "flex") {

        helper.style.display = "none";

    } else {

        helper.style.display = "flex";

    }

}

function startAudit() {

    let contentWindow = document.getElementById('content');
    let startContainer = document.getElementById('startContainer');

    selectauditedUser = document.getElementById('auditedUser');
    auditedUserID = selectauditedUser.value;

    if(auditedUserID.length !== 0) {

        startContainer.style.opacity = 0;
        startContainer.style.display = "none";

        createNewAudit = "createNeAudit";

        dataSend = {
            createNewAudit,
            auditedUserID
        }

        $.ajax({

            type: 'POST',
            url: 'traitements/createAudit.php',
            data: dataSend,
            dataType: 'text',
            success: function(data) {
                
                contentWindow.innerHTML = data;

            }

        });
    
    } else {

        alert("Veuillez selectionner un utilisateur à auditer !");
        return;
    }

}

function startEval() {

    let contentWindow = document.getElementById('content');
    let startContainer = document.getElementById('startContainer');

    startContainer.style.opacity = 0;
    startContainer.style.display = "none";

    $.ajax({

        type: 'POST',
        url: 'traitements/createAutoeval.php',
        data: "createNewEval",
        dataType: 'text',
        success: function(data) {
            
            contentWindow.innerHTML = data;

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

    textArea = document.getElementById(textAreaID);

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

        },

        error: function(xhr, textStatus, error){

            alert(error);
            alert(xhr);
            alert(textStatus);
            
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
    
            error: function(xhr, textStatus, error){
    
                alert(error);
                alert(xhr);
                alert(textStatus);
                
            }
    
        });

    }

}

function addReason(evalNumber, question, reason, textareaID) {

    if(reason.length !== 0) {

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

            error: function(xhr, textStatus, error){

                alert(error);
                alert(xhr);
                alert(textStatus);
                
            }

        });

    }

}

function sendEval(eval) {

    $(document).ready(function() {

        let content = document.getElementById('content');
        let messageWindow = document.getElementById('message');
        let buttonSend = document.getElementById('autoeval-button-send');
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

                content.innerHTML = "<div id='resultat-autoevaluation'></div><div id='tableau-nonconformites'></div>";

                createChart("resultat-autoevaluation", "Vos résultats", data);


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
                alert(xhr);
                alert(textStatus);
                
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
            success: function(data) {

                if(data == "Acces refusé ! Veuillez vous connectez !") {

                    window.location.replace('index.php');
                    return;
                    
                }

                if(data !== "") {

                    setTimeout(() => {

                        document.getElementById('tableau-nonconformites').style.display = "block";
                        document.getElementById('tableau-nonconformites').innerHTML = data;

                    }, 250);

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