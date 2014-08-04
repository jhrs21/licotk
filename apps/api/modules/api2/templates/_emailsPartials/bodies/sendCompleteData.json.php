<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('api2/emailsPartials/styles/bodyContentStyle') ?>">
            <h2 style="<?php include_partial('api2/emailsPartials/styles/h2Style', array('fontColor' => 'ffd900')) ?>">
                ¡Comienza a disfrutar tus premios!
            </h2>
            <br>
            <b>%FULLNAME%,</b>
            <br><br>
            Sólo falta un paso para disfrutar todos los premios que Licoteca ofrece para ti. 
            Por favor, completa tus datos usando este 
            <b><a style="<?php include_partial('api2/emailsPartials/styles/linksStyle', array('fontColor' => 'ffd900')) ?>"
                  href="%ROUTE%">LINK</a></b>:
        </div>
    </td>
</tr>
<tr>
    <td align="center" valign="top">
        <table border="0" cellpadding="15" cellspacing="0" class="templateButton" style="<?php include_partial('api2/emailsPartials/styles/buttonContainerStyle') ?>">
            <tr>
                <td valign="middle" class="templateButtonContent">
                    <div>
                        <a href="%ROUTE%" target="_blank" style="<?php include_partial('api2/emailsPartials/styles/buttonStyle') ?>">
                            Completar Datos
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('api2/emailsPartials/styles/bodyContentStyle') ?>">
            <em>Si tienes algún inconveniente con el link anterior, copia y pega esto en la barra de direcciones de tu navegador:</em>
            <br>
            <em>
                <a href="%ROUTE%" style="<?php include_partial('api2/emailsPartials/styles/linksStyle', array('fontColor' => 'ffd900')) ?>">
                    %ROUTE%
                </a>
            </em>
            <br><br>
            Una vez completes los datos de tu cuenta, podrás canjear tus premios y disfrutar de todo lo que Licoteca trae para ti.
            <br><br>
            Pide una tarjeta del Club Licoteca en la tienda o imprímela 
            <a style="<?php include_partial('api2/emailsPartials/styles/linksStyle', array('fontColor' => 'ffd900')) ?>" 
               href="https://www.lealtag.com/usuario/tarjeta">AQUÍ</a>.
            <br><br>
            ¡Gracias por ser un cliente frecuente!
        </div>
    </td>
</tr>
<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('api2/emailsPartials/styles/bodyFooterStyle') ?>">
            El equipo de Licoteca.
        </div>
    </td>
</tr>