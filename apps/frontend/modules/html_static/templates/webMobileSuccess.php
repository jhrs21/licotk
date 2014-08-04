<div id="wm-content">
    <?php if ($sf_user->hasFlash('lt_pts_awarded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo sfOutputEscaper::unescape($sf_user->getFlash('lt_pts_awarded')) ?></p>
        </div>
    <?php endif; ?>
    <img border="0" src="<?php echo Util::auto_version('/images/fotowebmobile.png') ?>" alt="Lealtag"></img> 
    <div class="wm-bloc">
        <div onclick="location.href='<?php echo url_for('download_descarga') ?>';" class="lt-button lt-button-darkblue box_round wm-button">Descarga la aplicación</div>
        <div onclick="location.href='<?php echo url_for('apply') ?>';" class="wm-button lt-button lt-button-darkblue box_round">Regístrate</div>
        <div onclick="location.href='<?php echo url_for('homepage') ?>?vt=1';" class="wm-button lt-button lt-button-darkblue box_round">Ver página completa</div>
    </div>
</div>        
