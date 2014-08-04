<?php use_helper('I18N') ?>
<div id="wm-content">
    <div class="top-separator-colors box_shadow_bottom">
        <div class="purple-background"></div>
        <div class="blue-background"></div>
        <div class="orange-background"></div>
    </div> 
    <div class="wm-bloc">
        <img id="white-smile" border="0" src="/images/white_smile.png" alt="Lealtag"></img> 
        <div id="thankyou-message" class="main-canvas-title lightblue">
            <?php echo __('¡Gracias por registrarte!') ?>
        </div>
        <div class="main-canvas-content">
            <p>
                Pronto recibirás un correo electrónico para verificar tu cuenta.
                <br>
                Comienza a disfrutar de todo lo que <b>LealTag</b> trae para ti.
            </p>
            <p>
                ¡Mereces ser premiado!
                <br>
                El equipo de LealTag ;)
            </p>
        </div>
        <div class="main-canvas-footer">
            <div class="lightgray-separator separator"></div>
            <?php include_partial('sfApply/continue') ?>
        </div>
    </div>
</div>
