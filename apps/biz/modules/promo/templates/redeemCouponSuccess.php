<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('coupon_validation' => true))) ?>
    <?php include_partial('redeemCouponForm', array('form' => $form, 'serial' => $serial, 'footer' => true)) ?>
</div>