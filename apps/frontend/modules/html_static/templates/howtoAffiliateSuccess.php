<?php use_helper('I18N') ?>
<?php slot('page_title', 'Publica tu negocio | LealTag - Mereces ser premiado') ?>
<?php slot('metas') ?>
    <meta content="" name="abstract"/>
    <meta content="Afilia tu negocio a LealTag, comienza a premiar a tus clientes y verás tus ganancias crecer" name="description"/>
    <meta content="" name="keywords"/>
    <meta content="" name="keyphrases"/>
    <meta content="index, follow" name="robots"/>
    <meta content="<?php echo url_for('@howto_affiliate', true); ?>" property="og:url"/>
    <meta content="website" property="og:type"/>
    <meta content="LealTag" property="og:site_name"/>
    <meta content="http://www.lealtag.com/images/download_qr.png" property="og:image"/>
    <meta content="Afilia tu negocio a LealTag, comienza a premiar a tus clientes y verás tus ganancias crecer" property="og:description"/>
<?php end_slot() ?>
    
<div id="main-container" class="box_bottom_round box_shadow white-background">
        <div id="howtoaffiliate-image">
            <div id="link-registro1" onclick="location.href='<?php echo url_for('contact_affiliate') ?>';"></div>
            <div id="link-registro2" onclick="location.href='<?php echo url_for('contact_affiliate') ?>';"></div>
        </div>
</div>