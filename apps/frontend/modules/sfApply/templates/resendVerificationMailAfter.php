<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="main-canvas-title lightblue">
                <?php echo __('¡Revisa tu correo!') ?>
            </div>
            <div class="main-canvas-content">
                <p>
                    Por razones de seguridad hemos enviado un correo electrónico de confirmación.
                    Por favor, verifica que has recibido ese mensaje en tu correo.
                </p>
                <br>
                <p>
                    Deberás hacer clic en el link que te hemos facilitado en ese correo para verificar tu cuenta.
                </p>
                <p>
                    En caso de que no veas ese correo, asegurate de revisar las carpetas de "spam" de tu manejador de correos.
                </p>
                <br>
                <p>Disculpa las molestias.</p>
                <p>El equipo de Licoteca</p>
            </div>
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php include_partial('sfApply/continue') ?></b>
            </div>
        </div>
    </div>
</div>
