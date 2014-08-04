<?php use_helper('I18N') ?>

<div id="signin-form">
    <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
        <div class="white-frame form_container">
                <?php echo $form ?>
        </div>
        <div class="form_footer">
            <?php if (isset($routes['sf_guard_forgot_password'])): ?>
                <a href="<?php echo url_for('@sf_guard_forgot_password') ?>"><?php echo __('¿Olvidaste tu contraseña?', null, 'sf_guard') ?></a>
                <br>
            <?php endif; ?>
            <input class="form_submit" type="submit" value="<?php echo __('Acceder', null, 'sf_guard') ?>" />
        </div>
    </form>
</div>