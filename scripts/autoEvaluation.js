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
        let answers = new Array();

        $("input:checkbox[name*=questions]:checked").each(function(){

            answers.push($(this).val());
            
        });

        let questions = new Array();

        var els = document.getElementsByName("questionNumber[]");

        for (var i = 0; i < els.length; i++) {

            questions.push(els[i].value);

        }

        $.ajax({

            type: 'POST',
            url: 'traitements/traitementAutoeval.php',
            data: {Reponses:answers, Questions:questions},
            dataType: 'text',
            success: function(data) {
            
                messageWindow.style.display = "block";
                messageWindow.innerHTML = data;
                // setTimeout(() => {
                //     messageWindow.style.display = "none";
                //     messageWindow.innerHTML = "";
                // }, 2500);
            

            }

        });

    });

}