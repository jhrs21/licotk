<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('api2/emailsPartials/styles/bodyContentStyle') ?>">
            <h2 style="<?php include_partial('api2/emailsPartials/styles/h2Style', array('fontColor' => 'ffd900')) ?>">
                %FULLNAME%,
            </h2>
            <br>
            Si pagaste tu consumo en <b>%ASSET%</b> con tu tarjeta Maestro o Mastercard, <b>participa por una 
            de las dos tarjetas pre-pagadas MasterCard de Bs.2500</b> que rifaremos cada mes. Para participar 
            haz clic en el siguiente 
            <b><a style="<?php include_partial('api2/emailsPartials/styles/linksStyle', array('fontColor' => 'ffd900')) ?>"
                  href="%ROUTE%">LINK</a></b> y completa el formulario que aparecerá en pantalla.
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
                            ¡Vamos a vivirlo!
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
            <b>Recuerda guardar el comprobante de pago</b>, ya que lo necesitarás en caso que resultes ganador en esta 
            rifa que MasterCard/Maestro y LealTag traen para ti.
            <br><br>
            Sigue visitando y no dejes de ganar en tus comercios favoritos con LealTag
        </div>
    </td>
</tr>
<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('api2/emailsPartials/styles/bodyFooterStyle') ?>">
            Mereces ser premiado
            <br>
            El equipo de LealTag ;)
        </div>
    </td>
</tr>