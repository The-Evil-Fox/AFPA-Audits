function showTip(helperid) {

    let helper = document.getElementById(helperid);

    if(helper.style.display == "block") {

        helper.style.display = "none";

    } else {

        helper.style.display = "block";

    }
}

function addControls() {

    document.addEventListener('keydown', shortcutsControls);

}

function removeControls() {

    document.removeEventListener('keydown', shortcutsControls);

}

shortcutsControls = (e) => {

    keyPressed = e.which || e.keyCode;

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

function startEval() {

    let startContainer = document.getElementById('startContainer');
    let autoEval = document.getElementById('autoEvaluation');

    startContainer.style.opacity = 0;
    autoEval.style.opacity = 1;
    startContainer.style.display = "none";

    page = 1;

    addControls();
    autoEvalKeyboardControlsActive = true;

    questionsNbr = document.getElementById('questionsNumber').value;

}

function showTextArea(textAreaId) {

    let questionTextArea = document.getElementById(textAreaId);

    if(questionTextArea.style.display == "block") {

        questionTextArea.style.display = "none";

    } else {

        questionTextArea.style.display = "block";

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

function sendAutoEval() {

    $(document).ready(function() {

        let messageWindow = document.getElementById('message');
        let buttonSend = document.getElementById('autoeval-button-send');
        let answers = new Array();

        $("input:checkbox[name*=questions]:checked").each(function(){

            answers.push($(this).val());
            
        });

        let questions = new Array();

        var els = document.getElementsByName("questionNumber[]");

        for (var i = 0; i < els.length; i++) {

            questions.push(els[i].value);

        }

        let reasons = new Array();

        $('textarea').each(function() {

            if($(this).val() != "") {

                reasons.push({id: $(this).attr('id'), reason: $(this).val()});

            }

        })

        if(answers.length > questions.length) {

            messageWindow.style.display = "flex";
            messageWindow.style.color = "#FF781B";
            messageWindow.innerHTML = "<span class='phrase'>Une ou plusieurs questions ont reçu plusieurs réponses !</span>";
            // console.log("(vérification) nombre réponses: " + answers.length + "nombre questions :" + questions.length);
            buttonSend.style.display = "none";
            setTimeout(() => {
                messageWindow.style.display = "none";
                messageWindow.innerHTML = "";
                buttonSend.innerHTML = "Renvoyer mon auto-évaluation";
                buttonSend.style.display = "block";
            }, 2500);
            return;

        }

        if(answers.length < questions.length) {

            messageWindow.style.display = "flex";
            messageWindow.style.color = "#FF781B";
            messageWindow.innerHTML = "<span class='phrase'>Une ou plusieurs questions n'ont pas reçu de réponses !</span>";
            // console.log("(vérification) nombre réponses: " + answers.length + "nombre questions :" + questions.length);
            buttonSend.style.display = "none";
            setTimeout(() => {
                messageWindow.style.display = "none";
                messageWindow.innerHTML = "";
                buttonSend.innerHTML = "Renvoyer mon auto-évaluation";
                buttonSend.style.display = "block";
            }, 2500);
            return;

        }

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAutoeval.php',
            data: {Reponses:answers, Questions:questions, Raisons:reasons},
            dataType: 'text',
            success: function(data) {
                
                buttonSend.style.display = "none";
                messageWindow.style.display = "flex";
                messageWindow.style.color = "green";
                messageWindow.innerHTML = data;

                // setTimeout(() => {
                //     messageWindow.style.display = "none";
                //     messageWindow.innerHTML = "";
                // }, 2500);
            
            }

        });

    });

}