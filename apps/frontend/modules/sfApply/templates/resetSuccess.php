<?php use_helper('I18N') ?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div class="main-container-inner">
        <div id="reset-request-container" class="main-canvas box_round gray-background">
            <div id="reset-request-title" class="main-canvas-title lightblue">
                <?php echo __('Aquí podrás modificar tu contraseña') ?>
            </div>
            <div id="reset-request-content" class="main-canvas-content">
                <div class="main-canvas-subtitle">
                    Indica cual es la nueva contraseña que deseas utilizar en el formulario que aparece a continuación.
                </div>
                <div id="reset-request-form-container" class="form-container">
                    <form method="post" action="<?php echo url_for("reset") ?>" name="sf_apply_reset_form" id="sf_apply_reset_form">
                        <div class="form-canvas box_round white-background">
                            <?php echo $form ?>
                        </div>
                        <div class="form_submit">
                            <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" type="submit" value="<?php echo __("Modificar Contraseña") ?>" />
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