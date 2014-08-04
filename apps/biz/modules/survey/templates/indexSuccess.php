<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('survey' => true))) ?>
    <div id="prizes-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Listado de Encuestas</h2>
        </div>
        <div id="list_container">
            <?php include_partial('list', array('pager' => $pager, 'assetsIds' =>  $assetsIds)) ?>
        </div>
    </div>
</div>