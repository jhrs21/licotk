<?php use_helper('I18N') ?>
<div class="top-separator-colors box_shadow_bottom">
    <div class="purple-background"></div>
    <div class="blue-background"></div>
    <div class="orange-background"></div>
</div>
<div class="main-canvas-title lightblue">
    <?php echo __("¡Completa tu cuenta!") ?>
</div>
<form id="sign-up-form" method="post" action="<?php echo url_for('user_complete_register',array('validate' => $validate)) ?>">
    <div class="form-canvas box_round white-background">
        <?php echo $form ?>
        <div class="form-canvas-footer"></div>
    </div>
    <div class="form_submit">
        <p>¿Ya estás registrado? Ingresa <a href="<?php echo url_for('sf_guard_signin') ?>">AQUÍ</a></p>
        <input class="lt-button lt-button-blue box_round opensanscondensedlight submit wm-submit-button" 
               type="submit" value="<?php echo __("Completar e Ingresar") ?>" />
    </div>
</form>