function createChart(container, title, myData) {

    CanvasJS.addColorSet("redGreen",
        [//colorSet Array
        "#35B5A0", // Conforme
        "#F4700E", // Non conforme
        "#A8A8A8", // Non applicable
        "#EDCF18"  // Non disponible actuellement
        ]
    );

    let chart = new CanvasJS.Chart(container, {

        colorSet: "redGreen",
        animationEnabled: true,
        exportEnabled: true,
        zoomEnabled: false,
        
        title: {
        
            text: title,
            fontColor: "#000000",
            fontSize: 20,
            margin: 55,
            fontWeight: "bold",
        
        },
        
        /* subtitles: [{
        
            text: "Currency Used: Thai Baht (฿)"
        
        }], */
        
        toolTip: {
        
            backgroundColor: "black",
            fontColor: "#FFFFFF",
            content: "{label}: {y} questions",
        
        },
        
        data: [{
        
            type: "doughnut",
            startAngle: 268,
            showInLegend: true,
            legendText: "{label}",
            indexLabelFontSize: 9,
            indexLabel: "{label} - #percent%",
            highlightEnabled: true,
            explodeOnClick: true,
            dataPoints: myData
        
        }],
        
        // axisX: {

        //     labelFontColor: "#FFFFFF", 

        //  },
        
        axisY: {

            titleFontWeight: "bolder",
            labelFontColor: "#000000",
            labelFontWeight: "bolder"
        
        },

        legend: {
        
            fontColor: "#000000",
            fontWeight: "bolder",
            horizontalAlign: "center",
            verticalAlign: "bottom",
            maxHeight: 200,
        
        }

    });

    chart.render();

}