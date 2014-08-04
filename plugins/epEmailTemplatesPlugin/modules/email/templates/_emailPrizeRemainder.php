<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('email/styles/bodyContentStyle') ?>">
            <h2 style="<?php include_partial('email/styles/h2Style', array('fontColor' => '006bb5')) ?>">
                ¡%FULLNAME%, tienes un premio listo para disfrutar en %AFFILIATE%!
            </h2>
            <br>
            No pierdas la oportunidad de disfrutar este increíble premio. Canjéalo en %AFFILIATE% usando tu tarjeta 
            LealTag o imprime el cupón en el siguiente 
            <b><a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => '009ddf')) ?>"
                  href="%ROUTE%">LINK
                </a></b>:
        </div>
    </td>
</tr>
<tr>
    <td align="center" valign="top">
        <table border="0" cellpadding="15" cellspacing="0" class="templateButton" style="<?php include_partial('email/styles/buttonContainerStyle') ?>">
            <tr>
                <td valign="middle" class="templateButtonContent">
                    <div>
                        <a href="%ROUTE%" target="_blank" style="<?php include_partial('email/styles/buttonStyle') ?>">
                            ¡Ver Premio!
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('email/styles/bodyContentStyle') ?>">
            <em>Si tienes algún inconveniente con el link anterior, copia y pega esto en la barra de direcciones de tu navegador:</em>
            <br>
            <em>
                <a href="%ROUTE%" style="<?php include_partial('email/styles/linksStyle', array('fontColor' => '009ddf')) ?>">
                    %ROUTE%
                </a>
            </em>
            <br><br>
            Ahí encontraras todos los establecimientos y condiciones para que puedas realizar el canje.
            <br><br>
            También puedes hacerlo desde la aplicación en tu Blackberry, iPhone o Android haciendo clic 
            <a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => '009ddf')) ?>" 
                             href="http://www.lealtag.com/descarga">AQUÍ</a>.
            <br><br>
            Si no tienes uno de estos equipos, pide una tarjeta LealTag en un comercio afiliado o imprímela 
            <a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => '009ddf')) ?>" 
               href="https://www.lealtag.com/usuario/tarjeta">AQUÍ</a>.
            <br><br>
            Sigue visitando y recibiendo premios en tus comercios favoritos con LealTag.
        </div>
    </td>
</tr>
<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('email/styles/bodyFooterStyle') ?>">
            Mereces ser premiado
            <br>
            El equipo de LealTag ;)
        </div>
    </td>
</tr>