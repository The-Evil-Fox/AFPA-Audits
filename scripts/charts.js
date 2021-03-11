function createChart(container, title, myData) {

    CanvasJS.addColorSet("redGreen",
        [//colorSet Array
        "#76CC63",
        "#F16137",
        "#2FD09F",
        "#565AB3"         
        ]
    );

    let chart = new CanvasJS.Chart(container, {

        colorSet: "redGreen",
        animationEnabled: true,
        exportEnabled: true,
        backgroundColor: "#6A6A6A",
        title: {
            text: title,
            fontColor: "#FFFFFF",
            fontSize: 20,
            fontWeight: "bold",
        },
        /* subtitles: [{
            text: "Currency Used: Thai Baht (฿)"
        }], */
        toolTip: {
            backgroundColor: "#FFFFFF",
            fontColor: "#000000",
        },
        data: [{
            type: "pie",
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
        // },
        axisY: {
            titleFontWeight: "bolder",
            labelFontColor: "#FFFFFF",
            labelFontWeight: "bolder"
        },
        legend: {
            fontColor: "#FFFFFF",
            fontWeight: "bolder",
            horizontalAlign: "center",
            maxHeight: 200,
        }
    });

    chart.render();

}