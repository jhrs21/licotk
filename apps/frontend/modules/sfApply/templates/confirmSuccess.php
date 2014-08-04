<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div class="main-canvas box_round gray-background">
        <div id="signin-form-title" class="main-canvas-title lightblue">
            <?php echo __('¡Tu cuenta ha sido activada!') ?>
        </div>
        <div class="memo-container">
            <p>Se ha iniciado tu sesión en <b>Licoteca</b></p>
            <p>Desde ya puedes comenzar a acumular puntos que luego podrás canjear por premios.</p>
            <br>
            <p>El equipo de Licoteca</p>
        </div>
        <div class="main-canvas-footer">
            <div class="lightgray-separator separator"></div>
            <?php include_partial('sfApply/continue') ?>
        </div>
    </div>
</div>
