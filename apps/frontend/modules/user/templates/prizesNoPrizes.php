<?php use_helper('I18N') ?>
<?php slot('page_title', 'Tus premios | Licoteca') ?>
<?php slot('metas') ?>
    <meta content="" name="abstract"/>
    <meta content="Los premios que has obtenido por ser un cliente fiel" name="description"/>
    <meta content="" name="keywords"/>
    <meta content="" name="keyphrases"/>
    <meta content="index, follow" name="robots"/>
    <meta content="website" property="og:type"/>
    <meta content="Licoteca" property="og:site_name"/>
    <meta content="Los premios que he obtenido por ser un cliente fiel" property="og:description"/>
<?php end_slot() ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('userHeader', array('isActive' => array('user_prizes' => true)))?>
    <?php if ($sf_user->hasFlash('profile_update_succeeded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('profile_update_succeeded') ?></p>
        </div>
    <?php endif; ?>
    <?php if ($sf_user->hasFlash('tag_registration_succeeded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('tag_registration_succeeded') ?></p>
        </div>
    <?php endif ?>
    <div id="register-tag-form-main" class="main-canvas box_round gray-background">
        <div class="main-canvas-title lightblue">
            <?php echo __("Aún no tienes premios...") ?>
        </div>
        <div class="main-canvas-subtitle darkgray">
            <br><br><?php echo __("¡Es hora de comenzar a obtener premios! Escanea tu tarjeta del Club Licoteca en el negocio más cercano.") ?><br><br><br><br>
        </div>
        <!-- <div id="register-tag-form-container" class="form-container">
            <?php include_partial('tagForm',array('form' => $form))?>
        </div> -->
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
