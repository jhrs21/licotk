<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="error-title lightblue">
                <?php echo __('¡Revisa tu correo!') ?>
            </div>
            <div class="error-content">
                <p>
                    Te hemos enviado un correo electrónico para que puedas ingresar a tu cuenta.
                </p>
                <p>
                    Ingresa al link que encontrarás en el mismo y completa la información.
                </p>
                <br>
                <p>
                    En caso de que no veas ese correo, asegúrate de revisar las carpetas de "spam" de tu manejador de correos.
                </p>
                <br>
                <p>El equipo de Licoteca</p>
            </div>
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php include_partial('sfApply/continue') ?></b>
            </div>
        </div>
    </div>
</div>
