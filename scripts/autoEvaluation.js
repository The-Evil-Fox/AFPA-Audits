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

shortcutsControls = (e) => {

    keyPressed = e.which || e.keyCode;

    questionsNbr = document.getElementById('questionsNumber').value;

    if(keyPressed === 37) {
        
        if(page == 1) {

            return false;
    
        }

        autoEvalPrevious(page);

    }
    
    if(keyPressed === 39) {
        
        if(page == questionsNbr) {

            return false;
    
        }
        
        autoEvalNext(page);

    }

}

addControls();

function addControls() {

    document.body.addEventListener('keydown', shortcutsControls);

}

function removeControls() {

    document.body.removeEventListener('keydown', shortcutsControls);

}

function showTextArea(textAreaId, mustBeDisplayed) {

    let questionTextArea = document.getElementById(textAreaId);

    if(mustBeDisplayed == true) {

        questionTextArea.style.display = "block";

    } else {

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

// function sendAutoEval() {

//     $(document).ready(function() {

//         let messageWindow = document.getElementById('message');
//         let buttonSend = document.getElementById('autoeval-button-send');
//         let answers = new Array();

//         $("input:checkbox[name*=questions]:checked").each(function(){

//             answers.push($(this).val());
            
//         });

//         let questions = new Array();

//         var els = document.getElementsByName("questionNumber[]");

//         for (var i = 0; i < els.length; i++) {

//             questions.push(els[i].value);

//         }

//         let reasons = new Array();

//         $('textarea').each(function() {

//             if($(this).val() != "") {

//                 reasons.push({id: $(this).attr('id'), reason: $(this).val()});

//             }

//         })

//         if(answers.length > questions.length) {

//             messageWindow.style.display = "flex";
//             messageWindow.style.color = "#FF781B";
//             messageWindow.innerHTML = "<span class='phrase'>Une ou plusieurs questions ont reçu plusieurs réponses !</span>";
//             // console.log("(vérification) nombre réponses: " + answers.length + "nombre questions :" + questions.length);
//             buttonSend.style.display = "none";
//             setTimeout(() => {
//                 messageWindow.style.display = "none";
//                 messageWindow.innerHTML = "";
//                 buttonSend.innerHTML = "Renvoyer mon auto-évaluation";
//                 buttonSend.style.display = "block";
//             }, 2500);
//             return;

//         }

//         if(answers.length < questions.length) {

//             messageWindow.style.display = "flex";
//             messageWindow.style.color = "#FF781B";
//             messageWindow.innerHTML = "<span class='phrase'>Une ou plusieurs questions n'ont pas reçu de réponses !</span>";
//             // console.log("(vérification) nombre réponses: " + answers.length + "nombre questions :" + questions.length);
//             buttonSend.style.display = "none";
//             setTimeout(() => {
//                 messageWindow.style.display = "none";
//                 messageWindow.innerHTML = "";
//                 buttonSend.innerHTML = "Renvoyer mon auto-évaluation";
//                 buttonSend.style.display = "block";
//             }, 2500);
//             return;

//         }

//         $.ajax({

//             type: 'POST',
//             url: 'traitements/traitementAutoeval.php',
//             data: {Reponses:answers, Questions:questions, Raisons:reasons},
//             dataType: 'text',
//             success: function(data) {
                
//                 buttonSend.style.display = "none";
//                 messageWindow.style.display = "flex";
//                 messageWindow.style.color = "green";
//                 messageWindow.innerHTML = data;

//                 // setTimeout(() => {
//                 //     messageWindow.style.display = "none";
//                 //     messageWindow.innerHTML = "";
//                 // }, 2500);
            
//             }

//         });

//     });

// }