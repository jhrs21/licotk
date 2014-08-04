<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('api2/emailsPartials/styles/bodyContentStyle') ?>">
            <h2 style="<?php include_partial('api2/emailsPartials/styles/h2Style', array('fontColor' => 'ffd900')) ?>">
                Modificación de contraseña
            </h2>
            <br>
            <b>%FULLNAME%,</b>
            <br><br>
            Hemos recibido una solicitud para modificar tu contraseña. Para realizar el cambio has clic en el 
            siguiente 
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
                            Modificar mi contraseña
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
            Si tu no realizaste esta solicitud ignora este correo, tu contraseña no será modificada.
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