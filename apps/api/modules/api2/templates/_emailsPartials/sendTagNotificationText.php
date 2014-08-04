<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    %FULLNAME%,
        
    Si pagaste tu consumo en %ASSET% con tu tarjeta Maestro o Mastercard, 
    participa por una de las dos tarjetas pre-pagadas MasterCard de Bs.2500 que 
    rifaremos cada mes, ingresa el siguiente link en la barra de direcciones 
    de tu explorador:
    
    %URL%
    
    Recuerda guardar el comprobante de pago, ya que lo necesitarás en caso que 
    resultes ganador en esta rifa que MasterCard/Maestro y LealTag traen para ti.

    Sigue visitando y no dejes de ganar en tus comercios favoritos con LealTag

    Mereces ser premiado

    El equipo de LealTag ;)

    Dirección: Av. Neverí, Quinta Mireille, Colinas de Bello Monte, Caracas - Venezuela.
    Recibió este correo porque usted está registrado en LealTag
EOM
, array(
"%FULLNAME%" => $fullname,
"%ASSET%" => $asset,
"%URL%" => $route,
))
?>
