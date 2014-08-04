<div class="generated-qr">
    <img class="qr-code" src="<?php echo Util::auto_version($suffix) ?>">
    <?php if ($sf_user->hasFlash('no_pc_validation_required')): ?>
        <div id="no-digital-qr" class="flash_notice box_round box_shadow_bottom"><?php echo $sf_user->getFlash('no_pc_validation_required') ?></div>
    <?php endif ?>
</div>
<div class="vcode-embedded">
    <div class="validation-code"><b>Código:</b> <?php echo $vcode ?></div>
</div>