<h1>Códigos de promoción</h1>

<table>
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Status</th>
            <th>Fecha de uso</th>
            <th>Promoción</th>
            <th>Usuario</th>
            <th>Local/Producto</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($promo_codes as $promo_code): ?>
            <tr>
                <td><?php echo $promo_code->getType() ?></td>
                <td><?php echo $promo_code->getStatus() ?></td>
                <td><?php echo $promo_code->getUsedAt() ?></td>
                <td><?php echo $promo_code->getPromo() ?></td>
                <td><?php echo $promo_code->getUser() ?></td>
                <td><?php echo $promo_code->getAsset() ?></td>
                <td><a href="<?php echo url_for('promo_code/printQR?id='.$promo_code->getId().'&affiliate='.$affiliate->getId())?>">Generar PDF</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!--<a href="<?php echo url_for('promo_code_new', $affiliate) ?>">Crear nuevo</a>  <a href="<?php echo url_for('promo_code_activate', $affiliate) ?>">Activar lote</a> <a href="<?php echo url_for('promo_code_deactivate', $affiliate) ?>">Desactivar lote</a>-->
