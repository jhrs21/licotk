<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div id="faqs" class="main-container-inner">
        <div class="main-canvas box_round gray-background">
            <div class="main-canvas-title lightblue">
                <?php echo __('¿Qué debo saber sobre LealTag?') ?>
            </div>
            <div class="main-canvas-content">
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Qué es LealTag?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Imagina que al visitar tus comercios favoritos pudieras recibir premios, descuentos o regalos simplemente por ser 
                            un cliente frecuente.
                        </p>
                        <p>
                            LealTag es la plataforma que te recompensa por ser un cliente fiel. Es muy fácil comenzar a recibir premios, solo 
                            y regístrate gratis, descarga la aplicación o imprime tu tarjeta, y al visitar un comercio afiliado diríjete a la 
                            caja y pide LealTag. Aquí versa un tablet LealTag donde podrás recibir el tag de tu visita diréctamente desde tu 
                            aplicación o con tu tarjeta. Si no hay tablet, no importa, pídeles que te premien con tu aplicación, com tu tarjeta 
                            o con tu email.
                        </p>
                        <p>
                            De esa manera vas a generar un Tag o una visita, cada comercio requiere un número de tags para premiarte.
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Cómo descargo la aplicación?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Para descargar la aplicación visita: <a href="<?php url_for('download_descarga') ?>">www.lealtag.com/descarga</a> 
                            o busca "Lealtag" en la tienda de aplicaciones desde tu Blackberry, iPhone o Android. ¿Te encanta escanear códigos 
                            QR? Descárgala escaneando este:
                        </p>
                        <img id="faq-download-qr" width="170px" height="240px" src="/images/download_qr.png"/>
                        <p>
                            Si no tienes un celular compatlble no importa, solo regístrate e imprime tu tarjeta LealTag 
                            <a href="<?php url_for('generate_membership_card') ?>">aquí.</a> 
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿LealTag tiene algún costo para los usuarios?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            No. tanto registrarte e imprimir tu tarjeta y descargarte la aplicación es completamente gratuito. Solo pide LealTag 
                            en los comercios afiliados y comienza a ser premiado en tus locales favoritos por ser un cliente frecuente.
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Qué debo hacer si no tengo teléfono inteligente?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Si no cuentas con un teléfono inteligente compatible, puedes registrar tu cuenta en 
                            <a href="<?php url_for('homepage') ?>">www.lealtag.com</a> completamente gratis y descargar tu tarjeta LealTag 
                            para recibir tus premios por ser un cliente frecuente.
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Debo estar registrado para disfrutar de LealTag?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Sí. Para utilizar la aplicación LealTag deberás registrarte colocando tus datos personales la primera vez que te 
                            registres. Una vez lo hagas (por la aplicación o por la página web) podrás acceder a tus premios desde cualquier 
                            equipo celular o computadora.
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Sale error al tratar de registrarte con una cuenta de TuDescueton.com?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Si ya cuentas con una cuenta de TuDescuenton.com ingresa a LealTag con tu correo y contraseña (el que usas para 
                            comprar cupones), completa la información personal faltante (en caso de que este sea el caso) y comienza a disfrutar. 
                            Para recibir tus premios de Tudescuentón.com, DEBES usar la misma cuenta, de lo contrario no te podremos premiar.
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Qué es un Tag?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Un Tag es el crédito que recibes en cada comercio afiliado por ser un cliente frecuente. Lo puedes recibir escaneando el 
                            código QR que te mostrarán al pagar (presionando el botón “Escanear” desde tu app, o presentando tu tarjeta LealTag y 
                            siguiendo las instrucciones que te darán)
                        </p>
                        <p>
                            Cada comercio requiere un número distinto de tags para premiarte (Ej: En tu tercera visita (2 tags previos) recibes 25% 
                            de descuento en tu consumo.)
                        </p>
                        <p>
                            Los Tags de un comercio son de ese mismo, no pueden ser utilizados en otro comercio diferente.
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Cómo canjeo un premio?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Una vez completas los tags necesarios para un premio diríjete al comercio afiliado con tu tarjeta LealTag o con tu 
                            aplicación, (desde el app oprime el botón canjear y selecciona tu premio). También puedes imprimirlo desde tu cuenta 
                            en <a href="<?php url_for('homepage') ?>">www.lealtag.com</a>.
                        </p>
                        <p>
                            Diríjete al tablet LealTag (o a la caja) y procede a canjear tu premio oprimiendo el botón canjear y escaneando el 
                            código QR.
                        </p>
                        <p>
                            Cualquier duda pregúntale a la persona encargada de la caja en tu establecimiento.
                        </p>
                        <p>
                            Recuerda que una vez uses tu premio, consumirás los tags correspondiente. Si quieres alcanzar nuevos premios, deberás 
                            seguir escaneando.
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Cómo puedo saber el número de tags y premios que he alcanzado?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Para ver el status de tus promociones, puedes ingresar a tu cuenta en 
                            <a href="<?php url_for('homepage') ?>">www.lealtag.com</a> o desde la aplicación móvil ingresando al ícono de "Premios".
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Qué son los puntos LealTag?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Los puntos LealTag son puntos que obtienes por utilizar tu aplicación. Cada vez que escanees, hagas retroalimentación a 
                            un comercio o canjees tus premios obtendrás puntos que podrás ver en la esquina derecha de tu aplicación. Muy pronto 
                            podrás obtener premios y participar en rifas con tus puntos LealTag.
                        </p>
                        <p>
                            Los puntos LealTag son independientes de los tags que obtienes en los comercios.
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Qué teléfonos pueden instalar la aplicación móvil LealTag?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Para utilizar la nueva aplicación de LealTag sólo se requiere cualquier modelo de teléfono inteligente Blackberry 
                            (Sistema operativo V5 o superior), Android con cámara trasera (Sistema operativo 2.2 o superior) o dispositivo con Apple 
                            iOS (Sistema Operativo 4 o superior).
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('La aplicación me indica que debo actualizarla ¿Qué debo hacer?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            Debes seguir las instrucciones dadas por la tienda de aplicaciones de tu equipo para mantener actualizada la aplicación 
                            de LealTag. Cada versión contará con mejoras, y nuevas funciones para ti.
                        </p>
                        <p>
                            Ante cualquier error te recomendamos contactarnos a <a href="mailto:soporte@lealtag.com">soporte@lealtag.com</a>
                        </p>
                    </div>
                </div>
                <div class="faq-container box_round white-background">
                    <div class="faq-title main-canvas-title darkgray">
                        <b><?php echo __('¿Por qué la aplicación funciona más rápido en algunos momentos que en otros?') ?></b>
                        <div class="down-arrow"></div>
                    </div>
                    <div class="faq-hide">
                        <p>
                            La aplicación de LealTag se comunica con nuestros servidores para realizar cualquiera de las interacciones. 
                            La velocidad de estas descargas se ve afectada por la señal disponible en el lugar donde te encuentres.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".down-arrow").click(function () {
        $(this).parent().next().slideToggle('slow');
        $(this).toggleClass("transformed");
    });
</script>