<?php use_helper('I18N') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <title><?php echo get_slot('page_title', __('LealTag - Mereces ser premiado')) ?></title>

        <meta property="og:title" content="<?php echo get_slot('page_title', __('LealTag - Mereces ser premiado')) ?>"/>
        <meta property="og:url" content="http://www.lealtag.com"/>
        <meta property="og:image" content="http://www.lealtag.com/images/download_qr.png"/>
        <meta property="og:site_name" content="LealTag"/>
        <meta property="og:description" content="LealTag es el nuevo plan de fidelidad que te premia por visitar los sitios que te gustan. Descarga la aplicación y empieza a escanear tus premios."/>
        <meta name="viewport"  content="initial-scale=1, width=device-width">

        <link rel="shortcut icon" href="/favicon.gif" />

        <link rel="stylesheet" media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/webmobile.css') ?>"></link>
        <link rel="stylesheet" media="screen, projection" type="text/css" href="<?php echo Util::auto_version('/css/jquery_ui/jquery-ui-1.8.18.custom.css') ?>"></link>
        <link rel="stylesheet" href="<?php echo Util::auto_version('/css/bvalidator.css') ?>" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Util::auto_version('/css/jquery_ui/jquery-ui-1.8.18.custom.css') ?>" type="text/css" media="screen, projection" />


        <?php include_stylesheets() ?>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <?php use_javascript('jquery.tinyscrollbar.min.js') ?>
        <?php use_javascript('jquery.bvalidator-yc.js') ?>
        <?php include_javascripts() ?>

        <script type="text/javascript">    
            function report() { document.getElementsByTagName('output')[0].innerHTML = 'screen.width:'+screen.width+'<br>screen.height:'+screen.height+'<br>window.innerWidth:'+window.innerWidth+'<br>window.innerHeight:'+window.innerHeight+'<br>window.outerWidth:'+window.outerWidth+'<br>window.outerHeight:'+window.outerHeight+'<br>document.documentElement.<br> clientWidth:'+document.documentElement.clientWidth+'<br>document.documentElement.<br> clientHeight:'+document.documentElement.clientHeight+'<br>window.devicePixelRatio:'+window.devicePixelRatio; }
            window.addEventListener('load', report, false);
            window.addEventListener('resize', report, false);
            window.addEventListener('orientationchange', report, false);
            window.addEventListener('deviceorientation', report, false);
            window.addEventListener('MozOrientation', report, false); 
        </script>
    </head>
    <body>
        <div id="wm-wrap">
            <div id="wm-header">
                <div id="wm-content-header">
                    <a href="<?php echo url_for('homepage')?>">
                        <img border="0" src="/images/Lealtag_700px.png" alt="Lealtag"></img>
                    </a>
                </div>
            </div>
            <div id="wm-page">  
                <?php echo $sf_content ?>
            </div>
        </div>
        <div id="wm-footer">
            <div id="wm-content-footer">
                <div id="copyrights">
                    <div class="copyrights-icon" alt="Derechos Reservados"></div>
                    <p>LealTag 2012, Todos los derechos reservados</p>
                    <p>J-40058569-4</p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
            document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script>
        <script type="text/javascript">
            try {
                var pageTracker = _gat._getTracker("UA-8453623-1");
                pageTracker._trackPageview();
            } catch(err) {}
        </script></body>
</html>