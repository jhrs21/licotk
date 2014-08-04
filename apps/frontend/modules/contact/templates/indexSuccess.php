<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div class="contact-form-container">
        <?php if ($sf_user->hasFlash('send_email_succeeded')): ?>
            <div id="success_message" class="flash_notice flash_success box_round box_shadow_bottom"><?php echo $sf_user->getFlash('send_email_succeeded') ?></div>
        <?php endif ?>
        <div class="main-container-inner">
            <div id="contact-container" class="main-canvas box_round gray-background">
                <div id="reset-request-title" class="main-canvas-title lightblue">
                    <?php echo __('Envíanos los datos de tu negocio') ?>
                </div>
                <div id="contact-form-container" class="form-container">
                    <?php include_partial('contact', array('form' => $form)) ?>
                </div>
                <div id="link-biz" onclick="location.href='/biz.php';"></div>
            </div>
        </div>
    </div>
</div>