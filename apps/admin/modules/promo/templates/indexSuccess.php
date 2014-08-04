<div id="main-container" class="span-24">
    <div id="container-inner-header" class="span-24">
        <a class="top-link" href="<?php echo url_for('promo/new') ?>">Nueva promoción</a>
    </div>
    <div class="promos-container span-24">
        <div class="titles-active span-24">
            <div id="affiliate-cell-title" class="span-3">Afiliado</div>
            <div id="name-cell-title" class="span-3">Nombre</div>
            <div id="description-cell-title" class="span-6">Descripción</div>
            <div id="start-cell-title" class="span-2">Tags desde</div>
            <div id="end-cell-title" class="span-2">Tags hasta</div>
            <div id="begin-cell-title" class="span-2">Canje desde</div>
            <div id="expires-cell-title" class="span-2">Canje hasta</div>
            <div id="option-cell-title" class="span-4 last">Acciones</div>
        </div>
        <?php foreach ($promos as $promo): ?>
            <div class="promo-row span-24">
                <div class="affiliate-cell span-3"><a href="#"><?php echo $promo->getAffiliate() ?></a></div>
                <div class="name-cell span-3"><a href="<?php echo url_for('promo_show', $promo) ?>"><?php echo $promo->getName() ?></a></div>
                <div class="description-cell span-6"><?php echo $promo->getDescription() ?></div>
                <div class="start-cell span-2"><?php echo $promo->getDateTimeObject('starts_at')->format('d/m/Y') ?></div>
                <div class="end-cell span-2"><?php echo $promo->getDateTimeObject('ends_at')->format('d/m/Y') ?></div>
                <div class="begin-cell span-2"><?php echo $promo->getDateTimeObject('begins_at')->format('d/m/Y') ?></div>
                <div class="expires-cell span-2"><?php echo $promo->getDateTimeObject('expires_at')->format('d/m/Y') ?></div>
                <div class="option-cell span-4 last">
                    <a href="<?php echo url_for('promo_show', $promo) ?>">Detalles</a>
                    <a href="<?php echo url_for('promo_edit', $promo) ?>">Editar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="container-inner-footer" class="span-24">
        <a class="bottom-link" href="<?php echo url_for('promo/new') ?>">Nueva promoción</a>
    </div>
</div>