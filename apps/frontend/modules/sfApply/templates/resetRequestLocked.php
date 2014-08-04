<?php use_helper('I18N') ?>
<div id="main-container" class="span-24">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-container-inner">
        <div id="apply-mailer-error-container" class="main-canvas">
            <div id="apply-mailer-error-title" class="main-canvas-title">
                <h2><?php echo __('Algo no está bien...') ?></h2>
            </div>
            <div id="apply-mailer-error-content" class="main-canvas-content apply-content">
                <p>
                    Tu cuenta está inactiva.
                </p>
                <br>
                <p>
                    Por favor, contactanos en la siguiente dirección de correo 
                    <a href="mailto:soporte@lealtag.com?subject='Cuenta de LealTag inactiva'">
                        soporte@lealtag.com
                    </a>
                </p>
                <br>
                <p>
                    Disculpa las molestias.
                </p>
                <br>
                <p>El equipo de LealTag</p>
                <div class="main-canvas-content-footer">
                    <?php include_partial('sfApply/continue') ?>                    
                </div>
            </div>
        </div>
    </div>
</div>
