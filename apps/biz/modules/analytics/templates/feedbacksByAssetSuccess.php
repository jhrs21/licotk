<?php use_helper('sdInteractiveChart'); ?>
<?php use_helper('I18N') ?>
<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('analytics' => true))) ?>
    <div id="feedbacks-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Opinión de tus Fans</h2>
        </div>
        <?php if ($pager->count()) :?>
            <div class="options-container">
                <div class="form-filter-container filter">
                    <form action="<?php echo url_for('all_asset_feedbacks_post',$asset) ?>" method="post">
                        <label>Filtar por tipo de comentario:</label>
                        <select id="valoration_filter">
                            <option value="all" selected="selected">Todos</option>
                            <option value="2">Positivo</option>
                            <option value="1">Neutro</option>
                            <option value="0">Negativo</option>
                        </select>
                        <input type="submit" value="Filtrar" />
                        <img id="loader" src="/images/loader.gif" style="vertical-align: middle; display: none" />
                    </form>
                </div>
            </div>
            <div class="feedbacks">
                <?php include_partial('feedbacksList', array('pager' => $pager))?>
            </div>
        <?php else: ?>
            <div class="feedbacks main-canvas white-frame">
                <div class="main-canvas-content-title">
                    <h3>Aún no se han recibido comentarios sobre: <span><?php echo $asset; ?></span></h3>
                    <div></div>
                </div>
            </div>
        <?php endif; ?>
        <div class="white-frame feedback-barside barside">
            <div class="barside-title">
                <?php echo __('Opiniones de tus Fans')?>
            </div>
            <div class="count-feedback">
                <!-- GRAFICO DE GOOGLE CHARTS: PieChart -->
                <?php
                    $feedbackChart = InteractiveChart::newPieChart();
                    $feedbackChart->setWidthAndHeight(208,200);
                    $feedbackChart->setLegendPosition('none');
                    $feedbackChart->setOption('chartArea','{width:208,height:200,top:10,left:10}');
                    $feedbackChart->setDataColors(array('#8CC63F','#FFC612','#FF3333'));
                    $feedbackChart->inlineGraph(array($goodFeeds,$regularFeeds,$badFeeds), array('Positivo','Neutral','Negativo'), 'feedbackChart');
                    $feedbackChart->render();
                ?>
                <div id="feedbackChart"></div>
                <div class="total-feedback"><?php echo __('Opiniones recibidas').': '.$counter ?></div>
                <div class="main-canvas-content-footer">
                    <a href="<?php echo url_for('analytics') ?>">Volver a Estadísticas globales</a><br>
                    <a href="<?php echo url_for('analytics_asset',$asset) ?>">Volver a Estadísticas para <?php echo $asset?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">    
    $(document).ready(function(){
        $('.filter input[type="submit"]').hide();
        $('#valoration_filter').change(function(){
            $('#loader').show();
            $('.feedbacks').load(
                $('#valoration_filter').parents('form').attr('action'),
                { valoration: this.value },
                function() { jQuery('#loader').hide(); }
            );
        });
    });
</script>
