<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    ¡Completa tu cuenta de LealTag!


    %FULLNAME%,


    Para disfrutar de tus premios primero debes completar tus datos ingresando a este link:

    %2%
 
    Una vez completes tu cuenta, podrás canjear tus premios y disfrutar de todo lo 
    que en LealTag traemos para ti.
    

    ¡Gracias por ser un cliente frecuente!

 
    El equipo de LealTag ;)
EOM
, array(
        "%1%" => $route1,
        "%2%" => $route2,
        "%3%" => $route3,
        '%FULLNAME%' => $fullname
    )
) ?>