<?php use_helper('I18N') ?>
<div class="top-separator-colors box_shadow_bottom">
                <div class="purple-background"></div>
                <div class="blue-background"></div>
                <div class="orange-background"></div>
</div>
<form id="sign-up-form" method="post" action="<?php echo url_for('sfApply/apply') ?>">
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put"/>
    <?php endif; ?>
    <div class="form-canvas box_round white-background">
        <?php echo $form ?>
        <?php if (isset($showTermCheck) && $showTermCheck) : ?>
            <div class="form_row">
                <div class="form_row_label">
                    <label for="sfApplyApply_privacyPolicy">
                        Acepto los <a href="<?php echo url_for('privacy_policy') ?>" target="_blank">Términos de Privacidad</a>
                    </label>
                    <input type="checkbox" name="sfApplyApply[privacyPolicy]" id="sfApplyApply_privacyPolicy" data-bvalidator-msg="Debes aceptar los terminos de privacidad para crear tu cuenta" data-bvalidator="required">
                </div>
            </div>
        <?php endif; ?>
        <div class="form-canvas-footer"></div>
    </div>
    <div class="form_submit">
        <p>¿Ya estás registrado? Ingresa <a href="<?php echo url_for('sf_guard_signin') ?>">AQUÍ</a></p>
        <input class="lt-button lt-button-blue box_round opensanscondensedlight submit wm-submit-button" 
               type="submit" value="<?php echo __('Regístrate') ?>" />
    </div>
</form>