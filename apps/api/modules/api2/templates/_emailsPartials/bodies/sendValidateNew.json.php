<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('api2/emailsPartials/styles/bodyContentStyle') ?>">
            <h2 style="<?php include_partial('api2/emailsPartials/styles/h2Style', array('fontColor' => 'ffd900')) ?>">
                ¡%WELCOME% al Club Licoteca!
            </h2>
            <br>
            <b>%FULLNAME%<b/>,
            <br><br>
            Gracias por visitar <b>Licoteca</b>. Para validar tu cuenta ingresa en este 
            <b><a style="<?php include_partial('api2/emailsPartials/styles/linksStyle', array('fontColor' => 'ffd900')) ?>"
                  href="%ROUTE%">LINK
                </a></b>:
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
                            Validar mi cuenta.
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
            Conoce el estado de tus premios pidiendo una tarjeta del Club Licoteca en la tienda o imprímela 
            <a style="<?php include_partial('api2/emailsPartials/styles/linksStyle', array('fontColor' => 'ffd900')) ?>" 
               href="https://club.licoteca.com.ve/usuario/tarjeta">AQUÍ</a>.
            <br><br>
            Tus comentarios y sugerencias nos ayudan a mejorar, así que cualquier cosa
            escríbenos a 
            <a style="<?php include_partial('api2/emailsPartials/styles/linksStyle', array('fontColor' => 'ffd900')) ?>" target="_blank" 
               href="mailto:info@licoteca.com.ve">info@licoteca.com.ve</a>
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