<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    ¡Felicitaciones has canjeado un Premio!
        
    
    ¡Felicitaciones %FULLNAME%, acabas de canjear un Premio en %ASSET%!
    
    
    Tu opinión es importante para nosotros


    Puedes compartir tu experiencia en %ASSET% haciendo click en el siguiente link:


    %FEEDBACK%
    

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
    "%ASSET%" => $asset,
    "%FEEDBACK%" => $feedback,
))
?>