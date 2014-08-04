<?php use_helper('I18N') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <title><?php echo get_slot('page_title', __('LealTag :: Mereces ser premiado')) ?></title>
        <link rel="shortcut icon" href="/favicon.ico" />

        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/blueprint/src/reset.css')?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/blueprint/src/grid.css') ?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/blueprint/src/typography.css') ?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/jquery_ui/jquery-ui-1.8.18.custom.css') ?> " rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/colorbox/colorbox.css') ?>" rel="stylesheet"></link>

        <!--[if lt IE 7]>
            <div style=' clear: both; text-align:center; position: relative;'>
                <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
                    <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
                </a>
            </div>
        <![endif]-->

        <!--[if lt IE 8]>
            <link rel="stylesheet" href="<?php echo Util::auto_version('/css/blueprint/ie.css') ?>" type="text/css" media="screen, projection" />
        <![endif]-->

        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/biz.css') ?>" rel="stylesheet"></link>

        <!--[if IE 7]>
            <link rel="stylesheet" href="<?php echo Util::auto_version('/css/ie_7_hacks_frontend.css') ?>" type="text/css" media="screen, projection" />
        <![endif]-->

        <!--[if IE 8]>
            <link rel="stylesheet" href="<?php echo Util::auto_version('/css/ie_8_hacks_frontend.css') ?>" type="text/css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" href="<?php echo Util::auto_version('/css/bvalidator.css') ?>" type="text/css" media="screen, projection" />

        <?php include_stylesheets() ?>

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        
        <script type="text/javascript" src="<?php echo Util::auto_version('/js/amcharts/amcharts.js') ?>"></script>
        <script type="text/javascript" src="<?php echo Util::auto_version('/js/survey.js') ?>"></script>
        <script type="text/javascript" src="<?php echo Util::auto_version('/js/charts.js') ?>"></script>
        
        <?php use_javascript('jquery.bvalidator-yc.js') ?>
        <?php use_javascript('jquery.colorbox-min.js') ?>
        <?php include_javascripts() ?>

    </head>
    <body>
        <div id="wrap">
            <header>
                <div id='global_container'>
                    <div id='header_container'>
                        <div id='header'>
                            <div id='header_inner'>
                                <h1 id='logo'>
                                    <a title="LealTag.com - Mereces ser premiado..." href="<?php echo url_for('@homepage') ?>">
                                        LealTag.com - Mereces ser premiado...
                                    </a>
                                </h1>
                                <?php include_partial('html_static/gtdHeader');?>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div id="main" class="container">
                <?php if ($sf_user->hasFlash('notice')): ?>
                    <div class="flash flash_notice">
                        <p class="flash_message"><?php echo $sf_user->getFlash('notice') ?></p>
                    </div>
                <?php endif ?>
                <?php if ($sf_user->hasFlash('success')): ?>
                    <div class="flash flash_notice">
                        <p class="flash_message"><?php echo $sf_user->getFlash('success') ?></p>
                    </div>
                <?php endif ?>
                <div id="main-container" class="span-24">
                    <?php #include_partial('html_static/colorBanner')?>
                    <?php echo $sf_content ?>
                </div>
            </div>
        </div>
<!--        <div id="footer">
            <div id="footer-container">
                <div id="footer-content" class="container">
                    <div id="footer-copyrights"></div>
                    <div id="footer-mailto">
                        <img src="/images/footer_mailto.png"border="0" usemap="#mailto_map">
                        <map name="mailto_map">
                            <area shape="rect" coords="0,18,112,40" href="mailto:info@lealtag.com" alt="info@lealtag.com">
                            <area shape="rect" coords="112,0,130,18" href="mailto:info@lealtag.com" alt="info@lealtag.com">
                        </map>
                    </div>
                    <div id="footer-menu">
                        
                    </div>
                </div>
            </div>
        </div>-->
    </body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        var effect = 'blind';
        var options = {};
        $('.flash').click(function(){
            $(this).hide( effect, options, 1000);
        });
    })
</script>