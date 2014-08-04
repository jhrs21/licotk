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
                    <p><?php echo __('Nuevos Fans esta semana') . ': <b>' . $newFans . '</b>' ?></p>
                </div>
                <div id="nuevosfans-total" class="analytics-title">
                    <p><?php echo __('Fans Totales') . ': <b>' . $totalFans . '</b>' ?></p>
                </div>
                <div id="tags-total" class="analytics-title">
                    <p><?php echo __('Visitas Totales') . ': <b>' . $totalTickets . '</b>' ?></p>
                </div>
            </div>

            <div id="gender-analitycs">
                <div class="analytics-title">
                    <p><?php echo __('¿Cuál es la edad promedio de mis Fans') ?>?</p>
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
                    <p><?php echo __('Número de visitas promedio de mis fans en el último trimestre') ?></p>
                </div>
            </div>
            <div id="ColumnChartAges" style="width : 100%; height: 350px;"></div>
            <div id="ColumnChartWeekday" style="width : 100%; height: 350px;"></div>
            <div id="ColumnChartHour" style="width : 100%; height: 350px;"></div>
            <div id="ColumnChartCard" style="width : 100%; height: 350px;"></div>
            <div id="ColumnChartTag" style="width : 100%; height: 350px;"></div>
        </div>
        <div id="right">
            <div class="feedback-area white-frame barside">
                <div class="barside-title">
                    <?php echo __('Opiniones de tus Fans') ?>
                </div>
                <div class="count-feedback">
                    <div id="feedbackChart" style="width: 100%; height: 160px;"></div>
                    <div class="total-feedback"><?php echo __('Opiniones recibidas') . ': ' . $feedbacksCount ?></div>
                </div>
                <div id="last-comments">
                    <?php if ($lastComments->count() == 0): ?>
                    <?php else: ?>
                        <?php foreach ($lastComments as $comment): ?>
                            <div class="<?php echo $comment->getValoration() < 1 ? 'speech bad-speech' : ($comment->getValoration() == 1 ? 'speech' : 'speech good-speech' ); ?>">
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

<?php echo javascript_include_tag('analytics') ?>
<script type="text/javascript">
   var chartData = <?php echo $sf_data->getRaw('ageAmChart'); ?>;
   var paramsChart = {categoryField : "ages", domId: "ColumnChartAges", title: "Usuarios por edades"};
   var paramsGraph = [ 
       { title : "Masculino", labelText : "[[percents]]%", valueField : "male", balloonText : "[[category]] años: [[value]] ([[percents]]%)", 
           lineColor : "#009ddf", type : "column"
       },
       { title : "Femenino", labelText : "[[percents]]%", valueField : "female", balloonText : "[[category]] años: [[value]] ([[percents]]%)",
           lineColor : "#a84c9e", type : "column"
       }
    ];
   buildColumnChart(chartData, paramsChart, paramsGraph);
   
   var chartData = <?php echo $sf_data->getRaw('weekdayAmChart'); ?>;
   var paramsChart = {categoryField : "days", domId: "ColumnChartWeekday", title: "Que dias vienen mis fans"};
   buildColumnChart(chartData, paramsChart, paramsGraph);
   
   var chartData = <?php echo $sf_data->getRaw('hourAmChart'); ?>;
   var paramsChart = {categoryField : "hour", domId: "ColumnChartHour", title: "A que hora vienen mis fans"};
   buildColumnChart(chartData, paramsChart, paramsGraph);
   
   var chartData = <?php echo $sf_data->getRaw('cardAmChart'); ?>;
   var paramsChart = {categoryField : "cards", domId: "ColumnChartCard", title: "Estado de mis premios"};
   var paramsGraph = [ 
       { title : "Tarjetas", labelText : "[[percents]]%", valueField : "card", balloonText : "[[category]] tarjetas: [[value]] ([[percents]]%)", 
           lineColor : "#009ddf", type : "column"
       }
    ];
   buildColumnChart(chartData, paramsChart, paramsGraph);
   
   var chartData = <?php echo $sf_data->getRaw('tagAmChart'); ?>;
   var paramsChart = {categoryField : "tags", domId: "ColumnChartTag", title: "Numero de visitas por usuario"};
   var paramsGraph = [ 
       { title : "Personas", labelText : "[[percents]]%", valueField : "total", balloonText : "[[category]] visitas: [[value]] ([[percents]]%)", 
           lineColor : "#009ddf", type : "column"
       }
    ];
   buildColumnChart(chartData, paramsChart, paramsGraph);
   
    var chartData = <?php echo $sf_data->getRaw('feedbackAmChart'); ?>;
    var paramsChart = {domId: "feedbackChart"};
    var paramsGraph = { 
            title : "type", labelText : "[[percents]]%", valueField : "feedbacks", colors : ["#FF3333","#FFC612","#8CC63F"], 
            balloonText : "[[value]] comentarios [[category]] <br> ([[percents]]%)"
        } ;
    buildPieChart(chartData,paramsChart,paramsGraph);
</script>