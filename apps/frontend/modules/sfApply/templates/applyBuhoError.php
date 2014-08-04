<?php use_helper('I18N') ?>

<?php $body = 'Hay un problema al intentar registrarme con mi email "'.$email.'". La respuesta del sistema ha sido: Error '.$error['code'].', Mensaje: '.$error['message']; ?>

<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="error-face"></div>
            <div class="error-title lightblue" class="main-canvas-title ">
                <?php echo __('Algo no está bien...') ?>
            </div>
            <div class="error-content">
                <p>
                    Hubo un inconveniente al momento de crear tu cuenta.
                </p>
                <p>
                    Por favor, contactanos en la siguiente dirección de correo 
                    <b class="darkblue">
                        <a target="_blank" href="mailto:soporte@lealtag.com?subject=<?php echo urlencode('Error en la creación de cuenta - ErrorBuho ') ?>&body=<?php echo urlencode($body) ?>">
                            soporte@lealtag.com
                        </a>
                    </b>
                </p>
                <br>
                <p>
                    Disculpa las molestias.
                </p>
                <p>El equipo de LealTag</p>
            </div>
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php include_partial('sfApply/continue') ?></b>
            </div>
        </div>
    </div>
</div>