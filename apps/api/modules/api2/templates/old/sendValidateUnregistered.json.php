<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
<p>
    <a href="http://www.lealtag.com"><img alt="LealTag" src="http://lealtag.com/images/logo_slogan.png"/></a>
</p>   
<p>
    <h1>¡Queremos que formes parte de LealTag!</h1>
</p>
<p>
    <b>%email%<b/>,
</p>
<p>
    Para que puedas ser premiado por visitar los sitios que te gustan valida tu 
    dirección de correo haciendo clic en este <a href="%1%">link</a>.
</p>
<p>
    Si tienes algún inconveniente siguiendo el link anterior, copia y pega la dirección siguiente en la
    barra de direcciones de tu navegador:
    <br>
    <a href="%3%">%2%</a>
</p>
<p>
    También podrás disfrutar de <b>LealTag</b> bajarte la aplicación desde tu 
    Blackberry (versión 5.0 en adelante), iPhone (u otro dispositivo Apple iOS) 
    o Android desde el link <a href="http://www.lealtag.com/descarga">www.lealtag.com/descarga</a>
</p>
<p>
    Busca el regalito LealTag que identifica a los comercios afiliados para empezar
    a ganar; y si tus locales preferidos no forman parte de LealTag, pídeles que te
    premien.
</p>
<p>
    Para más información o si no cuentas con un teléfono móvil de este tipo, entra a
    nuestra página web <a href="http://www.lealtag.com">www.lealtag.com</a>.
</p>
<p>
    Tus comentarios y sugerencias nos ayudan a mejorar, así que cualquier cosa
    escríbenos a info@lealtag.com y síguenos en Twitter <a href="http://www.twitter.com/lealtag">@lealtag</a> para enterarte de
    todas las cosas nuevas que vamos a traer para ti.
</p>

<p>
    Mereces ser premiado
</p> 
<p>
    El equipo de LealTag ;)
</p>
EOM
, array(
        "%1%" => $route,
        "%2%" => $route,
        "%3%" => $route,
        '%email%' => $email
    )
) ?>
