//
// This script depends on charts.js
//

var maleColor = "#009ddf", 
    femaleColor = "#a84c9e";

function insertChartStructure( items ) {
    var i;
    for ( i in items ) {
        if ( withOptions( items[ i ].type ) ) {
            var chartSeries = {};
                
            $.each( items[ i ].options, function( j, option ) {
                chartSeries[option.id] = {
                    label   : option.label,
                    male    : 0,
                    female  : 0,
                    total   : 0
                };
            } );
            
            chartSeries[ 'na-nc' ] = {
                    label   : 'No aplica/No contesta',
                    male    : 0,
                    female  : 0,
                    total   : 0
                };
                
            $.extend( items[ i ], { chartData: chartSeries } );
        } else if ( items[ i ].type.toLowerCase() === 'date') {
            var chartSeries = {};
            
            chartSeries[ 'na-nc' ] = {
                    label   : 'No aplica/No contesta',
                    male    : 0,
                    female  : 0,
                    total   : 0
                };
                
            $.extend( items[ i ], { chartData: chartSeries } );
        } else {
            $.extend( items[ i ], {
                answersData: {
                    answers : [],  
                    male    : 0, 
                    female  : 0,
                    total   : 0
                }
            } );
        }
    }
}
    
function withOptions( type ) {
    // WithOut Options Types
    var woot = [ "text", "textarea", "date" ];
        
    // If the item's type is in woot return false
    if ( $.inArray( type, woot ) > -1 ) {
        return false;
    }
        
    return true;
}
    
function isMultipleSelection( type ) {
    // Multiple Selection Types
    var mst = [ "multiple_selection" ];
        
    // If item's type is in mst return true
    if ( $.inArray( type, mst ) > -1 ) {
        return true;
    }
        
    return false;
}
    
function updateDemographics( data, users, demographics ) {
    users.push( data.user );
        
    if ( data.gender === "female" ) {
        demographics.totalFemale++;
    } else {
        demographics.totalMale++;
    }

    if ( typeof demographics.ages[ data.age ] == "undefined" ) {
        demographics.ages[ data.age ] = {
            age     : data.age,
            male    : 0, 
            female  : 0
        };
    }

    demographics.ages[ data.age ][ data.gender ]++;
}
    
function updateAssetsData( data, assets, assetsData ) {
    if ( $.inArray(data.asset, assets ) === -1 ){
        assets.push( data.asset );
        assetsData[ data.asset ] = {
            male    : 0, 
            female  : 0
        };
    }
        
    assetsData[ data.asset ][ data.gender ]++;
}
    
function updateItemsData( items, data ) {
    var it = data.item;
    if ( withOptions( items[ it ].type ) ) {
        var a,
        answers = [];

        if ( isMultipleSelection( items[ it ].type ) ) {
            answers = data.answer.split( ";" );
        } else {
            answers = data.answer.split( ";", 1 );
        }

        for ( a in answers ) {
            items[ it ].chartData[ answers[ a ] ][ data.gender ]++;
            items[ it ].chartData[ answers[ a ] ][ "total" ]++;
        }
    } else if ( items[ it ].type.toLowerCase() == 'date' ) {
        if ( $.inArray(data.answer, items[ it ].chartData ) === -1 ){
            items[ it ].chartData[ data.answer ] = {
                label   : data.answer,
                male    : 0,
                female  : 0,
                total   : 0
            }
        }
        
        items[ it ].chartData[ data.answer ][ data.gender ]++;
        items[ it ].chartData[ data.answer ][ "total" ]++;
    } else {
        items[ it ].answersData.answers.push( data.answer );
        items[ it ].answersData[ data.gender ]++;
        items[ it ].answersData[ "total" ]++;
    }
}
    
function processData( survey, data, users, assets, applications, demographics, assetsData ) {
    var d;
    for ( d in data ) {
        // this section handles the users demographics, applications count and assets data
        if ( $.inArray( data[ d ].application, applications ) === -1 ){
            applications.push( data[ d ].application );
            updateAssetsData( data[ d ], assets, assetsData );
                
            if ( $.inArray( data[ d ].user, users ) === -1 ){
                updateDemographics( data[ d ], users, demographics );
            }
        }
            
        // this section handles the results for each item in the survey
        updateItemsData( survey.items, data[ d ] );
    }
}

function getAgesSeries ( ages, mColor, fColor ) {
    var series = [];
        
    $.each( ages, function() {
        $.extend( this, {
            maleColor   : mColor, 
            femaleColor : fColor
        } );
        
        series.push( this );
    } );
        
    return series;
}

function getSeries ( item ) {
    var series = [];
        
    $.each( item.chartData, function( key, piece ){
        series.push( piece );
    } );
        
    return series;
}
    
function getPieChartParams ( item ) {
    var params = {
        dataProvider    : getSeries( item ),
        title           : "Item: \""+item.label+"\"",
        titleField      : "label",
        valueField      : "total"
    }
        
    return params;
}
    
function getColumnChartParams ( item ) {
    var chParams = {
        dataProvider    : getSeries( item ),
        title           : "Item: \""+item.label+"\"",
        categoryField   : "label"
    }
        
        
    var grParams = [
        {
            title               : "Masculino",
            labelText           : "[[value]]",
            valueField          : "male",
            type                : "column",
            balloonText         : "Opción '[[category]]': [[value]] ([[percents]]%)",
            lineColor           : maleColor
        },
        {
            title               : "Femenino",
            labelText           : "[[value]]",
            valueField          : "female",
            type                : "column",
            balloonText         : "Opción '[[category]]': [[value]] ([[percents]]%)",
            lineColor           : femaleColor
        }
    ];
        
    return {
        chartParams  : chParams,
        graphsParams : grParams
    };
}
    
function getItemsCharts ( survey ) {
    var charts = {};
        
    $.each(survey.items, function( key, item ){
        var ch = {
            chart: {}, 
            domid: "item_"+key
        };
        var params = {};
        
        if ( withOptions( item.type ) ) {                
            if ( isMultipleSelection( item.type ) ) {
                params = getColumnChartParams( item );
                ch.chart = generateStackedColumnChart( params.chartParams, params.graphsParams );
            } else {
                ch.chart = generate3DDonutChart( getPieChartParams( item ), {
                    markerType : "circle"
                } );
            }
        } else if ( item.type.toLowerCase() == 'date' ) {
            params = getColumnChartParams( item );
            ch.chart = generateStackedColumnChart( params.chartParams, params.graphsParams );
        } else {
            return true;
        }
        
        charts["item_"+key] = ch;
    });
        
    return charts;
}
    
function buildCharts( charts, data, survey, maleColor, femaleColor ){
    var users = [], 
        assets = [], 
        applications = [],
        assetsData = {},
        demographics = {
            totalMale: 0,
            totalFemale: 0,
            ages: {}
        };
    
    if ( maleColor == undefined ) {
        maleColor = "#009ddf";
    }
        
    if ( femaleColor == undefined ) {
        femaleColor = "#a84c9e";
    }
        
    insertChartStructure(survey.items);
    processData(survey, data, users, assets, applications, demographics, assetsData);
        
    var genderChartParams = {
        dataProvider    : [
            {
                gender: "Masculino", 
                quantity: demographics.totalMale, 
                color: maleColor
            },
            {
                gender: "Femenino", 
                quantity: demographics.totalFemale, 
                color: femaleColor
            }
        ],
        title           : "Usuarios por genero",
        titleField      : "gender",
        valueField      : "quantity",
        colorField      : "color"
    };
        
    $.extend( charts, { 
        gender_chart : { 
            chart : generate3DDonutChart( genderChartParams, {
                markerType : "circle"
            } ), 
            domid : "gender_chart" 
        } 
    });
        
    var agesChartParams = {
        dataProvider    : getAgesSeries( demographics.ages, maleColor, femaleColor ),
        categoryField   : "age",
        title           : "Usuarios agrupados por edades"
    };
        
    var agesGraphsParams = [
        {
            title               : "Masculino",
            labelText           : "[[percents]]%",
            valueField          : "male",
            type                : "column",
            balloonText         : "[[category]] años: [[value]] ([[percents]]%)",
            lineColor           : maleColor
        },
        {
            title               : "Femenino",
            labelText           : "[[percents]]%",
            valueField          : "female",
            type                : "column",
            balloonText         : "[[category]] años: [[value]] ([[percents]]%)",
            lineColor           : femaleColor
        }
    ];
        
    $.extend( charts, { 
        ages_chart : { 
            chart : generateStackedColumnChart( agesChartParams, agesGraphsParams ), 
            domid : "ages_chart" 
        } 
    });
        
    $.extend( charts, getItemsCharts( survey ) );
        
    $.each( survey.items, function ( key, item ){
        if ( withOptions( item.type ) ) {
            if ( $("#item_"+key).length === 0 ) {
                $( "#results-graphics" ).append("<div id='item_"+key+"' class='span-18 last' style='width: 100%; height: 300px;'></div>");
            }
        } else if ( item.type.toLowerCase() == 'date' ) {
            if ( $("#item_"+key).length === 0 ) {
                $( "#results-graphics" ).append("<div id='item_"+key+"' class='span-18 last' style='width: 100%; height: 300px;'></div>");
            }
        } else {
            
        }
    });
}