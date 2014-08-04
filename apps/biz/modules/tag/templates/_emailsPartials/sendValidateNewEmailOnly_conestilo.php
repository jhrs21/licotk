<?php use_helper('I18N', 'Url') ?>
<?php include_partial('tag/emailsPartials/emailHeader') ?>
<?php echo __(<<<EOM
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
/* Mobile-specific Styles */
@media only screen and (max-device-width: 480px) { 
table[class=w0], td[class=w0] { width: 0 !important; }
table[class=w10], td[class=w10], img[class=w10] { width:10px !important; }
table[class=w15], td[class=w15], img[class=w15] { width:5px !important; }
table[class=w30], td[class=w30], img[class=w30] { width:10px !important; }
table[class=w60], td[class=w60], img[class=w60] { width:10px !important; }
table[class=w125], td[class=w125], img[class=w125] { width:80px !important; }
table[class=w130], td[class=w130], img[class=w130] { width:55px !important; }
table[class=w140], td[class=w140], img[class=w140] { width:90px !important; }
table[class=w160], td[class=w160], img[class=w160] { width:180px !important; }
table[class=w170], td[class=w170], img[class=w170] { width:100px !important; }
table[class=w180], td[class=w180], img[class=w180] { width:80px !important; }
table[class=w195], td[class=w195], img[class=w195] { width:80px !important; }
table[class=w220], td[class=w220], img[class=w220] { width:80px !important; }
table[class=w240], td[class=w240], img[class=w240] { width:180px !important; }
table[class=w255], td[class=w255], img[class=w255] { width:185px !important; }
table[class=w275], td[class=w275], img[class=w275] { width:135px !important; }
table[class=w280], td[class=w280], img[class=w280] { width:135px !important; }
table[class=w300], td[class=w300], img[class=w300] { width:140px !important; }
table[class=w325], td[class=w325], img[class=w325] { width:95px !important; }
table[class=w360], td[class=w360], img[class=w360] { width:140px !important; }
table[class=w410], td[class=w410], img[class=w410] { width:180px !important; }
table[class=w470], td[class=w470], img[class=w470] { width:200px !important; }
table[class=w580], td[class=w580], img[class=w580] { width:280px !important; }
table[class=w640], td[class=w640], img[class=w640] { width:300px !important; }
table[class*=hide], td[class*=hide], img[class*=hide], p[class*=hide], span[class*=hide] { display:none !important; }
table[class=h0], td[class=h0] { height: 0 !important; }
p[class=footer-content-left] { text-align: center !important; }
#headline p { font-size: 30px !important; }
.article-content, #left-sidebar{ -webkit-text-size-adjust: 90% !important; -ms-text-size-adjust: 90% !important; }
.header-content, .footer-content-left {-webkit-text-size-adjust: 80% !important; -ms-text-size-adjust: 80% !important;}
img { height: auto; line-height: 100%;}
 } 
/* Client-specific Styles */
#outlook a { padding: 0; }	/* Force Outlook to provide a "view in browser" button. */
body { width: 100% !important; }
.ReadMsgBody { width: 100%; }
.ExternalClass { width: 100%; display:block !important; } /* Force Hotmail to display emails at full width */
/* Reset Styles */
/* Add 100px so mobile switch bar doesn't cover street address. */
body { background-color: #05599F; margin: 0; padding: 0; }
img { outline: none; text-decoration: none; display: block;}
br, strong br, b br, em br, i br { line-height:100%; }
h1, h2, h3, h4, h5, h6 { line-height: 100% !important; -webkit-font-smoothing: antialiased; }
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: blue !important; }
h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {	color: red !important; }
/* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited { color: purple !important; }
/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */  
table td, table tr { border-collapse: collapse; }
.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
color: black; text-decoration: none !important; border-bottom: none !important; background: none !important;
}	/* Body text color for the New Yahoo.  This example sets the font of Yahoo's Shortcuts to black. */
/* This most probably won't work in all email clients. Don't include <code _tmplitem="229" > blocks in email. */
code {
  white-space: normal;
  word-break: break-all;
}
#background-table { background-color: #05599F; }
/* Webkit Elements */
#top-bar { border-radius:6px 6px 0px 0px; -moz-border-radius: 6px 6px 0px 0px; -webkit-border-radius:6px 6px 0px 0px; -webkit-font-smoothing: antialiased; background-color: #21BAE7; color: #FFFFFF; }
#top-bar a { font-weight: bold; color: #FFFFFF; text-decoration: none;}
#footer { border-radius:0px 0px 6px 6px; -moz-border-radius: 0px 0px 6px 6px; -webkit-border-radius:0px 0px 6px 6px; -webkit-font-smoothing: antialiased; }
/* Fonts and Content */
body, td { font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; }
.header-content, .footer-content-left, .footer-content-right { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
/* Prevent Webkit and Windows Mobile platforms from changing default font sizes on header and footer. */
.header-content { font-size: 12px; color: #FFFFFF; }
.header-content a { font-weight: bold; color: #FFFFFF; text-decoration: none; }
#headline p { color: #d9fffd; font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size: 36px; text-align: center; margin-top:0px; margin-bottom:30px; }
#headline p a { color: #d9fffd; text-decoration: none; }
.article-title { font-size: 18px; line-height:24px; color: #585d61; font-weight:bold; margin-top:0px; margin-bottom:18px; font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; }
.article-title a { color: #585d61; text-decoration: none; }
.article-title.with-meta {margin-bottom: 0;}
.article-meta { font-size: 13px; line-height: 20px; color: #ccc; font-weight: bold; margin-top: 0;}
.article-content { font-size: 13px; line-height: 18px; color: #444444; margin-top: 0px; margin-bottom: 18px; font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; }
.article-content a { color: #05599F; font-weight:bold; text-decoration:none; }
.article-content img { max-width: 100% }
.article-content ol, .article-content ul { margin-top:0px; margin-bottom:18px; margin-left:19px; padding:0; }
.article-content li { font-size: 13px; line-height: 18px; color: #444444; }
.article-content li a { color: #05599F; text-decoration:underline; }
.article-content p {margin-bottom: 15px;}
.footer-content-left { font-size: 12px; line-height: 15px; color: #FFFFFF; margin-top: 0px; margin-bottom: 15px; }
.footer-content-left a { color: #FFFFFF; font-weight: bold; text-decoration: none; }
.footer-content-right { font-size: 11px; line-height: 16px; color: #FFFFFF; margin-top: 0px; margin-bottom: 15px; }
.footer-content-right a { color: #FFFFFF; font-weight: bold; text-decoration: none; }
#footer { background-color: #585D61; color: #FFFFFF; }
#footer a { color: #FFFFFF; text-decoration: none; font-weight: bold; }
#permission-reminder { white-space: normal; }
#street-address { color: #FFFFFF; white-space: normal; }
</style>
<!--[if gte mso 9]>
<style _tmplitem="229" >
.article-content ol, .article-content ul {
   margin: 0 0 0 24px;
   padding: 0;
   list-style-position: inside;
}
</style>
<![endif]--></head><body><table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table">
	<tbody><tr>
		<td align="center" bgcolor="#05599F">
        	<table class="w640" style="margin:0 10px;" width="640" cellpadding="0" cellspacing="0" border="0">
            	<tbody><tr><td class="w640" width="640" height="20"></td></tr>
                
            	<tr>
                	<td class="w640" width="640">
                        <table id="top-bar" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
    <tbody><tr bgcolor="#21BAE7">
        <td class="w15" width="15"></td>
        <td class="w325" width="350" valign="middle" align="left">
            <table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w325" width="350" height="8"></td></tr>
            </tbody></table>
            <table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w325" width="350" height="8"></td></tr>
            </tbody></table>
        </td>
        <td class="w30" width="30"></td>
        <td class="w255" width="255" valign="middle" align="right">
            <table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w255" width="255" height="8"></td></tr>
            </tbody></table>
            <table cellpadding="0" cellspacing="0" border="0">
    <tbody><tr>
        
        <td valign="middle"><a href="http://www.facebook.com/lealtag"><img src="http://www.lealtag.com/images/facebook_icon.png" border="0" width="8" height="14" alt="Me gusta"></a></td>
        <td width="3"></td>
        <td valign="middle"><div class="header-content" style="width: 56px;"><a href="http://www.facebook.com/lealtag">Me gusta</a></div></td>
        
        
        <td class="w10" width="10"></td>
        <td valign="middle"><a href="http://www.twitter.com/lealtag"><img src="http://www.lealtag.com/images/twitter_icon.png" border="0" width="17" height="13" alt="Síguenos"></a></td>
        <td width="3"></td>
        <td valign="middle"><div class="header-content"><a href="http://www.twitter.com/lealtag">Síguenos</a></div></td>
        
        
        <td class="w10" width="10"></td>
        <td valign="middle"><a href="http://www.lealtag.com"><img src="http://www.lealtag.com/images/www_icon.png" border="0" width="17" height="13" alt="Síguenos"></a></td>
        <td width="3"></td>
        <td valign="middle"><div class="header-content"><a href="http://www.lealtag.com">Lealtag.com</a></div></td>
        
    </tr>
</tbody></table>
            <table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w255" width="255" height="8"></td></tr>
            </tbody></table>
        </td>
        <td class="w15" width="15"></td>
    </tr>
</tbody></table>
                        
                    </td>
                </tr>
                <tr>
                <td id="header" class="w640" width="640" align="center" bgcolor="#FFFFFF">
    
    <div align="right" style="text-align: right">
        <a href="http://www.lealtag.com/">
            <img id="customHeaderImage" label="Header Image" editable="true" width="264" alt="LealTag" src="http://lealtag.com/images/logo_slogan.png" class="w640" border="0" align="top" style="display: inline">
        </a>
    </div>
    
    
</td>
                </tr>
                
                <tr><td class="w640" width="640" height="30" bgcolor="#ebebeb"></td></tr>
                <tr id="simple-content-row"><td class="w640" width="640" bgcolor="#ebebeb">
    <table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
        <tbody><tr>
            <td class="w30" width="30"></td>
            <td class="w580" width="580">
                <repeater>
                    
                    <layout label="Text only">
                        <table class="w580" width="580" cellpadding="0" cellspacing="0" border="0">
                            <tbody><tr>
                                <td class="w580" width="580">
                                    <h1 align="left" class="article-title"><singleline label="Title">¡Bienvenido a LealTag! </singleline></h1>
                                    <div align="left" class="article-content">
                                        <multiline label="Description">
                                            <h2>
                                                ¡Completa tu cuenta para disfrutar de tus premios!
                                            </h2>
                                            <p>
                                                Felicitaciones por recibir tu primer tag en %ASSET%, ya estas siendo premiado 
                                                por visitar los sitios que te gustan. Para poder disfrutar de tus premios, 
                                                completa el registro de tu cuenta en este <a href="%1%">link</a>:
                                            </p>
                                            <p>
                                                <a href="%3%">%2%</a>
                                            </p>
                                            <p>
                                                Ahora sólo debes bajarte la aplicación desde tu Blackberry (versión 5.0 en adelante),
                                                iPhone (u otro dispositivo Apple iOS) o Android desde el link 
                                                <a href="http://www.lealtag.com/descarga">www.lealtag.com/descarga</a>
                                            </p>
                                            <p>
                                                Busca el regalito LealTag que identifica a los comercios afiliados para empezar
                                                a ganar; y si tus locales preferidos no forman parte de LealTag, pídeles que te
                                                premien.
                                            </p>
                                            <p>
                                                Para más información o si no cuentas con un teléfono móvil de este tipo, entra a
                                                nuestra página web www.lealtag.com para acceder a tu cuenta LealTag y ver tus
                                                premios.
                                            </p>
                                            <p>
                                                Tus comentarios y sugerencias nos ayudan a mejorar, así que cualquier cosa
                                                escríbenos a info@lealtag.com y síguenos en Twitter <a href="http://www.twitter.com/lealtag">@lealtag</a> para enterarte de
                                                todas las cosas nuevas que vamos a traer para ti.
                                            </p>
                                            <p>
                                            Mereces ser premiado
                                            </p> 
                                            <p>El equipo de LealTag ;)</p>
                                        </multiline>
                                    </div>
                                </td>
                            </tr>
                            <tr><td class="w580" width="580" height="10"></td></tr>
                        </tbody></table>
                    </layout>
                    
                </repeater>
            </td>
            <td class="w30" width="30"></td>
        </tr>
    </tbody></table>
</td></tr>
                <tr><td class="w640" width="640" height="15" bgcolor="#ebebeb"></td></tr>
                
                <tr>
                <td class="w640" width="640">
    <table id="footer" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#585D61">
        <tbody><tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="30"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
        <tr>
            <td class="w30" width="30"></td>
            <td class="w580" width="720" valign="top">
            <span class="hide"><p id="permission-reminder" align="left" class="footer-content-left"></p></span>
            <p align="center" class="footer-content-left" style="color:white;"><b>Mail:</b> <a href="mailto:soporte@lealtag.com" target="_blank" style="color: white;">soporte@lealtag.com</a></p>
	    <p align="right" class="footer-content-left" style="color:white;"><b>Servicio al cliente:</b>  <a href="tel:%280212%29%203122724" value="+582123122724" target="_blank" style="color: white;">(0212) 3122724</a> Lunes a Viernes 9:00 - 18:00</p>
            </td>
            <td class="hide w0" width="60"></td>
            <td class="hide w0" width="160" valign="top">
            <p id="street-address" align="right" class="footer-content-right"></p>
            </td>
            <td class="w30" width="30"></td>
        </tr>
        <tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="15"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
    </tbody></table>
</td>
                </tr>
                <tr><td class="w640" width="640" height="60"></td></tr>
            </tbody></table>
        </td>
	</tr>
</tbody></table></body></html>
<p>Dirección: Av. Neverí, Quinta Mireille, Colinas de Bello Monte, Caracas - Venezuela.</p>
<p>Recibió este correo porque usted está registrado en LealTag</p>   
EOM
        , array(
    "%1%" => $route1,
    "%2%" => $route2,
    "%3%" => $route3,
    "%ASSET%" => $asset
        )
)
?>
<?php

include_partial('tag/emailsPartials/emailFooter')?>
