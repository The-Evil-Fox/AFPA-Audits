function showChart(autoeval_number, date) {
    
    $(document).ready(function() {
        
        let evalNumber = autoeval_number;

        showLoading(contentWindow);

        $.ajax({

            type: 'POST',
            url: 'traitements/chartGenerator.php',
            data: { 
                'evalNumber' : evalNumber 
            }, 
            dataType: 'JSON',
            success: function(dataGraphique) {

                if(dataGraphique == "Acces refusé ! Veuillez vous connectez !") {

                    window.location.replace('index.php');
                    return;
                    
                }

                contentWindow.innerHTML = "<div id='dateGraphique'>Le "+date+"</div><div id='graphique-resultats'></div><div id='tableauContainer'></div>";

                createChart("graphique-resultats", "Résultats de mon autoévaluation", dataGraphique);

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
            success: function(dataTableau) {

                if(dataTableau == "Acces refusé ! Veuillez vous connectez !") {

                    window.location.replace('index.php');
                    return;
                    
                }

                if(dataTableau !== "") {

                    setTimeout(() => {

                        document.getElementById('tableauContainer').style.display = "block";
                        document.getElementById('tableauContainer').innerHTML = dataTableau;

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