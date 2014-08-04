<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    ¡Felicitaciones has recibido un Tag!
        
    
    ¡Felicitaciones %FULLNAME%, acabas de recibir un Tag en %ASSET%!
    

    Conoce el estado de tus premios descargando la aplicación desde tu Blackberry 
    (versión 5.0 en adelante), iPhone (u otro dispositivo Apple iOS) o Android desde 
    el link www.lealtag.com/descarga o en la tienda de aplicaciones. Si no tienes 
    uno de estos equipos, ingresa a tu cuenta en www.lealtag.com
    

    Sigue visitando y siendo premiado en tus comercios favoritos con LealTag
    

    Mereces ser premiado
    

    El equipo de LealTag ;)
EOM
, array(
        "%FULLNAME%" => $fullname,
        "%ASSET%" => $asset
    )
) ?>