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
    
        <link rel="shortcut icon" href="/favicon.ico" />
        
        <link media="screen, projection" type="text/css" href="/css/blueprint/src/reset.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/blueprint/src/grid.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/blueprint/src/typography.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/jquery_ui/jquery-ui-1.8.18.custom.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/colorbox/colorbox.css" rel="stylesheet"></link>
        <link media="screen, projection" type="text/css" href="/css/coin-slider.css" rel="stylesheet"></link>
        
        <!--[if lt IE 7]>
            <div style=' clear: both; text-align:center; position: relative;'>
                <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
                    <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
                </a>
            </div>
        <![endif]-->
        
        <!--[if lt IE 8]>
            <link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection" />
        <![endif]-->
        
        <link media="screen, projection" type="text/css" href="/css/frontend.css" rel="stylesheet"></link>
        
        <!--[if IE 7]>
            <link rel="stylesheet" href="/css/ie_7_hacks_frontend.css" type="text/css" media="screen, projection" />
        <![endif]-->
        
        <!--[if IE 8]>
            <link rel="stylesheet" href="/css/ie_8_hacks_frontend.css" type="text/css" media="screen, projection" />
        <![endif]-->
        
        <link rel="stylesheet" href="/css/bvalidator.css" type="text/css" media="screen, projection" />
        
        <?php include_stylesheets() ?>
        
        <?php use_javascript('jquery-1.7.1.min.js') ?>
        <?php use_javascript('jquery-ui-1.8.18.custom.min.js') ?>
        <?php use_javascript('jquery.tinyscrollbar.min.js') ?>
        <?php use_javascript('jquery.bvalidator-yc.js') ?>
        <?php use_javascript('superfish.js') ?>
        <?php use_javascript('coin-slider.js') ?>
        <?php use_javascript('jquery.colorbox-min.js') ?>
        <?php use_javascript('jquery.pajinate.js') ?>
        <?php include_javascripts() ?>
        
        <!--
            Lo siguiente es el código de Google Analytics
        -->
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-30126093-1']);
            _gaq.push(['_setDomainName', 'lealtag.com']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
        <!--
            Fin código Google Analytics
        -->
        <script type="text/javascript">
            $(document).ready(function(){
                if($.browser.mozilla||$.browser.opera){document.removeEventListener("DOMContentLoaded",$.ready,false);document.addEventListener("DOMContentLoaded",function(){$.ready()},false)}$.event.remove(window,"load",$.ready);$.event.add( window,"load",function(){$.ready()});$.extend({includeStates:{},include:function(url,callback,dependency){if(typeof callback!='function'&&!dependency){dependency=callback;callback=null}url=url.replace('\n','');$.includeStates[url]=false;var script=document.createElement('script');script.type='text/javascript';script.onload=function(){$.includeStates[url]=true;if(callback)callback.call(script)};script.onreadystatechange=function(){if(this.readyState!="complete"&&this.readyState!="loaded")return;$.includeStates[url]=true;if(callback)callback.call(script)};script.src=url;if(dependency){if(dependency.constructor!=Array)dependency=[dependency];setTimeout(function(){var valid=true;$.each(dependency,function(k,v){if(!v()){valid=false;return false}});if(valid)document.getElementsByTagName('head')[0].appendChild(script);else setTimeout(arguments.callee,10)},10)}else document.getElementsByTagName('head')[0].appendChild(script);return function(){return $.includeStates[url]}},readyOld:$.ready,ready:function(){if($.isReady) return;imReady=true;$.each($.includeStates,function(url,state){if(!state)return imReady=false});if(imReady){$.readyOld.apply($,arguments)}else{setTimeout(arguments.callee,10)}}});
                $( ".opener" ).colorbox({inline:true, href:"#download-modal", width:"50%"});
            })
        </script>
    </head>
    <body>
        <div id="wrap">
            <header>
                <div id='global_container'>
                    <div id='header_container'>
                        <div id='header'>
                            <div id='header_inner'>
                                <h1 id='logo'>
                                    <a title="LealTag.com - Mereces ser premiado..." href="<?php echo url_for('@homepage')?>">
                                        LealTag.com - Mereces ser premiado...
                                    </a>
                                </h1>
                                <p id='questionlinks' name="questionlinks">
                                    <a href="<?php echo url_for('@homepage')?>">INICIO</a>&nbsp;&nbsp;&nbsp; 
                                    <a href="<?php echo url_for('howto_user') ?>">¿CÓMO FUNCIONA?</a>&nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo url_for('donde_estamos') ?>">COMERCIOS AFILIADOS</a>&nbsp;&nbsp;&nbsp; 
                                    <a href="<?php echo url_for('howto_affiliate') ?>">¿TIENES UN NEGOCIO?</a>&nbsp;&nbsp;&nbsp; 
                                    <a style="display: none" href="#">¿DÓNDE ESTAMOS?</a>
                                </p>
                                <div class='layer2 clearfix'>
                                    <ul class='partners_links'>
                                        <li class="divisor">&nbsp;</li>
                                        <li><a target="_blank" href="http://www.tudescuenton.com"><img src="/images/newhd_td_letters.png"></a></li>
                                        <li class="divisor">&nbsp;</li>
                                        <li><a target="_blank" href="http://www.lealtag.com"><img src="/images/newhd_lt_letters.png"></a></li>
                                        <li class="divisor">&nbsp;</li>
                                        <li><a target="_blank" href="http://www.fueradelote.com"><img src="/images/newhd_fl_letters.png"></a></li>
                                        <li class="divisor">&nbsp;</li>
                                    </ul>
                                    <ul id='header_nav'>
                                        <?php if($sf_user->isAuthenticated()): ?>
                                            <li class='current'>
                                                <a href="<?php echo url_for('user_prizes') ?>" class="current" >Tu Cuenta</a>
                                            </li>
                                            <li class='current'>
                                                <a href="<?php echo url_for('sf_guard_signout') ?>" class="current" >Salir</a>
                                            </li>
                                        <?php else: ?>
                                            <li class='current'>
                                                <a href="<?php echo url_for('sf_guard_signin') ?>" class="current" >Ingresa</a>
                                            </li>
                                            <li class='current'>
                                                <a href="<?php echo url_for('apply') ?>" class="current" >Regístrate</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <ul id="header-register-tag">
                                    <li>
                                        <a href="<?php echo url_for('user_new_ticket') ?>"><span class="tag-simbol">Registrar un Código</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div id="main" class="container">
                <?php echo $sf_content ?>
            </div>
        </div>
        <div id="footer">
            <div id="main-footer" class="container">
                <div id="main-footer-inner">
                    <div id="main-footer-buttons">
                        <a alt="Registrar un Tag" href="<?php echo url_for('user_new_ticket')?>">
                            <span class="register-tag-button-up"></span>
                        </a>
                        <a target="_blank" alt="Google+" href="https://plus.google.com/u/0/117951752927464537515/posts">
                            <span class="google-plus-button"></span>
                        </a>
                        <a target="_blank" alt="Facebook" href="https://www.facebook.com/pages/LealTag/264505156951043">
                            <span class="facebook-button"></span>
                        </a>
                        <a target="_blank" alt="Twitter" href="http://twitter.com/#!/Lealtag">
                            <span class="twitter-button"></span>
                        </a>
                    </div>
                </div>
            </div>
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
                        <ul class="footer-menu-column">
                            <li class="column-header">
                                <a href="<?php echo url_for('@homepage')?>">Inicio</a>
                            </li>
                        </ul>
                        <ul class="footer-menu-column">
                            <li class="column-header">
                                <a href="<?php echo url_for('howto_user')?>">Usuarios</a>
                            </li>
                            <li>
                                <a href="<?php echo url_for('sf_guard_signin')?>">Accede a tu cuenta</a>
                            </li>
                            <li>
                                <a href="<?php echo url_for('preguntas_frecuentes')?>">Preguntas Frecuentes</a>
                            </li>
                            <li>
                                <a class="opener" href="#">Descarga la aplicación</a>
                            </li>
                        </ul>
                        <ul class="footer-menu-column">
                            <li class="column-header">
                                <a href="<?php echo url_for('howto_affiliate')?>">Negocios</a>
                            </li>
                            <li>
                                <a href="http://lealtag.com/biz.php/login">Accede a tu cuenta</a>
                            </li>
                        </ul>
                        <ul class="footer-menu-column">
                            <li class="column-header">
                                <a href="<?php echo url_for('contacto')?>">Contactanos</a>
                            </li>
                        </ul>
                        <ul class="footer-menu-column">
                            <li class="column-header">
                                <a href="<?php echo url_for('privacy_policy')?>">Términos y condiciones</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
