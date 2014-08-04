<?php $isActive = $sf_data->getRaw('isActive')?>
<div id="top-menu-container">
    <ul class="top-menu">
        <?php if (!$sf_user->getGuardUser()->hasGroup('admin')):?>
            <li class="option <?php echo array_key_exists('analytics',$isActive) && $isActive['analytics'] ? 'active' : '' ?>">
                <a href="<?php echo url_for('analytics') ?>">Mis estadísticas</a>
            </li>
        <?php else: ?>
            <li class="option <?php echo array_key_exists('analytics',$isActive) && $isActive['analytics'] ? 'active' : '' ?>">
                <a><?php echo link_to("Mis estadísticas", 'analytics', array('id' => $sf_user->getGuardUser()->getId())) ?></a>
            </li>
        <?php endif; ?>
        <li class="option <?php echo array_key_exists('promo',$isActive) && $isActive['promo'] ? 'active' : '' ?>">
            <a href="<?php echo url_for('promo')?>">Mis promociones</a>
        </li>
        <li class="option <?php echo array_key_exists('coupon_validation',$isActive) && $isActive['coupon_validation'] ? 'active' : '' ?>">
            <a href="<?php echo url_for('promo_redeem') ?>">Canjear un premio</a>
        </li>
        <li class="option <?php echo array_key_exists('give_tag',$isActive) && $isActive['give_tag'] ? 'active' : '' ?>">
            <a href="<?php echo url_for('give_tag') ?>">Acreditar una Visita</a>
        </li>
        <li class="option <?php echo array_key_exists('change_pass',$isActive) && $isActive['change_pass'] ? 'active' : '' ?>">
            <a href="<?php echo url_for('affiliate_change_pass') ?>">Cambiar contraseña</a>
        </li>
<!--
	<li class="option <?php echo array_key_exists('survey',$isActive) && $isActive['survey'] ? 'active' : '' ?>">
            <a href="<?php echo url_for('survey') ?>">Encuestas</a>
        </li>
        <li class="option last <?php echo array_key_exists('help',$isActive) && $isActive['help'] ? 'active' : '' ?>">
            <a href="<?php echo url_for('help_index') ?>">Ayuda</a>
        </li>
-->
    </ul>
</div>
