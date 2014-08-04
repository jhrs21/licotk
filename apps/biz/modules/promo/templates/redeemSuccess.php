<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu',array('isActive'=>array('coupon_validation'=>true)))?>
    <?php if ($sf_user->hasFlash('success')): ?>
        <div class="flash flash_success">
            <p class="flash_message"><?php echo $sf_user->getFlash('success') ?></p>
        </div>
    <?php endif ?>
    <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash flash_error">
            <p class="flash_message"><?php echo $sf_user->getFlash('error') ?></p>
        </div>
    <?php endif ?>
    <div id="redeem-container" class="main-canvas">
        <div id="redeem-container-leftside">
            <div class="main-canvas-title">
                <h2>Canjear Premio</h2>
            </div>
            <?php include_partial('redeemCouponForm', array('form' => $couponForm, 'serial' => false, 'footer' => false))?>
        </div>
        <div id="redeem-container-rightside">
            <div class="main-canvas-title">
                <h2>Buscar Premios del Cliente</h2>
            </div>
            <?php include_partial('searchUserPrizesForm', array('form' => $userForm, 'footer' => false))?>
        </div>
        <div id="redeem-container-footer" class="main-canvas-content-footer" >
            <a href="<?php echo url_for('promo') ?>">Regresar a Mis Promociones</a>
        </div>
    </div>
</div>