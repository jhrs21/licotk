<?php $isActive = $sf_data->getRaw('isActive')?>
<div class="user-header">
    <ul class="user-links">
        <li <?php echo array_key_exists('user_prizes',$isActive) && $isActive['user_prizes'] ? 'class="active"' : '' ?>>
            <a class="darkgray" href="<?php echo url_for('user_prizes') ?>">Mis Premios</a>
        </li>
<!--        <li <?php echo array_key_exists('user_register_tag',$isActive) && $isActive['user_register_tag'] ? 'class="active"' : '' ?>>
            <a class="darkgray" href="<?php echo url_for('user_new_ticket')?>">Registrar un código</a>
        </li>
-->
        <li <?php echo array_key_exists('user_update',$isActive) && $isActive['user_update'] ? 'class="active"' : '' ?>>
            <a class="darkgray" href="<?php echo url_for('sfApply/update') ?>">Mis Datos</a>
        </li>
        <li>
            <a class="darkgray" target="_blank" href="<?php echo url_for('generate_membership_card') ?>">Mi Tarjeta Virtual</a>
        </li>
    </ul>
</div>
