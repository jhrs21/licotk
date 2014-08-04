<?php use_helper('I18N') ?>
<?php slot('page_title', 'Cómo funciona | LealTag - Mereces ser premiado') ?>
<?php slot('metas') ?>
    <meta content="" name="abstract"/>
    <meta content="En sólo 3 fáciles, sencillos y rápidos pasos, así es LealTag" name="description"/>
    <meta content="" name="keywords"/>
    <meta content="" name="keyphrases"/>
    <meta content="index, follow" name="robots"/>
    <meta content="<?php echo url_for('@howto_user', true); ?>" property="og:url"/>
    <meta content="website" property="og:type"/>
    <meta content="LealTag" property="og:site_name"/>
    <meta content="http://www.lealtag.com/images/download_qr.png" property="og:image"/>
    <meta content="En sólo 3 fáciles, sencillos y rápidos pasos, así es LealTag" property="og:description"/>
<?php end_slot() ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div id="howtouser-image">
        <div id="link-home" onclick="location.href='http://lealtag.com/descarga';"></div>
        <div id="link-apply" onclick="location.href='<?php echo url_for('apply') ?>';"></div>
        <div id="link-blackberry" onclick="location.href='http://appworld.blackberry.com/webstore/content/99034/';"></div>
        <div id="link-android" onclick="location.href='https://play.google.com/store/apps/details?id=com.mobmedianet.lealtag';"></div>
        <div id="link-iphone" onclick="location.href='http://itunes.apple.com/us/app/lealtag/id542239833';"></div>
        <div id="link-faq" onclick="location.href='<?php echo url_for('preguntas_frecuentes') ?>';"></div>
        <div id="link-card" onclick="location.href='<?php echo url_for('generate_membership_card')?>';"></div>
    </div>
</div>