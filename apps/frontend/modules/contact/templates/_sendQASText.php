<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    ¡Un usuario ha planteado una pregunta o sugerencia!
        
    Nombre del usuario:%name%

    Email del usuario: %email%

    Mensaje del usuario: %message%

    Correo enviado automáticamente por el sistema LealTag

EOM
, array(
        "%name%" => $name,
        "%email%" => $email,
        '%message%' => $message,
    )
) ?>