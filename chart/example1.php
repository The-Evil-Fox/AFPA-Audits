<?php
 
$dataPoints = array(
	array("label"=> "Carrossiers automobiles", "y"=> 590, "indexLabelFontColor" => "#FFFFFF"),
	array("label"=> "Geomètres", "y"=> 261, "indexLabelFontColor" => "#FFFFFF"),
	array("label"=> "Régleurs", "y"=> 158, "indexLabelFontColor" => "#FFFFFF"),
    array("label"=> "Développeurs WEB", "y"=> 72, "indexLabelFontColor" => "#FFFFFF"),
	array("label"=> "Couvreurs", "y"=> 191, "indexLabelFontColor" => "#FFFFFF"),
	array("label"=> "Charpentier bois", "y"=> 573, "indexLabelFontColor" => "#FFFFFF"),
	array("label"=> "Peintres en carroserie", "y"=> 126, "indexLabelFontColor" => "#FFFFFF")
);
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="author" content="Steven Durieux">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AFPA: Formation professionnelle</title>
    <script>
        window.onload = function () {
        
            let chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                backgroundColor: "#0E3447",
                title: {
                    text: "Nombre d'apprenants actuel dans les formations",
                    fontColor: "#FFFFFF",
                    fontSize: 30,
                },
                /* subtitles: [{
                    text: "Currency Used: Thai Baht (฿)"
                }], */
                toolTip: {
                    backgroundColor: "rgba(0,0,0,0.5)",
                    fontColor: "#FFFFFF",
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    legendText: "{label}",
                    // indexLabelFontSize: 16,
                    // indexLabel: "{label} - #percent%",
                    highlightEnabled: true,
                    explodeOnClick: true,
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }],
                // axisX: {
                //     labelFontColor: "#FFFFFF", 
                // },
                // axisY: {
                //     labelFontColor: "#FFFFFF",
                // },
                legend: {
                    fontColor: "#FFFFFF",
                    horizontalAlign: "center",
                    maxHeight: 200,
                }
            });
            chart.render();

        }
</script>
</head>
<body style="background-color: #22242A;">
    <div id="chartContainer" style="height: 50vh; width: 90%; margin: 2.5% auto;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>

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

    </script>
</body>
