<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('coupon_validation' => true))) ?>
    <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash flash_error">
            <p class="flash_message"><?php echo $sf_user->getFlash('error') ?></p>
        </div>
    <?php endif ?>
    <div id="redeem-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Buscar Premios del Cliente</h2>
        </div>
        <?php include_partial('searchUserPrizesForm', array('form' => $userForm, 'footer' => true))?>
    </div>
</div>