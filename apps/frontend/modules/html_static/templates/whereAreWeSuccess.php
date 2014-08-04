<?php use_helper('I18N') ?>
<?php slot('page_title', 'Comercios Afiliados | LealTag - Mereces ser premiado') ?>
<?php slot('metas') ?>
    <meta content="" name="abstract"/>
    <meta content="Estos son los sitios que premian tu fidelidad. Visitalos, acumula puntos y gana premios" name="description"/>
    <meta content="" name="keywords"/>
    <meta content="" name="keyphrases"/>
    <meta content="index, follow" name="robots"/>
    <meta content="<?php echo url_for('@donde_estamos', true); ?>" property="og:url"/>
    <meta content="website" property="og:type"/>
    <meta content="LealTag" property="og:site_name"/>
    <meta content="http://www.lealtag.com/images/download_qr.png" property="og:image"/>
    <meta content="Estos son los sitios que premian tu fidelidad. Visitalos, acumula puntos y gana premios" property="og:description"/>
<?php end_slot() ?>
<div id="main-container" class="box_bottom_round box_shadow white-background many-canvas">
    <!--<div class="main-canvas">
        <a href='https://www.facebook.com/MasterCardVenezuela/app_664993200192906' target="_blank"><img class="box_round" alt='MasterCard te premia' src="/images/910x60px.jpg"/></a>
    </div>-->
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas-title lightblue">
            <?php echo __('Comercios Afiliados') ?>
        </div>
        <div class="main-canvas-subtitle darkgray">
            <?php echo __('¡Estos son los comercios que premian tu lealtad! Cada día se irán sumando nuevos comercios.') ?>
        </div>
        <div id="waw-items">
            <?php include_partial('wawItems',array('pager' => $pager)); ?>
        </div> 
<!--        <div class="main-canvas box_round gray-background">
            <div class="main-canvas-title lightblue">
                <?php //echo __('Comercios Afiliados') ?>
            </div>
            <div id="waw-items">
                <?php //include_partial('wawItems',array('pager' => $pager)); ?>
            </div>        
        </div>-->
        <?php //foreach ($promotedAffiliates as $affiliate): ?> 
            <?php //include_partial('wawItem', array('affiliate' => $affiliate)); ?>
        <?php //endforeach; ?>
    </div>
    <div id="waw-categories" class="main-canvas box_round gray-background">
        <?php foreach ($categories as $category): ?> 
            <a class="white box_round box_shadow_bottom blue-background" href="<?php echo url_for('donde_estamos').'?category='.$category->getSlug() ?>">
                <?php echo $category->getName() ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>