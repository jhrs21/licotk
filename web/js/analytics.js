// This script depends on charts.js

var maleColor = "#009ddf", 
    femaleColor = "#a84c9e";

function buildColumnChart(chartData, chartParams, graphParams){
    AmCharts.ready(function() {
        n = Object.keys(graphParams).length;

        // SERIAL CHART
        chart = new AmCharts.AmSerialChart();
        chart.dataProvider = chartData;
        chart.categoryField = chartParams.categoryField;
        chart.plotAreaBorderAlpha = 0.2;

        // AXES
        // category
        var categoryAxis = chart.categoryAxis;
        categoryAxis.gridAlpha = 0.1;
        categoryAxis.axisAlpha = 0;
        categoryAxis.gridPosition = "start";

        // value
        var valueAxis = new AmCharts.ValueAxis();
        valueAxis.stackType = "regular";
        valueAxis.gridAlpha = 0.1;
        valueAxis.axisAlpha = 0;
        chart.addValueAxis(valueAxis);

        // GRAPHS
        // first graph
        for (var i = 0; i < n; i++){
            var graph = new AmCharts.AmGraph();
            graph.title = graphParams[i].title;
            graph.labelText = graphParams[i].labelText;
            graph.valueField = graphParams[i].valueField;
            graph.type = graphParams[i].type;
            graph.lineAlpha = 0;
            graph.fillAlphas = 1;
            graph.lineColor = graphParams[i].lineColor;
            graph.balloonText = graphParams[i].balloonText;
            chart.addGraph(graph);  
        }

        // LEGEND                  
        var legend = new AmCharts.AmLegend();
        legend.borderAlpha = 0.2;
        legend.valueWidth = 0;
        legend.horizontalGap = 10;
        chart.addLegend(legend);

        // WRITE
        chart.addTitle(chartParams.title);
        chart.write(chartParams.domId);
    });
}

function buildPieChart(chartData, chartParams, graphParams){
    AmCharts.ready(function() {
    // PIE CHART
    chart = new AmCharts.AmPieChart();
    chart.dataProvider = chartData;
    chart.titleField = graphParams.title;
    chart.valueField = graphParams.valueField;
    chart.outlineColor = "#FFFFFF";
    chart.outlineAlpha = 0.8;
    chart.outlineThickness = 2;
    chart.labelsEnabled = false;
    chart.colors = graphParams.colors;
    chart.balloonText = graphParams.balloonText;
    
    // LEGEND
    legend = new AmCharts.AmLegend();
    legend.align = "center";
    legend.markerType = "circle";
    chart.addLegend(legend);

    // WRITE
    chart.write(chartParams.domId);
});
}
