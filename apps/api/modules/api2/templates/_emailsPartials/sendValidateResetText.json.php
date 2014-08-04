<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    Hola %FULLNAME%, hemos recibido una solicitud para modificar tu contraseña.

    Haz clic en el link y sigue las instrucciones para ingresar una contraseña nueva.

    %1%

    Tu contraseña actual no será modificada a menos que hagas clic en el link y completes el formulario.

    Dirección: Av. Neverí, Quinta Mireille, Colinas de Bello Monte, Caracas - Venezuela.
    Recibió este correo porque usted está registrado en LealTag
EOM
, array(
"%1%" => $route1,
"%FULLNAME%" => $fullname
)
)
?>