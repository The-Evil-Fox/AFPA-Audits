function createChart(container, title, myData) {

    CanvasJS.addColorSet("redGreen",
        [//colorSet Array
        "#35B5A0", // Compliance
        "#F4700E", // Non compliance
        "#A8A8A8", // Not available
        "#EDCF18"  // Not currently available
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
        
        toolTip: {
        
            backgroundColor: "black",
            fontColor: "#FFFFFF",
            content: "{label}: {y} question(s)",
        
        },
        
        data: [{
        
            type: "doughnut",
            startAngle: 268,
            showInLegend: true,
            legendText: "{label}",
            indexLabelFontSize: 12,
            indexLabel: "#percent%",
            highlightEnabled: true,
            explodeOnClick: true,
            dataPoints: myData,
            indexLabelPlacement: "outside",
            indexLabelWrap: false,
        
        }],
        
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

    /* 
       This is what renders a chart
       Above are the global style parameters.
       But a chart can also have special style parameters passed directly
       from the processing data pages
    */
   
    chart.render();

}