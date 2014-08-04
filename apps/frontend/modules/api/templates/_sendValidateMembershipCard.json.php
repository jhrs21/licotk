<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
   
<p>
    <a href="http://www.lealtag.com"><img alt="LealTag" src="http://lealtag.com/images/logo_slogan.png"/></a>
</p>   
<p>
    <h1>¡Activaste una Tarjeta LealTag!</h1>
</p>
<p>
    <b>%FULLNAME%<b/>,
</p>
<p>
    Has activado una nueva%REPLACEMARK% Tarjeta LealTag, el identificador de tu
    nueva tarjeta es %MCARD%.
</p>
%REPLACE%
<p>
    Para poder canjear tus premios utilizando tu nueva Tarjeta validala en este <a href="%1%">link</a>:
</p>
<p>
    %2%
</p>
<p>
    Recuerda que puedes bajarte la aplicación desde tu Blackberry (versión 5.0 en adelante),
    iPhone (u otro dispositivo Apple iOS) o Android desde la dirección: www.lealtag.com/descarga
    <br>
    <br>
    La aplicación te permite, además de obtener Tags y PuntosLT, poder ver que comercios
    y marcas te premian, ver tus premios y puntos acumulados y mucho más.
    <br>
    <br>
    Para más información o para acceder a tu cuenta y ver tus premios, entra a
    nuestra página web www.lealtag.com
</p>
<p>
    Busca el regalito LealTag que identifica a los comercios afiliados para empezar
    a ganar; y si tus locales preferidos no forman parte de LealTag, pídeles que te
    premien.
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
EOM
, array(
        "%l%" => url_for("card/confirm?validate=$validate&user=$user", true),
        "%2%" => link_to(url_for("card/confirm?validate=$validate&user=$user", true), "card/confirm?validate=$validate&user=$user", array("absolute" => true)),
        '%FULLNAME%' => $fullname,
        '%REPLACEMARK%' => ($replace ? '(*)' : ''),
        '%REPLACE%' => ($replace ? '<p>(*) Esta nueva tarjeta reemplazará a la anterior, no te preocupes por tus Tags y Premios, nada se perderá.</p>' : ''),
        '%MCARD%' => $mcard
    )
) ?>
