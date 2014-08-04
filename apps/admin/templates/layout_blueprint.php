<?php use_helper('I18N') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <title><?php echo get_slot('page_title', __('LealTag :: Mereces ser premiado')) ?></title>
        <link rel="shortcut icon" href="/favicon.ico" />
        
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/blueprint/screen.css'); ?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/admin.css'); ?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/dashboard.css'); ?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/jquery_ui/jquery-ui-1.8.18.custom.css'); ?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/tablesorter/tablesorter.css'); ?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/tablesorter/themes/blue/style.css'); ?>" rel="stylesheet"></link>
        <link rel="stylesheet" href="<?php echo Util::auto_version('/css/bvalidator.css') ?>" type="text/css" media="screen, projection" />
        <?php include_stylesheets() ?>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo Util::auto_version('/js/amcharts/amcharts.js') ?>"></script>
        <script type="text/javascript" src="<?php echo Util::auto_version('/js/survey.js') ?>"></script>
        <script type="text/javascript" src="<?php echo Util::auto_version('/js/charts.js') ?>"></script>
        <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
        
        <?php use_javascript('jquery.bvalidator-yc.js') ?>
        <?php use_javascript('jquery.tablesorter.min.js') ?>
        
        <?php include_javascripts() ?>
    </head>
    <body>
        <div class="container">
            <div id="header" class="span-24">
                <div class="span-22">
                    <h1>LealTag - Modulo de Administración</h1>
                </div>
                <?php if($sf_user->isAuthenticated()): ?>
                    <div class='span-2 last'>
                        <a href="<?php echo url_for('sf_guard_signout') ?>">Salir</a>
                    </div>
                <?php endif; ?>
            </div>
            <div id="top-menu" class="span-24">
                <ul>
                    <li><a href="<?php echo url_for('homepage')?>">Inicio</a></li>
                    <li><a href="<?php echo url_for('affiliate')?>">Afiliados</a></li>
                    <li><a href="<?php echo url_for('promo')?>">Promociones</a></li>
                    <li><a href="<?php echo url_for('promocode')?>">Códigos de Promoción</a></li>
                    <li><a href="<?php echo url_for('survey')?>">Encuestas</a></li>
                    <li><a href="<?php echo url_for('asset_places')?>">Establecimientos</a></li>
                    <li><a href="<?php echo url_for('asset_brands')?>">Marcas</a></li>
                    <li><a href="<?php echo url_for('user')?>">Usuarios</a></li>
                </ul>
            </div>
            <div id="main-container" class="span-24">
                <?php echo $sf_content ?>
            </div>
        </div>
    </body>
</html>
