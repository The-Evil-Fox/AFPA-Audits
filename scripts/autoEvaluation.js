page = 1;

function showTip(helperid) {

    let helper = document.getElementById(helperid);

    if(helper.style.display == "block") {

        helper.style.display = "none";

    } else {

        helper.style.display = "block";

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

function autoEvalPrevious(partNumber) {

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

function autoEvalNext(partNumber) {

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

    myAnswer = {
        evalNumber,
        question,
        answer
    };

    $.ajax({

        type: 'POST',
        url: 'traitements/traitementAutoeval.php',
        data: myAnswer,
        dataType: 'text',
        success: function(data) {

            if(textArea.value !== "") {

                textArea.value = "";
                if(textArea.classList.contains("visible")) {

                    textArea.className = "";

                }

            }

        }

    });

}

function addReason(evalNumber, question, reason) {

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
        
        }

    });

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

                setTimeout(() => {

                    document.getElementById('tableau-nonconformites').innerHTML = data;

                }, 250);

            },
            error: function(xhr, textStatus, error){
                alert(error);
            }

        });

    });

}