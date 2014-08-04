<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    ¡%WELCOME% ahora tienes una cuenta en LealTag!


    %FULLNAME%,


    Felicitaciones por recibir tu primer tag en %ASSET%, ya estas siendo premiado 
    por visitar los sitios que te gustan.


    Ahora sólo debes bajarte la aplicación desde tu Blackberry (versión 5.0 en adelante),
    iPhone (u otro dispositivo Apple iOS) o Android desde el link 
    <a href="http://www.lealtag.com/descarga">www.lealtag.com/descarga</a> o en la 
    tienda de aplicaciones correspondiente a tu dispositivo.


    Busca el regalito LealTag que identifica a los comercios afiliados para empezar
    a ganar; y si tus locales preferidos no forman parte de LealTag, pídeles que te
    premien.


    Para más información o si no cuentas con un teléfono móvil de este tipo, entra a
    nuestra página web www.lealtag.com para acceder a tu cuenta LealTag y ver tus
    premios.


    Tus comentarios y sugerencias nos ayudan a mejorar, así que cualquier cosa
    escríbenos a info@lealtag.com y síguenos en Twitter <a href="http://www.twitter.com/lealtag">@lealtag</a> para enterarte de
    todas las cosas nuevas que vamos a traer para ti.



    Mereces ser premiado
 
    El equipo de LealTag ;)
EOM
, array(
        '%FULLNAME%' => $fullname,
        '%WELCOME%' => '<b>'.$fullname.'<b/>',
        '%ASSET%' => $asset,
    )
) ?>