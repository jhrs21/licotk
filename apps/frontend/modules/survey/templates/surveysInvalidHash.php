<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div id="surveys-forms-container" class="main-canvas box_round gray-background">
        <div id="apply-mailer-error-container" class="main-canvas">
            <div class="main-canvas-title lightblue">
                <?php echo __('Algo no está bien...') ?>
            </div>
            <div id="apply-mailer-error-content" class="main-canvas-content apply-content">
                <p>
                    No hemos podido encontrar la información necesaria para que puedas compartir
                    con nostros tu experiencia.
                </p>
                <p>
                    Si copiaste y pegaste el link que envíamos a tu correo electrónico, 
                    por favor, verifica que lo hayas copiado completo y sin modificaciones.
                </p>
                <p>
                    Disculpa las molestias.
                </p>
                <p>
                    El equipo de LealTag
                </p>
            </div>
        </div>
        <div class="main-canvas-footer">
            <div class="lightgray-separator separator"></div>
            <a href="<?php echo url_for('user_prizes') ?>">Ir a Mis Premios</a>
        </div>
        <div id="signup-left-top-corner" class="lt-corner lt-corner-small lt-corner-small-tl"></div>
        <div id="signup-right-bottom-corner" class="lt-corner lt-corner-big lt-corner-big-br"></div>
    </div>
</div>