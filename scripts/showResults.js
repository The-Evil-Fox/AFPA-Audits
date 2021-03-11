function showChart(evaldate, autoeval_number) {
    
    $(document).ready(function() {

        let contentWindow = document.getElementById('content');
        
        if(typeof(evaldate !== "undefined")) {
            
            date = evaldate;

        }
        
        let evalNumber = autoeval_number;

        $.ajax({

            type: 'POST',
            url: 'traitements/chartGenerator.php',
            data: { 
                'evalNumber' : evalNumber 
            }, 
            dataType: 'JSON',
            success: function(data) {

                if(data == "Acces refusé ! Veuillez vous connectez !") {

                    window.location.replace('index.php');
                    return;
                    
                }

                contentWindow.innerHTML = "<div id='resultats-autoevaluation'></div><div id='tableau-nonconformites'></div>";

                createChart("resultats-autoevaluation", "Autoévaluation du " + date, data);

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

        $.ajax({

            type: 'POST',
            url: 'traitements/chartGenerator.php',
            data: {
                'getNonCompliances': true,
                'evalNumber' : evalNumber 
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