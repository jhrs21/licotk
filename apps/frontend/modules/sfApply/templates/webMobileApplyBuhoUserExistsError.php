<?php use_helper('I18N') ?>
<div id="wm-content">
    <div class="top-separator-colors box_shadow_bottom">
        <div class="purple-background"></div>
        <div class="blue-background"></div>
        <div class="orange-background"></div>
    </div> 
    <div class="wm-bloc">
        <img id="error-face" border="0" src="/images/ingresaConTdGde.png" alt="Lealtag"></img> 
        <div id="error-message" class="main-canvas-title lightblue">
                <?php echo __('¡Ya tienes una cuenta en TuDescuenton.com!') ?>
            </div>
            <div id="apply-mailer-error-content" class="main-canvas-content apply-content">
                <p>
                    Puedes <a id="login-td-button" href="<?php echo url_for('sf_guard_signin')?>">ingresar</a> en LealTag con el mismo correo y contraseña.
                </p>
                <div id="login-td-button-container">
                    <a id="login-td-button" href="<?php echo url_for('sf_guard_signin')?>"><span></span></a>
                </div>
                <p>El equipo de LealTag ;)</p>
                <div class="main-canvas-content-footer">
                    <?php include_partial('sfApply/continue') ?>                    
                </div>
            </div>
    </div>
</div>