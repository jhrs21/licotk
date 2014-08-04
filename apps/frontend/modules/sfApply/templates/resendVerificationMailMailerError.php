<?php use_helper('I18N') ?>
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
                    Ha ocurrido un inconveniente durante el proceso de envío de correo. Por favor intenta luego nuevamente.
                </p>
                <p>
                    Por favor, vuelve a intentarlo haciendo clic 
                    <b class="darkblue"><?php echo link_to('AQUÍ', url_for('resend_verification', array('user_alpha' => $userId)))?></b>.
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