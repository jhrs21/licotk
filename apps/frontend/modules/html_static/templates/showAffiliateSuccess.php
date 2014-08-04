<?php use_helper('I18N') ?>
<?php slot('page_title', $affiliate->getName().' | Comercios Afiliados | LealTag - Mereces ser premiado') ?>
<?php slot('metas') ?>
    <meta content="" name="abstract"/>
    <meta content="<?php echo $affiliate->getDescription(); ?>" name="description"/>
    <meta content="" name="keywords"/>
    <meta content="" name="keyphrases"/>
    <meta content="index, follow" name="robots"/>
    <meta content="<?php echo url_for('donde_estamos_afiliado', $affiliate, true); ?>" property="og:url"/>
    <meta content="website" property="og:type"/>
    <meta content="LealTag" property="og:site_name"/>
    <meta content="http://www.lealtag.com/uploads/<?php echo $affiliate->getLogo(); ?>" property="og:image"/>
    <meta content="<?php echo $affiliate->getDescription(); ?>" property="og:description"/>
<?php end_slot() ?>
<div id="main-container" class="box_bottom_round box_shadow white-background many-canvas">
    <div class="main-canvas box_round gray-background waw-affiliate" itemscope itemtype="http://schema.org/LocalBusiness">
        <div class="waw-image box_round box_shadow_bottom">
            <img itemprop="image" src="/uploads/<?php echo $affiliate->getLogo(); ?>"/>
        </div>
        <div class="waw-affiliate-info box_round white-background darkgray">
            <div class="waw-affiliate-name" itemprop="name"><?php echo $affiliate->getName();?></div>
            <div class="separator darkgray-background"></div>
            <div class="waw-affiliate-content" itemprop="description"><?php echo $affiliate->getDescription(); ?></div>
        </div>
        <div class="waw-affiliate-program box_round white-background darkgray">
            <div class="waw-affiliate-program-description">
                <div class="waw-affiliate-title"><?php echo __('Descripción') ?></div>
                <div class="waw-affiliate-content">
                    <?php echo $program->getDescription() ?>
                </div>
            </div>
            <div class="waw-affiliate-program-dates">
                <div class="program-dates">
                    <div class="waw-affiliate-title"><?php echo __('Período para acumular Tags') ?></div>
                    <div class="waw-affiliate-content">
                        <?php echo __('Desde').': '.$program->getDateTimeObject('starts_at')->format('d/m/Y').' - '.__('Hasta').': '.$program->getDateTimeObject('ends_at')->format('d/m/Y'); ?>
                    </div>
                </div>
                <div class="program-dates">
                    <div class="waw-affiliate-title"><?php echo __('Período para canjear Premios') ?></div>
                    <div class="waw-affiliate-content">
                        <?php echo __('Desde').': '.$program->getDateTimeObject('begins_at')->format('d/m/Y').' - '.__('Hasta').': '.$program->getDateTimeObject('expires_at')->format('d/m/Y'); ?>
                    </div>
                </div>
            </div>
            <div class="waw-affiliate-program-prizes">
                <div class="waw-affiliate-title"><?php echo __('Premios') ?></div>
                <div class="waw-affiliate-content">
                    <ul>
                        <?php foreach ($program->getPrizes() as $prize) : ?>
                            <li>
                                <?php echo $prize->getThreshold()?> Tag(s): <?php echo $prize->getPrize()?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="waw-affiliate-program box_round white-background darkgray">
            <div class="waw-affiliate-title">
                <?php echo $program->getAssets()->getFirst()->getAssetType() == 'place' ?
                            __('Estableciminetos participantes') : __('Marcas participantes');?>
            </div>
            <div class="waw-affiliate-content">
                <ul>
                    <?php foreach ($program->getAssets() as $asset) : ?>
                        <li itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <?php echo $asset->getName().
                                    ($asset->getAssetType() == 'place' ? ' - Dirección: <span itemprop="streetAddress">'.$asset->getLocation()->getFirst()->getAddress().'</span>' : '') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="waw-affiliate-program box_round white-background darkgray">
            <div class="waw-affiliate-title"><?php echo __('Condiciones'); ?></div>
            <div class="waw-affiliate-content">
                <ul>
                    <?php foreach($program->getTerms() as $term): ?>
                        <li>
                            <?php echo $term->getTerm() ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="waw-affiliate-program box_round white-background darkgray">
            <div class="waw-affiliate-content">
                INDEPABIS: <?php echo $program->getIndepabis() ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
    });
</script>
