<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('userHeader', array('isActive' => array('user_register_tag' => true)))?>
    <?php if ($sf_user->hasFlash('tag_registration_succeeded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('tag_registration_succeeded') ?></p>
        </div>
    <?php endif ?>
    <div id="register-tag-form-main" class="main-canvas box_round gray-background">
        <div class="main-canvas-title lightblue">
            <?php echo __("Registrar visita") ?>
        </div>
        <div id="register-tag-form-container" class="form-container">
            <?php include_partial('tagForm',array('form' => $form))?>
        </div>
        <div class="main-canvas-footer">
            <div class="lightgray-separator separator"></div>
            <a href="<?php echo url_for('user_prizes') ?>">Volver a Mis Premios</a>
        </div>
        <div class="lt-corner lt-corner-small lt-corner-small-tl"></div>
        <div class="lt-corner lt-corner-big lt-corner-big-br"></div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var effect = 'blind';
        var options = {};
        $('.flash_notice').click(function(){
            $(this).hide( effect, options, 1000);
        });
    });
</script>

