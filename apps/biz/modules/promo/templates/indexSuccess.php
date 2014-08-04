<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu',array('isActive'=>array('promo'=>true)))?>
    <div id="promos-container" class="main-canvas">
        <div class="list-titles">
            <div class="list-title"><p>Nombre</p></div>
            <div class="list-title title-big"><p>Descripción</p></div>
            <div class="list-title title-small"><p>Visitas desde</p></div>
            <div class="list-title title-small"><p>Visitas hasta</p></div>
            <div class="list-title title-small"><p>Canje desde</p></div>
            <div class="list-title title-small"><p>Canje hasta</p></div>
            <div class="list-title title-medium last"><p>Acciones</p></div>
        </div>
        <?php foreach ($promos as $promo): ?>
            <div class="promo-row">
                <div class="promo-row-cell align-left"><a href="<?php echo url_for('promo_show', $promo) ?>"><b><?php echo $promo->getName() ?></b></a></div>
                <div class="promo-row-cell promo-row-cell-big align-left"><p><?php echo $promo->getDescription() ?></p></div>
                <div class="promo-row-cell promo-row-cell-small"><?php echo $promo->getDateTimeObject('starts_at')->format('d/m/Y') ?></div>
                <div class="promo-row-cell promo-row-cell-small"><?php echo $promo->getDateTimeObject('ends_at')->format('d/m/Y') ?></div>
                <div class="promo-row-cell promo-row-cell-small"><?php echo $promo->getDateTimeObject('begins_at')->format('d/m/Y') ?></div>
                <div class="promo-row-cell promo-row-cell-small"><?php echo $promo->getDateTimeObject('expires_at')->format('d/m/Y') ?></div>
                <div class="promo-row-cell promo-row-cell-medium last">
                    <a href="<?php echo url_for('promo_show', $promo) ?>">Ver Detalles</a>
                    <br>
                    <a href="<?php echo url_for('promo_list_coupon', $promo) ?>">Lista de cupones</a>
                    <br>
                    <a href="<?php echo url_for('promo_redeem_coupon_validation', $promo) ?>">Canjear Premio</a>
                    <br>
                    <a href="<?php echo url_for('qr_generator', $promo) ?>">Generador de códigos QR</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>