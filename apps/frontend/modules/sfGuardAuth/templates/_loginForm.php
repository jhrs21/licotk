<?php use_helper('I18N') ?>
<form id="login-form" action="<?php echo url_for('@sf_guard_login') ?>" method="post">
    <div class="form-canvas">
        <?php echo $form ?>
    </div>
    <div class="signin-form-submit-container">
        <a class="signin-form-forgot" href="<?php echo url_for('@sf_guard_password') ?>"><?php echo __('¿Olvidaste tu contraseña?', null, 'sf_guard') ?></a>
        <br>
        <input class="submit" type="submit" value="<?php echo __('Ingresar', null, 'sf_guard') ?>" />
    </div>
</form>