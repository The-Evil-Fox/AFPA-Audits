function createChart(container, title, myData) {

    let chart = new CanvasJS.Chart(container, {

        animationEnabled: true,
        exportEnabled: true,
        backgroundColor: null,
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
            showInLegend: true,
            legendText: "{label}",
            // indexLabelFontSize: 16,
            // indexLabel: "{label} - #percent%",
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