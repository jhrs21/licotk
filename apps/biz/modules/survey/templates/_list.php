<?php 
    $assetsIds = $sf_data->getRaw('assetsIds');
    $assets = '';
    $count = count($assetsIds);
    $i = 0;
    foreach ($assetsIds as $key => $assetId) {
        $assets = $assets . 'asset_id['.$key.']='.$assetId;
        $i++;
        if ($i < $count) {
            $assets = $assets . '&';
        }
    }
?>
<?php if($pager->getResults()->count() > 0): ?>
    <div class="list-titles">
        <div class="list-title" style="width:50%">Encuesta</div>
        <div class="list-title" style="width:12%">Veces Tomada</div>
        <div class="list-title" style="width:35%">Acciones</div>
    </div>
    <?php foreach ($pager->getResults() as $survey): ?>
        <div class="list-row">
            <div class="list-cell list-cell-important" style="width:50%">
                <a href="<?php echo url_for('survey_show_results',$survey) ?>"><?php echo $survey->getName() ?></a>
            </div>
            <div class="list-cell" style="width:10%"><?php echo $survey->getTimesApplied($assetsIds) ?></p></div>
            <div class="list-cell" style="width:33%">
                <ul class="inline">
                    <li><a href="<?php echo url_for('survey_show_results',$survey).'?'.$assets ?>">Ver Resultados</a></li>
                    <li><a href="<?php echo url_for('survey_download_data',$survey).'?'.$assets ?>">Descargar Resultados</a></li>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if ($pager->haveToPaginate()): ?>
        <div class="pagination-container">
            <a title="Primera Página" href="<?php echo url_for('survey') ?>?page=1">
                << <!--<img src="/images/first.png" alt="First page" title="First page" />-->
            </a>

            <a title="Página Anterior" href="<?php echo url_for('survey') ?>?page=<?php echo $pager->getPreviousPage() ?>">
                < <!--<img src="/images/previous.png" alt="Previous page" title="Previous page" />-->
            </a>

            <?php foreach ($pager->getLinks() as $page): ?>
                <a class="<?php echo $page == $pager->getPage() ? 'current' : '' ?>" title="Página <?php echo $page ?>" 
                    href="<?php echo url_for('survey') ?>?page=<?php echo $page ?>">
                    <?php echo $page ?>
                </a>
            <?php endforeach; ?>

            <a title="Página Siguiente" href="<?php echo url_for('survey') ?>?page=<?php echo $pager->getNextPage() ?>">
                > <!--<img src="/images/next.png" alt="Next page" title="Next page" />-->
            </a>

            <a title="Última Página" href="<?php echo url_for('survey') ?>?page=<?php echo $pager->getLastPage() ?>">
                >> <!--<img src="/images/last.png" alt="Last page" title="Last page" />-->
            </a>
        </div>
    <?php endif; ?>
<?php else: ?>
<div class="main-canvas-title">
    <h2>No hay no se han encontrado encuestas.</h2>
</div>
<?php endif; ?>