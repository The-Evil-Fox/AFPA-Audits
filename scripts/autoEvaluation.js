function autoEvalPrevious(partNumber) {

    let content = document.getElementById('content');

    const questionId = "question";
    let questionNumber = partNumber;

    content.style.opacity = "0";

    document.getElementById(questionId+questionNumber).style.display = "none";

    questionNumber--;

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

        if(answers.length > questions.length) {

            messageWindow.style.display = "block";
            messageWindow.style.color = "#FF781B";
            messageWindow.innerHTML = "Une ou plusieurs questions ont reçu plusieurs réponses !";
            console.log("(vérification) nombre réponses: " + answers.length + "nombre questions :" + questions.length);
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

            messageWindow.style.display = "block";
            messageWindow.style.color = "#FF781B";
            messageWindow.innerHTML = "Une ou plusieurs questions ont pas reçu de réponses !";
            console.log("(vérification) nombre réponses: " + answers.length + "nombre questions :" + questions.length);
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
            data: {Reponses:answers, Questions:questions},
            dataType: 'text',
            success: function(data) {
                
                buttonSend.style.display = "none";
                messageWindow.style.display = "block";
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