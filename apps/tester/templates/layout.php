<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link media="screen, projection" type="text/css" href="/css/blueprint/screen.css" rel="stylesheet"></link>
        <link media="print" type="text/css" href="/css/blueprint/print.css" rel="stylesheet"></link>
        <!--[if IE]>
            <link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection" />
        <![endif]-->
        <link rel="stylesheet" href="/css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection" />
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
    </head>
    <body>
        <div class="container">
            <div id="header" class="span-24">
                <h1>LealTag Api Tester</h1>
            </div>
            <div class="top-menu span-24">
                <ul>
                    <li><a href="<?php echo url_for('lt_apitester')?>">LT API</a></li>
                    <li><a href="<?php echo url_for('lt_apitester_login')?>">TD API</a></li>
                </ul>
            </div>
            <div id="main" class="span-24">
                <?php echo $sf_content ?>
            </div>
        </div>
    </body>
</html>
