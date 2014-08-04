<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('email/styles/bodyContentStyle') ?>">
            <h2 style="<?php include_partial('email/styles/h2Style', array('fontColor' => '006bb5')) ?>">
                %FULLNAME%,
            </h2>
            <br>
            Has recibido un Tag en <b>%ASSET%</b>. Tu opinión es importante para nosotros, por eso queremos 
            conocer como fue tu experiencia. Por favor, 
            <b><a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => '009ddf')) ?>"
                  href="%ROUTE%">compartela con nosotros</a></b>:
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
                            Compartir mi experiencia
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
            Sigue visitando y no dejes de ganar en tus comercios favoritos con LealTag
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
