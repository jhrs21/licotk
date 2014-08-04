<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="error-face"></div>
            <div class="error-title lightblue">
                <?php echo __('Algo no está bien...') ?>
            </div>
            <div class="error-content">
                <p>Aún no has verificado tu cuenta de <a target="_blank" href="http://www.tudescuenton.com">TuDescuenton.com</a></p>
                <p>
                    Para poder usar tu cuenta de <a target="_blank" href="http://www.tudescuenton.com">TuDescuenton.com</a> 
                    en LealTag debes verificarla primero.
                </p>
                <br>
                <p>Disculpa las molestias.</p>
                <p>El equipo de LealTag</p>
            </div>
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php include_partial('sfApply/continue') ?></b>
            </div>
        </div>
    </div>
</div>