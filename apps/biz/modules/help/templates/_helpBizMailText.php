<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
¡Un negocio ha planteado una pregunta o sugerencia!
Nombre: %name%

Negocio: %asset%

Email del usuario: %email%

Mensaje del usuario: %message%

Correo enviado automáticamente por el sistema del Biz LealTag
EOM
, array(
        "%name%" => $name,
        "%email%" => $email,
        '%message%' => $message,
        '%asset%' => $asset,
    )
) ?>
