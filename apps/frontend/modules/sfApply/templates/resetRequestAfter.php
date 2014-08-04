<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash_notice flash_error box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('error') ?></p>
        </div>
    <?php endif; ?>
    <div class="main-canvas box_round gray-background">
        <div id="signin-form-title" class="main-canvas-title lightblue">
            <?php echo __('¡Revisa tu correo!') ?>
        </div>
        <div class="memo-container">
            <p>
                Por razones de seguridad hemos enviado un correo electrónico de confirmación.
                Por favor, verifica que has recibido ese mensaje en tu correo.
            </p>
            <br>
            <p>
                Deberás hacer clic en el link que te hemos facilitado en ese mensaje para que
                puedas cambiar tu contraseña.
            </p>
            <br>
            <p>
                En caso de que no veas ese correo, asegurate de revisar las carpetas de "spam" de tu manejador de correos.
            </p> 
            <br>
            <p>El equipo de Licoteca</p>
        </div>
        <div class="main-canvas-footer">
            <div class="lightgray-separator separator"></div>
            <?php include_partial('sfApply/continue') ?>
        </div>
    </div>
</div>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
