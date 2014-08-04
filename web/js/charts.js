function addChartTitle ( chart, title ) {
    chart.addLabel(0,0,title,"center",14,"#18b9e8",0,1,true);
    //addLabel(x, y, text, align, size, color, rotation, alpha, bold, url)
}

function addChartLegend ( chart, legendParams ) {
    var legendDefaults = {
        align               : "center",
        position            : "bottom",
        markerBorderAlpha   : 0
    }
        
    var legend = new AmCharts.AmLegend();
        
    $.extend( legend, legendDefaults );
        
    if ( typeof( legendParams ) != "undefined" ) {
        $.extend( legend, legendParams );
    } 
        
    chart.addLegend( legend );
}

function modifyCategoryAxis ( chart, categoryAxisParams ) {
    var categoryAxis = chart.categoryAxis;
        
    $.extend( categoryAxis, categoryAxisParams );
}
    
function addValueAxis ( chart, valueAxisParams ) {
    var valueAxisDefault = {
            gridAlpha : 0.2,
            axisAlpha : 0
        }
    
    var valueAxis = new AmCharts.ValueAxis();
        
    $.extend( valueAxis, valueAxisDefault, valueAxisParams );
        
    chart.addValueAxis( valueAxis );
}
    
function addGraph ( chart, graphParam ) {
    var graph = new AmCharts.AmGraph();
            
    $.extend( graph, graphParam );

    chart.addGraph( graph );
}
    
function generateSerialChart ( chartParams, categoryAxisParams, valueAxisParams, legendParams ) {
    var chart = new AmCharts.AmSerialChart();
        
    $.extend( chart, {  }, chartParams );
        
    if ( typeof( chartParams.title ) != "undefined" ) {
        chart.addTitle( chartParams.title, 16 );
    }
        
    if ( typeof( categoryAxisParams ) != "undefined" ) {
        modifyCategoryAxis( chart, categoryAxisParams );
    }
        
    if ( typeof( valueAxisParams ) != "undefined" ) {
        if ( $.isArray( valueAxisParams ) ) {
            $.each(valueAxisParams, function( key, params ){
                addValueAxis( chart, params );
            });
        } else {
            addValueAxis( chart, valueAxisParams );
        }
    }
        
    addChartLegend( chart, legendParams );
        
    return chart;
}
    
function generateColumnChart ( chartParams, graphsParams, categoryAxisParams, valueAxisParams, legendParams ) {
    var columnChartGraphDefaults = {
        fillAlphas  : 1,
        lineAlpha   : 1
    }
        
    var chart = generateSerialChart(chartParams, categoryAxisParams, valueAxisParams, legendParams);
        
    if ( $.isArray( graphsParams ) ) {
        $.each(graphsParams, function( key, params ){
            var settings = $.extend( {}, columnChartGraphDefaults, params );
            addGraph( chart, settings );
        });
    } else {
        var settings = $.extend( {}, columnChartGraphDefaults, params );
        addGraph( chart, settings );
    }
        
    return chart;
}
    
function generateStackedColumnChart ( chartParams, graphsParams, categoryAxisParams, valueAxisParams, legendParams ) {
    if ( typeof( valueAxisParams ) != "undefined" ) {
        $.extend( valueAxisParams, {stackType : "regular"} );
    } else {
        valueAxisParams = {stackType : "regular"};
    }
        
    var chart = generateColumnChart( chartParams, graphsParams, categoryAxisParams, valueAxisParams, legendParams )
        
    return chart;
}
    
function generate3DDonutChart ( donutParams, legendParams ) {
    var donutDefaults = {
        outlineColor        : "#FFFFFF",
        outlineAlpha        : 0.8,
        outlineThickness    : 2,
        sequencedAnimation  : true,
        startEffect         : "elastic",
        innerRadius         : "30%",    // this line makes the chart a donut
        // the following two lines makes the chart 3D
        depth3D             : 10,
        angle               : 15
    };
        
    var donut = new AmCharts.AmPieChart();
        
    $.extend( donut, donutDefaults, donutParams );
        
    if ( typeof( donutParams.title ) != "undefined" ) {
        donut.addTitle( donutParams.title, 16 );
    }
        
    addChartLegend( donut, legendParams );
        
    return donut;
}
    
function drawCharts ( charts ) {
    $.each(charts, function( key, ch ){
        ch.chart.write( ch.domid );
    });
}
    
function drawChart( chart, domid ) {
    chart.write( domid );
}