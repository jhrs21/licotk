<?php use_helper('I18N') ?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash_notice flash_error box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('error') ?></p>
        </div>
    <?php endif; ?>
    <div class="main-canvas box_round gray-background">
        <div id="signin-form-title" class="main-canvas-title lightblue">
            <?php echo __('¡Crea tu cuenta!', null, 'sf_guard') ?>
        </div>
        <div class="form-container">
            <?php include_partial('sfApply/newApplyForm', array('form' => $form, 'showTermCheck' => true)) ?>
        </div>
	<!--
        	<div class="main-canvas-footer">
        	    <div class="lightgray-separator separator"></div>
        	    ¿Tienes una cuenta en TuDescuenton.com? Úsala para ingresar <a href="<?php echo url_for('sf_guard_signin')?>">AQUÍ</a>
        	</div>
	-->
        <div id="signup-left-top-corner" class="lt-corner lt-corner-small lt-corner-small-tl"></div>
        <div id="signup-right-bottom-corner" class="lt-corner lt-corner-big lt-corner-big-br"></div>
    </div>
</div>
