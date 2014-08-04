<h1>Listado de cupones</h1>
<div>
    <a href="<?php echo url_for('promo_redeem_coupon_validation', $promo)?>">Canjear cupón</a>
</div>
<table>
    <?php include_partial('listCoupon', array('coupons' => $coupons))?>
</table>
