<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash_notice flash_error box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('error') ?></p>
        </div>
    <?php endif; ?>
    <div class="main-canvas box_round gray-background">
        <div id="signin-form-title" class="main-canvas-title lightblue">
            <?php echo __('¡Gracias por registrarte!') ?>
        </div>
        <div class="memo-container">
            <p>
                Pronto recibirás un correo electrónico para verificar tu cuenta.
                <br>
                Comienza a disfrutar de todo lo que <b>LealTag</b> trae para ti.
            </p>
            <p>
                ¡Mereces ser premiado!
                <br>
                El equipo de LealTag ;)
            </p>
        </div>
        <div class="main-canvas-footer">
            <div class="lightgray-separator separator"></div>
            <?php include_partial('sfApply/continue') ?>
        </div>
    </div>
</div>