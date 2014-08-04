<?php use_helper('I18N') ?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="main-canvas-title lightblue">
                <?php echo __('¿Olvidaste tu contraseña?') . '<br>' . __('¡No te preocupes!') ?>
            </div>
            <div class="main-canvas-content">
                <p>
                    Sólo ingresa la dirección de correo que registraste en tu cuenta
                    y haz clic en el botón "Modificar Contraseña".
                </p>
                <p>
                    Recibirás un correo con un link que te permitirá modificar tu contraseña.
                </p>
                <div id="reset-request-form-container" class="form-container">
                    <form method="post" action="<?php echo url_for('sfApply/resetRequest') ?>"
                          name="sf_apply_reset_request" id="sf_apply_reset_request">
                        <div class="form-canvas box_round white-background">
                            <?php echo $form ?>
                        </div>
                        <div class="form_submit">
                            <input id="reset-request-input" class="lt-button lt-button-blue box_round opensanscondensedlight submit" type="submit" value="<?php echo __("Modificar Contraseña") ?>" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php echo link_to(__("Cancelar"), sfConfig::get('app_sfApplyPlugin_after', '@homepage')) ?></b>
            </div>
        </div>
    </div>
</div>
