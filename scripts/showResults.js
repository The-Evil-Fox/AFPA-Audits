function showChart(autoeval_number, date) {
    
    $(document).ready(function() {
        
        let evalNumber = autoeval_number;

        showLoading(contentWindow);

        $.ajax({

            type: 'POST',
            url: 'contentGenerators/myResultsGenerator.php',
            data: { 
                'evalNumber' : evalNumber 
            }, 
            dataType: 'JSON',
            success: function(dataGraphique) {

                myFunctions.checkAuthentifiedUser(dataGraphique);

                contentWindow.innerHTML = "<div id='graphique-resultats'></div><div id='dateGraphique'>Effectuée le "+date+".</div><div id='tableauContainer'></div>";

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

                $.ajax({

                    type: 'POST',
                    url: 'contentGenerators/myResultsGenerator.php',
                    data: {
                        'getNonCompliances': true,
                        'evalNumber' : evalNumber 
                    }, 
                    dataType: 'text',
                    success: function(dataTableau) {
        
                        myFunctions.checkAuthentifiedUser(dataTableau);
        
                        if(dataTableau !== "") {
        
                            setTimeout(() => {
        
                                document.getElementById('tableauContainer').style.display = "block";
                                document.getElementById('tableauContainer').innerHTML = dataTableau;
        
                            }, 1500);
        
                        }
        
                    },
                    
                    error: function(xhr, textStatus, error){
        
                        myFunctions.showError(contentWindow, xhr);
                        
                    }
        
                });

            },
            error: function(xhr, textStatus, error){

                myFunctions.showError(contentWindow, xhr);
                
            }

        });

    });

}