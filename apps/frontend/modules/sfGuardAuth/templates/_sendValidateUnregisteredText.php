<?php use_helper('I18N', 'Url') ?>
<?php echo __(<<<EOM
    ¡Queremos que formes parte de LealTag!
        
        
    %email%,
    

    Para que puedas ser premiado por visitar los sitios que te gustan valida tu 
    dirección de correo haciendo clic en este link
    

    %2%
    

    Si tienes algún inconveniente siguiendo el link anterior, copialo y pegalo en la
    barra de direcciones de tu navegador.

 
    También podrás disfrutar de <b>LealTag</b> bajarte la aplicación desde tu 
    Blackberry (versión 5.0 en adelante), iPhone (u otro dispositivo Apple iOS) 
    o Android desde el link www.lealtag.com/descarga


    Busca el regalito LealTag que identifica a los comercios afiliados para empezar
    a ganar; y si tus locales preferidos no forman parte de LealTag, pídeles que te
    premien.


    Para más información o si no cuentas con un teléfono móvil de este tipo, entra a
    nuestra página web www.lealtag.com.


    Tus comentarios y sugerencias nos ayudan a mejorar, así que cualquier cosa
    escríbenos a info@lealtag.com y síguenos en Twitter <a href="http://www.twitter.com/lealtag">@lealtag</a> para enterarte de
    todas las cosas nuevas que vamos a traer para ti.



    Mereces ser premiado
 
    El equipo de LealTag ;)
EOM
, array(
        "%1%" => $route,
        "%2%" => $route,
        "%3%" => $route,
        '%email%' => $email
    )
) ?>