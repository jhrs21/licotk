<?php
$result = $sf_data->getRaw('result');
$assets = '';
$count = count($assetsIds);
$i = 0;
foreach ($assetsIds as $key => $assetId) {
    $assets = $assets . 'asset_id[' . $key . ']=' . $assetId;
    $i++;
    if ($i < $count) {
        $assets = $assets . '&';
    }
}
?>
<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('survey' => true))) ?>
    <div id="results-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Resultados</h2>
        </div>
        <div id="results-graphics" class="white-frame">
            <div id="times-applied" class=""></div>
            <div id="total-users" class=""></div>
            <div id="gender_chart" class="chart-container" style="width: 100%; height: 300px;"></div>
            <div id="ages_chart" class="chart-container" style="width: 100%; height: 300px;"></div>
        </div>
        <div id="results-filters" class="white-frame">
            <div class="title">Filtros</div>
            <div id="results-form-filter">
                <?php include_partial('surveyResultsFilter', array('form' => $form, 'survey' => $survey)) ?>
            </div>
            <div class="gray-separator"></div>
            <div id="download-survey-data">
                <a id="download-button" href="<?php echo url_for('survey_download_data', $survey) . '?' . $assets ?>">Descargar Datos</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo','Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    var daysShort = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
    var daysMin = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];
    
    function setDatePickers(){
        $( ".from_date" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            monthNames: months,
            monthNamesShort: monthsShort,
            dayNames: days,
            dayNamesShort: daysShort,
            dayNamesMin: daysMin,
            dateFormat: 'dd/mm/yy',
            numberOfMonths: 3,
            onClose: function( selectedDate ) {
                $(".to_date").datepicker( "option", "minDate", selectedDate );
            }
        });
        $( ".to_date" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            monthNames: months,
            monthNamesShort: monthsShort,
            dayNames: days,
            dayNamesShort: daysShort,
            dayNamesMin: daysMin,
            dateFormat: 'dd/mm/yy',
            numberOfMonths: 3,
            onClose: function( selectedDate ) {
                $(".from_date").datepicker( "option", "maxDate", selectedDate );
            }
        });
    }
    
    function validateregex(str) {
        var regx = /^[a-zA-ZáéíóúÁÉÍÓÚñÑü'.\s]*$/;
        return str.match(regx); 
    }

    $( document ).ready(function(){
        $( 'input:checkbox[name="survey_application_filters[asset_id][]"]' ).last().attr( 'data-bvalidator', 'required' ).attr( 'data-bvalidator-msg', 'Seleccione al menos un establecimiento' );        
        
        setDatePickers();
        
        $( '#survey-results-form-filter' ).bValidator();

        $( "#results-form-filter" ).on( "click", "#survey-results-form-filter input[type='submit']", function(event){
            
            event.preventDefault();
            
            $( "#survey-results-form-filter input[type='submit']" ).attr( 'disabled', 'disabled' );
            
            if( $( "#survey-results-form-filter").data( "bValidator" ).validate() ) {
                
                var $form = $('#survey-results-form-filter'),
                    data = $form.serialize(),
                    url = $form.attr( 'action' );
                
                $.post( url, data )
                    .done( function( result ) {
                        if ( result.success === 1 ) {
                            var charts = {};

                            buildCharts(
                                charts,
                                result.data,
                                result.survey
                            );

                            drawCharts( charts );
                        } else {
                            $( "#results-form-filter" ).html( result.html );

                            setDatePickers();
                            $( "input:checkbox[name='survey_application_filters[asset_id][]']" ).last().attr( 'data-bvalidator', 'required' ).attr( 'data-bvalidator-msg', 'Seleccione al menos un establecimiento' );        
                            $( "#survey-results-form-filter" ).bValidator();
                        }
                    })
                    .fail(function() {
                        alert( 'Ha ocurrido un problema de comunicación al intentar recuperar la información que solicitaste. Por favor, intenta más tarde nuevamente.' );
                    });

                //$('#survey-results-form-filter').submit();
                $( "input[type='submit']" ).removeAttr( "disabled" );
                return false;
            }
            else {
                $( "input[type='submit']" ).removeAttr( "disabled" );
                return false;
            }
        });
        
        var charts = {};
            
        buildCharts(
            charts,
            <?php echo $result['data']; ?>,
            <?php echo $result['survey']; ?>
        );
        
        drawCharts( charts );
    });
</script>