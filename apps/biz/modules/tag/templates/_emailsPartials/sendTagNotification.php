<?php use_helper('I18N', 'Url') ?>
<?php

echo __(<<<EOM
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table">
    <tbody>
        <tr>
            <td align="center" bgcolor="#05599F">
                <table class="w640" style="margin:0 10px;" width="640" cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <tr><td class="w640" width="640" height="20"></td></tr>
                        <tr>
                            <td class="w640" width="640">
                                <table id="top-bar" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                                    <tbody>
                                        <tr bgcolor="#21BAE7">
                                            <td class="w15" width="15"></td>
                                            <td class="w325" width="350" valign="middle" align="left">
                                                <table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr><td class="w325" width="350" height="8"></td></tr>
                                                    </tbody>
                                                </table>
                                                <table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody><tr><td class="w325" width="350" height="8"></td></tr></tbody>
                                                </table>
                                            </td>
                                            <td class="w30" width="30"></td>
                                            <td class="w255" width="255" valign="middle" align="right">
                                                <table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody><tr><td class="w255" width="255" height="8"></td></tr></tbody>
                                                </table>
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr>
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
                                                    </tbody>
                                                </table>
                                                <table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody><tr><td class="w255" width="255" height="8"></td></tr></tbody>
                                                </table>
                                            </td>
                                            <td class="w15" width="15"></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                        <tr id="simple-content-row">
                            <td class="w640" width="640" bgcolor="#ebebeb">
                                <table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td class="w30" width="30"></td>
                                            <td class="w580" width="580">
                                                <repeater>
                                                    <layout label="Text only">
                                                        <table class="w580" width="580" cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="w580" width="580">
                                                                        <h1 align="left" class="article-title">
                                                                            <singleline label="Title">¡Felicitaciones has recibido un Tag!</singleline>
                                                                        </h1>
                                                                        <div align="left" class="article-content">
                                                                            <multiline label="Description">
                                                                                <p>
                                                                                    <h2>¡Felicitaciones %FULLNAME%, acabas de recibir un Tag en %ASSET%!</h2>
                                                                                </p>
                                                                                <p>
                                                                                    <h2>Tu opinión es importante para nosotros</h2>
                                                                                </p>
                                                                                <p>
                                                                                    Puedes compartir tu experiencia en %ASSET% haciendo click en el siguiente <a href="%FEEDBACK%">link</a>:
                                                                                </p>
                                                                                <p>
                                                                                    <a href="%FEEDBACK%">%FEEDBACK%</a>
                                                                                </p>
                                                                                <p>
                                                                                    Conoce el estado de tus premios descargando la aplicación desde tu Blackberry 
                                                                                    (versión 5.0 en adelante), iPhone (u otro dispositivo Apple iOS) o Android desde 
                                                                                    el link www.lealtag.com/descarga o en la tienda de aplicaciones. Si no tienes 
                                                                                    uno de estos equipos, ingresa a tu cuenta en <a href="www.lealtag.com">www.lealtag.com</a>
                                                                                </p>
                                                                                <p>
                                                                                    Sigue visitando y siendo premiado en tus comercios favoritos con LealTag
                                                                                </p>
                                                                                <p>
                                                                                    Mereces ser premiado
                                                                                </p> 
                                                                                <p>
                                                                                    El equipo de LealTag ;)
                                                                                </p>
                                                                            </multiline>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr><td class="w580" width="580" height="10"></td></tr>
                                                            </tbody>
                                                        </table>
                                                    </layout>
                                                </repeater>
                                            </td>
                                            <td class="w30" width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr><td class="w640" width="640" height="15" bgcolor="#ebebeb"></td></tr>
                        <tr>
                            <td class="w640" width="640">
                                <table id="footer" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#585D61">
                                    <tbody>
                                        <tr>
                                            <td class="w30" width="30"></td>
                                            <td class="w580 h0" width="360" height="30"></td>
                                            <td class="w0" width="60"></td>
                                            <td class="w0" width="160"></td>
                                            <td class="w30" width="30"></td>
                                        </tr>
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
                                        <tr>
                                            <td class="w30" width="30"></td>
                                            <td class="w580 h0" width="360" height="15"></td>
                                            <td class="w0" width="60"></td><td class="w0" width="160"></td>
                                            <td class="w30" width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr><td class="w640" width="640" height="60"></td></tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<p>Dirección: Av. Neverí, Quinta Mireille, Colinas de Bello Monte, Caracas - Venezuela.</p>
<p>Recibió este correo porque usted está registrado en LealTag</p>
EOM
, array(
    "%FULLNAME%" => $fullname,
    "%ASSET%" => $asset,
    "%FEEDBACK%" => $feedback,
))
?>
