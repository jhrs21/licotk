<?php use_helper('I18N', 'Url') ?>

<?php echo __(<<<EOM
    ¡Activaste una Tarjeta LealTag!


    %FULLNAME%,


    Has activado una nueva%REPLACEMARK% Tarjeta LealTag, el identificador de tu
    nueva tarjeta es %MCARD%.
    
    %REPLACE%
    
    Para poder canjear tus premios utilizando tu nueva Tarjeta validala en este link:

    %2%


    Recuerda que puedes bajarte la aplicación desde tu Blackberry (versión 5.0 en adelante),
    iPhone (u otro dispositivo Apple iOS) o Android desde la dirección: www.lealtag.com/descarga
    
    La aplicación te permite, además de obtener Tags y PuntosLT, poder ver que comercios
    y marcas te premian, ver tus premios y puntos acumulados y mucho más.

    Para más información o para acceder a tu cuenta y ver tus premios, entra a
    nuestra página web www.lealtag.com

    Busca el regalito LealTag que identifica a los comercios afiliados para empezar
    a ganar; y si tus locales preferidos no forman parte de LealTag, pídeles que te
    premien.

    Tus comentarios y sugerencias nos ayudan a mejorar, así que cualquier cosa
    escríbenos a info@lealtag.com y síguenos en Twitter <a href="http://www.twitter.com/lealtag">@lealtag</a> 
    para enterarte de todas las cosas nuevas que vamos a traer para ti.


    Mereces ser premiado
 
    El equipo de LealTag ;)
EOM
, array(
        "%l%" => url_for("card/confirm?validate=$validate&user=$user", true),
        "%2%" => link_to(url_for("card/confirm?validate=$validate&user=$user", true), "card/confirm?validate=$validate&user=$user", array("absolute" => true)),
        '%FULLNAME%' => $fullname,
        '%REPLACEMARK%' => ($replace ? '(*)' : ''),
        '%REPLACE%' => ($replace ? '(*) Esta nueva tarjeta reemplazará a la anterior, no te preocupes por tus Tags y Premios, nada se perderá.' : ''),
        '%MCARD%' => $mcard
    )
) ?>