<?php use_helper('I18N') ?>
<div id="wm-content">
    <div class="top-separator-colors box_shadow_bottom">
        <div class="purple-background"></div>
        <div class="blue-background"></div>
        <div class="orange-background"></div>
    </div> 
    <div class="wm-bloc">
        <img id="white-smile" border="0" src="/images/white_smile.png" alt="Lealtag"></img> 
        <div id="success-message-title" class="main-canvas-title lightblue">
            <?php echo __('¡Perfecto!') ?>
        </div>
        <div class="success-message">
        <p>
            Tu contraseña ha sido cambiada exitosamente.
            <br>
            Recuerda que esta nueva contraseña será la utilizarás para ingresar en 
            <a target="_blank" href="http://www.tudescuenton.com">TuDescuenton.com</a>
        </p>
        <p>
            Para ver tus premios haz clic <a href="<?php echo url_for('user_prizes') ?>">aquí</a>.
        </p>
        <p>
            ¡Mereces ser premiado!
            <br>
            El equipo de LealTag ;)
        </p>
        </div>
    </div>
</div>
