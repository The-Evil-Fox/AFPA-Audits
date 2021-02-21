function showChart(evaldate, autoeval_number) {
    
    $(document).ready(function() {

        let contentWindow = document.getElementById('content');

        let date = evaldate;

        let evalNumber = autoeval_number;

        $.ajax({

            type: 'POST',
            url: 'traitements/chartGenerator.php',
            data: { 
                'evalNumber' : evalNumber 
            }, 
            dataType: 'JSON',
            success: function(data) {

                contentWindow.innerHTML = "<div id='resultat-autoevaluation'></div>";

                createChart("resultat-autoevaluation", "Autoévaluation du " +date, data);

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

    });

}