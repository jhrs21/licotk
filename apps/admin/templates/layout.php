<?php use_helper('I18N') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <title><?php echo get_slot('page_title', __('LealTag :: Mereces ser premiado')) ?></title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="shortcut icon" href="/favicon.ico" />
        <link media="screen, projection" type="text/css" href="/css/blueprint/screen.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/admin.css') ?>" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/dashboard.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/jquery_ui/jquery-ui-1.8.18.custom.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/tablesorter/tablesorter.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/tablesorter/themes/blue/style.css" rel="stylesheet"></link>
        <?php include_stylesheets() ?>
        
        <?php // use_javascript('jquery-1.7.1.min.js') ?>
        <?php // use_javascript('jquery-ui-1.8.18.custom.min.js') ?>
        <?php use_javascript('jquery.bvalidator-yc.js') ?>
        <?php use_javascript('jquery.colorbox-min.js') ?>
        <?php use_javascript('jquery.tablesorter.min.js') ?>
        <?php include_javascripts() ?>
    </head>
    <body>
        <div id="header" class="container">
            <div class="span-22">
                <h1>LealTag - Modulo de Administración</h1>
            </div>
            <?php if($sf_user->isAuthenticated()): ?>
                <div class='span-2 last' style="font-size:1.5em">
                    <a href="<?php echo url_for('homepage') ?>">Inicio</a> 
                    <a href="<?php echo url_for('sf_guard_signout') ?>">Salir</a>
                </div>
            <?php endif; ?>
        </div>
        <?php echo $sf_content ?>
    </body>
</html>
