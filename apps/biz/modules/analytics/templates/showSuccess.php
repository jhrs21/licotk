<?php use_helper('sdInteractiveChart'); ?>
<?php use_helper('I18N') ?>
<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('analytics' => true))) ?>
    <div id="analytics-container" class="main-canvas">
        <a id="activator">Opciones avanzadas (+)</a>
        <div id="top" class="white-frame" style="display: none;">
            <form action="#" method="post">
                <div id="date-filter">
                    <?php echo $form ?>
                    <input id="filter-button" type="submit" value="Filtrar" />
                    <a href="<?php echo url_for('analytics')?>">Reiniciar</a>
                </div>
            </form>
        </div>
        <div id="left" class="white-frame">
            <div class="analytics-header">
                <div class="analytics-title-full">
                    <p><?php echo __('Estadísticas para') . ': <b>' . $affiliate . '</b>' ?></p>
                </div>
                <div id="nuevosfans-semanal" class="analytics-title">
                    <p><?php echo __('Afiliados esta semana') . ': <b>' . $newFans . '</b>' ?></p>
                </div>
                <div id="nuevosfans-total" class="analytics-title">
                    <p><?php echo __('Afiliados Totales') . ': <b>' . $totalFans . '</b>' ?></p>
                </div>
                <div id="tags-total" class="analytics-title">
                    <p><?php echo __('Visitas Totales') . ': <b>' . $totalTickets . '</b>' ?></p>
                </div>
            </div>

            <div id="gender-analitycs">
                <div class="analytics-title">
                    <p><?php echo __('¿Cuál es la edad promedio de mis afiliados?') ?></p>
                </div>
                <div id="edad-hombres" class="gender-number male">
                    <?php echo $edadPromH ?>
                </div>
                <div id="edad-mujeres" class="gender-number female">
                    <?php echo $edadPromM ?>
                </div>
                <div id="gender-percentage">
                    <div id="gender-percentage-male" class="gender-number male">
                        <?php echo $porcentajeH . "%" ?>
                    </div>
                    <div id="gender-image"></div>
                    <div id="gender-percentage-female" class="gender-number female">
                        <?php echo $porcentajeM . "%" ?>
                    </div>
                </div>
                <div id="frecuencia-hombres" class="gender-number male">
                    <?php echo $frecuenciaH ?>
                </div>
                <div id="frecuencia-mujeres" class="gender-number female">
                    <?php echo $frecuenciaM ?>
                </div>
                <div class="analytics-title">
                    <p><?php echo __('Número de visitas promedio de mis afiliados en el último trimestre') ?></p>
                </div>
            </div>
            <!-- GRAFICOS DE GOOGLE ANALYTICS -->
            <?php
            $agesChart = InteractiveChart::newColumnChart();
            $agesChart->setWidthAndHeight($width2, $height2);
            $agesChart->setDataColors(array('#1765AF', '#FFD900'));
            $agesChart->setOption('title', '¿Qué edad tienen mis afiliados?');
            $agesChart->setVerticalAxisTitle('Personas');
            $agesChart->inlineGraph($data, $label, $type);
            $agesChart->render();
            ?>
            <div id="ColumnChartAges" class=""></div>
            <?php
            $weekdayChart = InteractiveChart::newColumnChart();
            $weekdayChart->setWidthAndHeight($width3, $height3);
            $weekdayChart->setDataColors(array('#1765AF', '#FFD900'));
            $weekdayChart->setOption('title', '¿Que días vienen mis afiliados?');
            $weekdayChart->setVerticalAxisTitle('Personas');
            $weekdayChart->inlineGraph($dataWeekday, $labelWeekday, $type3);
            $weekdayChart->render();
            ?>
            <div id="ColumnChartWeekday" class=""></div>
            <?php
            $hourChart = InteractiveChart::newColumnChart();
            $hourChart->setWidthAndHeight($width4, $height4);
            $hourChart->setDataColors(array('#1765AF', '#FFD900'));
            $hourChart->setOption('title', $title4);
            $hourChart->setVerticalAxisTitle('Visitas');
            $hourChart->inlineGraph($dataHour, $labelHour, $type4);
            $hourChart->render();
            ?>
            <div id="ColumnChartHour" class=""></div>
            <?php
            $cardChart = InteractiveChart::newColumnChart();
            $cardChart->setWidthAndHeight($width5, $height5);
            $cardChart->setDataColors(array('#1765AF', '#FFD900'));
            $cardChart->setOption('title', $title5);
            $cardChart->setVerticalAxisTitle('Premios');
            $cardChart->inlineGraph($dataCard, $labelCard, $type5);
            $cardChart->render();
            ?>
            <div id="ColumnChartCard" class=""></div>
            <?php
            $xChart = InteractiveChart::newColumnChart();
            $xChart->setWidthAndHeight($widthX, $heightX);
            $xChart->setDataColors(array('#1765AF', '#FFD900'));
            $xChart->setOption('title', $titleX);
            $xChart->setVerticalAxisTitle('Usuarios');
            $xChart->inlineGraph($dataX, $labelX, $typeX);
            $xChart->render();
            ?>
            <div id="ColumnChartX" class=""></div>
        </div>
        <div id="right">
            <div class="feedback-area white-frame barside">
                <div class="barside-title">
                    <?php echo __('Opiniones de tus afiliados') ?>
                </div>
                <div class="count-feedback">
                    <!-- GRAFICO DE GOOGLE CHARTS: PieChart -->
                    <?php
                    $feedbackChart = InteractiveChart::newPieChart();
                    $feedbackChart->setWidthAndHeight(164, 180);
                    $feedbackChart->setLegendPosition('none');
                    $feedbackChart->setOption('chartArea', '{width:144,height:200,top:10,left:10}');
                    $feedbackChart->setDataColors(array('#8CC63F', '#FFC612', '#FF3333'));
                    $feedbackChart->inlineGraph(array($goodFeeds, $regularFeeds, $badFeeds), array('Positivo', 'Neutral', 'Negativo'), 'feedbackChart');
                    $feedbackChart->render();
                    ?>
                    <div id="feedbackChart"></div>
                    <div class="total-feedback"><?php echo __('Opiniones recibidas') . ': ' . $feedbacksCount ?></div>
                </div>
                <div id="last-comments">
                    <?php if ($lastComments->count() == 0): ?>
                    <?php else: ?>
                        <?php foreach ($lastComments as $comment): ?>
                            <div class="<?php echo $comment->getValoration() < 1 ? 'speech bad-speech' : ($comment->getValoration() > 1 ? 'speech' : 'speech good-speech' ); ?>">
                                <p><?php echo $comment->getMessage() ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="main-canvas-content-footer"><a href="<?php echo url_for('all_feedbacks') ?>">Todos los comentarios</a></div>
            </div>
            <?php if ($assets->count() > 1): ?>
                <div class="white-frame barside">
                    <div class="barside-title">
                        <?php echo __('Tus Establecimientos') ?>
                    </div>
                    <?php foreach ($assets as $asset): ?>
                        <?php echo link_to($asset, 'analytics_asset', array('alpha_id' => $asset->getAlphaId())) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    var daysShort = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
    var daysMin = ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

    $("#activator").click(function () {
        $("#top").slideToggle();
    });  

    $(document).ready(function(){        
        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            monthNames: months,
            monthNamesShort: monthsShort,
            dayNames: days,
            dayNamesShort: daysShort,
            dayNamesMin: daysMin,
            dateFormat: 'dd/mm/yy',
            yearRange: 'c-100:c'
        });
    })
    
    function realizaProceso(valorCaja1, valorCaja2){
        var parametros = {
                "valorCaja1" : valorCaja1,
                "valorCaja2" : valorCaja2
        };
        $.ajax({
                data:  parametros,
                url:   'ejemplo_ajax_proceso.php',
                type:  'post',
                beforeSend: function () {
                        $("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
                        $("#resultado").html(response);
                }
        });
}
</script>
