<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="error-title lightblue">
                <?php echo __('No has verificado tu cuenta de LealTag.') ?>
            </div>
            <div class="error-content">
                <p>
                    Debes verificar tu cuenta antes de poder ingresar o modificar tu contraseña.
                </p>
                <br>
                <p>
                    Te hemos enviado nuevamente un correo de verificación, el cual contiene las instrucciones
                    para que verefiques tu cuenta.
                </p>
                <br>
                <p>
                    Si no encuentras el correo en tu bandeja de entrada, recuerda revisar en la carpeta de spam.
                </p>
                <br>
                <p>Disculpa las molestias.</p>
                <p>El equipo de LealTag</p>
            </div>
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php include_partial('sfApply/continue') ?></b>
            </div>
        </div>
    </div>
</div>