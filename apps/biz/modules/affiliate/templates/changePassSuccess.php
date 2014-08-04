<?php use_helper('I18N') ?>
<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu',array('isActive'=>array('change_pass'=>true)))?>
    <div id="redeem-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Modificar Contraseña</h2>
        </div>
        <div class="main-canvas-content">
            <div id="redeem-form-container">
                <form action="<?php echo url_for('affiliate_change_pass') ?>"
                    method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
                    <div class="white-frame">
                        <?php echo $form ?>
                    </div>
                    <div class="form_footer">
                        <input class="form_submit"type="submit" value="Cambiar" />
                    </div>
                </form>
            </div>
            <div class="main-canvas-content-footer">
                <a href="<?php echo url_for('analytics') ?>">Cancelar</a>
            </div>
        </div>
    </div>
</div>