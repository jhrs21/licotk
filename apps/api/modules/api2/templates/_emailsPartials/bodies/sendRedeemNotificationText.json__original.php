<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    ¡Felicitaciones %FULLNAME%, acabas canjear un premio en %ASSET%!

    Cuéntale a %ASSET% que tal ha sido tu experiencia al visitarlos y gana puntos LealTag haciendo clic en %FEEDBACK%

    Si tienes algún inconveniente siguiendo el link anterior, copia y pega la dirección siguiente en la
    barra de direcciones de tu navegador:

    %FEEDBACK%

    Conoce el estado de tus premios descargando la aplicación desde tu Blackberry, 
    iPhone o Android desde el siguiente link www.lealtag.com/descarga o en la tienda de aplicaciones. Si no tienes 
    uno de estos equipos, ingresa a tu cuenta en www.lealtag.com

    Sigue visitando y siendo premiado en tus comercios favoritos con LealTag

    Mereces ser premiado

    El equipo de LealTag ;)

    Dirección: Av. Neverí, Quinta Mireille, Colinas de Bello Monte, Caracas - Venezuela.
    Recibió este correo porque usted está registrado en LealTag
EOM
, array(
"%FULLNAME%" => $fullname,
"%FEEDBACK%" => $feedback,
"%ASSET%" => $asset
)
)
?>
