<?php use_helper('sdInteractiveChart'); ?>
<?php use_helper('I18N') ?>
<?php use_javascript('jquery-1.7.1.min.js') ?>
<div id="top" class="white-frame">
    <form action="#" method="post">
        <div id="date-filter">
            <?php echo $form ?>
            <input id="filter-button" type="submit" value="Filtrar" />
        </div>
    </form>
</div>
<a href="<?php echo url_for('detailsAffiliate', array("affiliate_id" => $affiliate->getId())) ?>">Atrás</a>
<div class="analytics-content span-24">
    <div class="analytics-text span-24">
        <div class="analytics-header span-7">
            <p><?php echo __('Estadísticas para') . ': <b>' . $assets[0] . '</b>'  ?></p>
            <p><?php echo __('Nuevos Fans esta semana') . ': <b>' . $newFans . '</b>' ?></p>
            <p><?php echo __('Fans Totales') . ': <b>' . $totalFans . '</b>' ?></p>
            <p><?php echo __('Tags Totales') . ': <b>' . $totalTickets . '</b>' ?></p>
        </div>
        <div class="gender-analitycs span-7">
            <p><?php echo __('Edad promedio de hombres: ') . $edadPromH ?></p>
            <p><?php echo __('Edad promedio de mujeres: ') . $edadPromM ?></p>
            <p><?php echo __('Porcentaje de clientes hombres: ') . $porcentajeH . "%" ?></p>
            <p><?php echo __('Porcentaje de clientes mujeres: ') . $porcentajeM . "%" ?></p>
            <p><?php echo __('Frecuencia de clientes hombres: ') . $frecuenciaH ?></p>
            <p><?php echo __('Frecuencia de clientes mujeres: ') . $frecuenciaM ?></p>
        </div>
        <div class="feedback-area span-10 last">
            <div class="barside-title">
                <?php echo __('Opiniones de tus Fans') ?>
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
            <div class="main-canvas-content-footer"><a href="<?php echo url_for('feedbackAsset', array("affiliate_id" => $affiliate->getId(), "asset_id" => $asset->getId())) ?>">Todos los comentarios</a></div>
        </div>
    </div>
    <div class="analytics-charts span-24">
        <!-- GRAFICOS DE GOOGLE ANALYTICS -->
        <?php
        $agesChart = InteractiveChart::newColumnChart();
        $agesChart->setWidthAndHeight($width2, $height2);
        $agesChart->setDataColors(array('#1765AF', '#A84D9D'));
        $agesChart->setOption('title', '¿Qué edad tienen mis fans?');
        $agesChart->setVerticalAxisTitle('Personas');
        $agesChart->inlineGraph($data, $label, $type);
        $agesChart->render();
        ?>
        <div id="ColumnChartAges" class="span-12"></div>
        <?php
        $weekdayChart = InteractiveChart::newColumnChart();
        $weekdayChart->setWidthAndHeight($width3, $height3);
        $weekdayChart->setDataColors(array('#1765AF', '#A84D9D'));
        $weekdayChart->setOption('title', '¿Que días vienen mis fans?');
        $weekdayChart->setVerticalAxisTitle('Personas');
        $weekdayChart->inlineGraph($dataWeekday, $labelWeekday, $type3);
        $weekdayChart->render();
        ?>
        <div id="ColumnChartWeekday" class="span-12 last"></div>
        <?php
        $hourChart = InteractiveChart::newColumnChart();
        $hourChart->setWidthAndHeight($width4, $height4);
        $hourChart->setDataColors(array('#1765AF', '#A84D9D'));
        $hourChart->setOption('title', $title4);
        $hourChart->setVerticalAxisTitle('Tags');
        $hourChart->inlineGraph($dataHour, $labelHour, $type4);
        $hourChart->render();
        ?>
        <div id="ColumnChartHour" class="span-12"></div>
        <?php
        $cardChart = InteractiveChart::newColumnChart();
        $cardChart->setWidthAndHeight($width5, $height5);
        $cardChart->setDataColors(array('#1765AF', '#A84D9D'));
        $cardChart->setOption('title', $title5);
        $cardChart->setVerticalAxisTitle('Premios');
        $cardChart->inlineGraph($dataCard, $labelCard, $type5);
        $cardChart->render();
        ?>
        <div id="ColumnChartCard" class="span-12 last"></div>
    </div>
</div>
<script type="text/javascript">
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    var daysShort = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
    var daysMin = ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa']; 

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
</script>