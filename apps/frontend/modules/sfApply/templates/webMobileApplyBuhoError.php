<?php use_helper('I18N') ?>
<div id="wm-content">
    <div class="top-separator-colors box_shadow_bottom">
        <div class="purple-background"></div>
        <div class="blue-background"></div>
        <div class="orange-background"></div>
    </div> 
    <div class="wm-bloc">
        <img id="error-face" border="0" src="/images/error_face.png" alt="Lealtag"></img> 
        <div id="error-message" class="main-canvas-title lightblue">
            <?php echo __('Algo no está bien...') ?>
        </div>
        <div id="apply-mailer-error-content" class="main-canvas-content apply-content">
            <p>
                Hubo un inconveniente al momento de crear tu cuenta.
            </p>
            <p>
                Por favor, contáctanos en la siguiente dirección de correo 
                <a href="mailto:soporte@lealtag.com
                   ?subject='Error en la creación de cuenta'
                   &body='
                   <?php echo 'Correo indicado: ' . $email ?>
                   <?php
                   foreach ($errors as $key => $error) {
                       echo 'Error: ' . $key . '<br>Mensaje: ' . $error;
                   }
                   ?>
                   '">
                    soporte@lealtag.com
                </a>
            </p>
            <p>
                Disculpa las molestias.
            </p>
            <p>El equipo de LealTag</p>
            <div class="main-canvas-content-footer">
<?php include_partial('sfApply/continue') ?>                    
            </div>
        </div>
    </div>
</div>