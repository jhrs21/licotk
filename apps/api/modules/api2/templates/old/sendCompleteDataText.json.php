<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    ¡Completa tu cuenta de LealTag!


    Hola %FULLNAME%,


    Gracias por comenzar a usar LealTag, a partir de este segundo empezarás a ser premiado por visitar los sitios que te gustan.
    
    Ahora solo debes acceder a la página web www.lealtag.com o descargarte la aplicación en tu Blackberry (versión 5.0 en adelante), iPhone (u otro dispositivo Apple iOS) o Android desde el link www.lealtag.com/descarga para culminar tu registro.
    
    Cada vez que salgas, busca el regalito de LealTag con la frase “AQUÍ TE PREMIAMOS” que identifica a los comercios y marcas afiliadas y comienza así a acumular Tags. Si alguno de tus comercios o marcas afiliadas favoritas no es parte de LealTag, sugiéreles que se unan y comienza a recibir premios.
                                                
    Con tu cuenta de LealTag también puedes ingresar a Tudescuenton.com y conseguir los mejores descuentos de tu ciudad. Utiliza el mismo correo electrónico y contraseña al momento de ingresar en tudescuenton.com y gana tags por comprar cupones y ahorrar.

    No olvides que tus comentarios y sugerencias nos ayudan a mejorar, así que cualquier cosa escríbenos a info@lealtag.com y síguenos en Twitter @lealtag para enterarte de todas las cosas nuevas que tenemos para ti.
    
    ¡Mereces ser premiado!
    
    El equipo de LealTag ;)
EOM
, array(
        "%1%" => $route1,
        "%2%" => $route2,
        "%3%" => $route3,
        '%FULLNAME%' => $fullname
    )
) ?>