<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div id="surveys-forms-container" class="main-canvas box_round gray-background">
        <div id="apply-mailer-error-container" class="main-canvas">
            <div class="main-canvas-title lightblue">
                <?php echo __('Ingresa a tu cuenta para compartir tu opinión') ?>
            </div>
            <div id="apply-mailer-error-content" class="main-canvas-content apply-content">
                <p>
                    Había una sesión para otro usuario iniciada en tu navegador. Por razones de
                    seguridad hemos cerrado esa sesión.
                </p>
                <p>
                    Para compartir tu experiencia e iniciar tu sesión has clic 
                    <a href="<?php echo url_for('survey_feedback', array(), true).'?h='.$participationRequest->getHash(); ?>">AQUÍ</a>
                </p>
                <p>
                    Recuerda que tu opinión es muy importante para nosotros, disculpa las molestias.
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