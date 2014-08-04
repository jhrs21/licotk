<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background many-canvas">
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas-title lightblue">
            <?php echo __('¡Tu mensaje ha sido enviado!') ?>
        </div>
        <div class="memo-container">
            <p>
                Gracias por compartir tu inquietudes o sugerencias sobre <b>LealTag</b>
            </p>
            <p>
                Pronto nuestro equipo dará respuestas a tus preguntas.
            </p>
            <p>
                Queremos ofercerte el mejor servicio posible, por eso tu opinión es importante para nosotros.
            </p>
            <br>
            <p>¡Mereces ser premiado!</p> 
            <p>El equipo de LealTag ;)</p>
        </div>
        <div class="main-canvas-footer">
            <div class="lightgray-separator separator"></div>
            <?php include_partial('sfApply/continue') ?>
        </div>
    </div>
</div>