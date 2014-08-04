<?php use_helper('I18N') ?>
<div class="main-container-inner">
    <div id="auth-error-container" class="main-canvas">
        <div id="signin-form-title" class="main-canvas-title">
            <h2><?php echo __('Oops! Permisos insuficientes.', null, 'sf_guard') ?></h2>
        </div>
        
<!--        <p><?php echo sfContext::getInstance()->getRequest()->getUri() ?></p>
        <h3><?php echo __('Login below to gain access', null, 'sf_guard') ?></h3>
        <?php echo get_component('sfGuardAuth', 'signin_form') ?> -->
    </div>
</div>
