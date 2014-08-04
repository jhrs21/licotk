<?php use_helper('I18N') ?>
<div class="top-separator-colors box_shadow_bottom">
    <div class="purple-background"></div>
    <div class="blue-background"></div>
    <div class="orange-background"></div>
</div>
<div id="reset-request-title" class="main-canvas-title lightblue">
    <?php echo __('¿Olvidaste tu contraseña?') . '<br>' . __('¡No te preocupes!') ?>
</div>
<form method="post" action="<?php echo url_for('sfApply/resetRequest') ?>" name="sf_apply_reset_request" id="sf_apply_reset_request">
    <div class="form-canvas box_round white-background">
        <?php echo $form ?>
    </div>
    <div class="form_submit">
        <input id="modify-password-button" class="lt-button lt-button-blue box_round opensanscondensedlight wm-submit-button" type="submit" value="<?php echo __("Modificar Contraseña") ?>" />
    </div>
</form>
<div class="main-canvas-footer">
    <div class="lightgray-separator separator"></div>
    <p><?php echo link_to(__("Cancelar"), sfConfig::get('app_sfApplyPlugin_after', '@homepage')) ?></p>             
</div>