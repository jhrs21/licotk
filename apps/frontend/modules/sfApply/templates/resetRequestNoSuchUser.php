<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="error-title lightblue">
                <?php echo __('¿Estás seguro de estar registrado?') ?>
            </div>
            <div class="error-content">
                <p>
                    Hubo un inconveniente, no hemos encontrado ningún usuario registrado con el correo indicado.
                </p>
                <p>
                    Por favor, vuelve a intentarlo haciendo clic <b class="darkblue"><?php echo link_to('AQUÍ', url_for('sf_guard_forgot_password'))?></b>.
                </p>
                <br>
                <p>
                    Disculpa las molestias.
                </p>
                <p>El equipo de Licoteca</p>
            </div>
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php include_partial('sfApply/continue') ?></b>
            </div>
        </div>
    </div>
</div>
