<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
¡Comienza a disfrutar tus premios!

%FULLNAME%,

Gracias por comenzar a usar LealTag, a partir de este segundo empezarás a ser premiado por
visitar los sitios que te gustan.

Para disfrutar de tus premios primero debes completar los datos de tu cuenta ingresando a este link

%1%

Una vez completes tu cuenta, podrás canjear tus premios y disfrutar de todo lo 
que en LealTag traemos para ti.

¡Gracias por ser un cliente frecuente!

El equipo de LealTag ;)

Dirección: Av. Neverí, Quinta Mireille, Colinas de Bello Monte, Caracas - Venezuela.
Recibió este correo porque usted está registrado en LealTag
EOM
         , array(
    "%1%" => $route1,
    '%FULLNAME%' => $fullname
        )
)
?>
