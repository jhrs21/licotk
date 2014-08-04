<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash_notice flash_error box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('error') ?></p>
        </div>
    <?php endif; ?>
    <div class="main-canvas box_round gray-background">
        <div id="signin-form-title" class="main-canvas-title lightblue">
            <?php echo __('¡Ingresa a tu cuenta!', null, 'sf_guard') ?>
        </div>
        <div class="form-container">
            <?php include_partial('sfGuardAuth/newLoginForm', array('form' => $form)) ?>
        </div>
	<!--
        	<div class="main-canvas-footer">
        	    <div class="lightgray-separator separator"></div>
        	    ¿Tienes una cuenta en <a target="_blank" href="http://www.tudescuenton.com">TuDescuenton.com</a>?
        	    &nbsp;&nbsp;
        	    ¡Úsala para ingresar!
        	</div>
	-->
        <div id="login-left-top-corner" class="lt-corner lt-corner-small lt-corner-small-tl"></div>
        <div id="login-right-bottom-corner" class="lt-corner lt-corner-big lt-corner-big-br"></div>
    </div>
</div>
<script type="text/javascript">
    var effect = 'blind';
    var options = {};
    $('.flash_notice').click(function(){$(this).hide( effect, options, 1000);});
</script>
