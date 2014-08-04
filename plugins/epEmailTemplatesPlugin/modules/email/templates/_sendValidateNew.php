<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('email/styles/bodyContentStyle') ?>">
            <h2 style="<?php include_partial('email/styles/h2Style', array('fontColor' => 'ffd900')) ?>">
                ¡%WELCOME% a Licoteca!
            </h2>
            <br><br>
            Gracias por registrarte, el primer paso para terminar tu registro debe ser
            validar tu cuenta en este
            <b><a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => 'ffd900')) ?>"
                  href="%ROUTE%">link
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
                            Validar mi cuenta
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
                <a href="%ROUTE%" style="<?php include_partial('email/styles/linksStyle', array('fontColor' => 'ffd900')) ?>">
                    %ROUTE%
                </a>
            </em>
            <br><br>
            Conoce el estado de tus premios y mucho más desde la página web 
	    <a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => 'ffd900')) ?>" 
                             href="http://club.licoteca.com.ve/usuario/premios">club.licoteca.com.ve</a>.
            <br><br>
            Para acumular puntos debes acercarte a tu tienda Licoteca más cercana y tomar una tarjeta de membresía
	    o imprimirla a través de este 
            <a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => 'ffd900')) ?>" 
               href="http:/club.licoteca.com.ve/usuario/tarjeta">enlace.</a>.
            <br><br>
            Tus comentarios y sugerencias nos ayudan a mejorar, así que cualquier cosa
            escríbenos a
            <a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => 'ffd900')) ?>" target="_blank" 
               href="mailto:info@lealtag.com">info@lealtag.com</a>
            y síguenos en Twitter
            <a style="<?php include_partial('email/styles/linksStyle', array('fontColor' => 'ffd900')) ?>" target="_blank" 
                href="http://www.twitter.com/licotecave">@licotecave</a>
            para enterarte de todas las cosas nuevas que vamos a traer para ti.
        </div>
    </td>
</tr>
<tr>
    <td valign="top" class="bodyContent">
        <div style="<?php include_partial('email/styles/bodyFooterStyle') ?>">
            Atentamente, Licoteca
        </div>
    </td>
</tr>
