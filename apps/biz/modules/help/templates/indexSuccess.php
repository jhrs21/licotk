<?php use_helper('I18N') ?>
<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('help' => true))) ?>
    <?php if ($sf_user->hasFlash('email_success')): ?>
        <div class="flash flash_success">
            <p class="flash_message"><?php echo $sf_user->getFlash('email_success') ?></p>
        </div>
    <?php endif ?>
    <div id="analytics-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Envíe su pregunta</h2>
        </div>
        <div class="main-canvas-content">
            <div id="help-form-container">
                <div class="white-frame">
                    <form id="help-form" action="<?php echo url_for('help_index') ?>" method="post">
                        <div class="help-form-inner">
                            <?php echo $form ?>
                        </div>
                        <div class="form_footer">
                            <input class="form_submit" type="submit" value="Enviar" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="main-canvas-content-footer">
                <a href="<?php echo url_for('promo') ?>">Regresar a Mis Promociones</a>
            </div>
        </div>
    </div>
</div>