<?php use_helper('I18N') ?>
<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu',array('isActive'=>array('promo'=>true)))?>
    <div id="promos-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2><?php echo __('Detalles de la Promoción')?></h2>
        </div>
        <div id="promo-details-container" class="main-canvas-content">
            <div class="white-frame">
                <?php if ($sf_user->getGuardUser()->hasGroup('admin')): ?>
                    <div class="promo-detail">
                        <div class="promo-detail-title"><?php echo __('Afiliado').':'?></div>
                        <div class="promo-detail-info">
                            <a href="<?php echo url_for('affiliate_show', $promo->getAffiliate()) ?>"><?php echo $promo->getAffiliate() ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Nombre').':'?></div>
                    <div class="promo-detail-info"><?php echo $promo->getName() ?></div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Descripción').':'?></div>
                    <div class="promo-detail-info"><?php echo $promo->getDescription() ?></div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Miniatura').':'?></div>
                    <div class="promo-detail-info"><?php echo $promo->getThumb() ? '<img src="/uploads/'.$promo->getThumb().'"/>' : __('No asignada')?></div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Foto').':'?></div>
                    <div class="promo-detail-info"><?php echo $promo->getPhoto() ? '<img src="/uploads/'.$promo->getPhoto().'"/>' : __('No asignada')?></div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Periodo de Visitas').':'?></div>
                    <div class="promo-detail-info">
                        <?php echo __('Desde').' '.$promo->getDateTimeObject('starts_at')->format('d/m/Y').' '.__('hasta').' '.$promo->getDateTimeObject('ends_at')->format('d/m/Y') ?>
                    </div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Periodo de Canje').':'?></div>
                    <div class="promo-detail-info">
                        <?php echo __('Desde').' '.$promo->getDateTimeObject('begins_at')->format('d/m/Y').' '.__('hasta').' '.$promo->getDateTimeObject('expires_at')->format('d/m/Y') ?>
                    </div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Premios').':'?></div>
                    <div class="promo-detail-info">
                        <?php $i = 1; foreach ($promo->getPrizes() as $key => $prize): ?>
                            <div class="promo-prize-row">
                                <div class="promo-prize-row-num"><?php echo $i; $i++ ?></div>
                                <div class="promo-prize-details">
                                    <div class="promo-prize-detail-title"><?php echo __('Imagen').':'?></div>
                                    <div class="promo-prize-detail-info">
                                        <?php echo $prize->getThumb() ? '<img src="/uploads/'.$prize->getThumb().'"/>' : __('No asignada')?>
                                    </div>
                                    <div class="promo-prize-detail-title"><?php echo __('Premio').':'?></div>
                                    <div class="promo-prize-detail-info"><?php echo $prize->getPrize(); ?></div>
                                    <div class="promo-prize-detail-title"><?php echo __('Visitas requeridos').':'?></div>
                                    <div class="promo-prize-detail-info"><?php echo $prize->getThreshold(); ?></div>
                                    <div class="promo-prize-detail-title"><?php echo __('Premios disponibles').':'?></div>
                                    <div class="promo-prize-detail-info"><?php echo $prize->getStock(); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Condiciones').':'?></div>
                    <div class="promo-detail-info">
                        <ul>
                            <?php foreach ($promo->getTerms() as $key => $term): ?>
                                <li><?php echo $term->getTerm(); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Fecha de creación').':'?></div>
                    <div class="promo-detail-info">
                    <?php echo $promo->getDateTimeObject('created_at')->format('d/m/Y - g:i:s a') ?>
                    </div>
                </div>
                <div class="promo-detail">
                    <div class="promo-detail-title"><?php echo __('Ultima actualización').':'?></div>
                    <div class="promo-detail-info">
                    <?php echo $promo->getDateTimeObject('updated_at')->format('d/m/Y - g:i:s a') ?>
                    </div>
                </div>
            </div>  
            <div class="main-canvas-content-footer">
                <a href="<?php echo url_for('promo/index') ?>"><?php echo __('Listado de Promociones')?></a>
            </div>
        </div>
    </div>
</div>