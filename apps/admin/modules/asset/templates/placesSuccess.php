<h1>Establecimientos</h1>

<a href="<?php echo url_for('asset_new_place')?>">Nuevo Establecimiento</a>

<table>
    <thead>
        <tr>
            <th>Afiliado</th>
            <th>Nombre</th>
            <th>Categoria</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($assets as $asset): ?>
            <tr>
                <td><a href="<?php echo url_for('affiliate_show', $asset->getAffiliate()) ?>"><?php echo $asset->getAffiliate() ?></a></td>
                <td><a href="<?php echo url_for('asset_show_place', $asset) ?>"><?php echo $asset ?></a></td>
                <td><?php echo $asset->getCategory(); ?></td>
                <td>
                    <a href="<?php echo url_for('asset_show_place', $asset) ?>">Detalles</a>
                    <a href="<?php echo url_for('asset_edit_place', $asset) ?>">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="<?php echo url_for('asset_new_place')?>">Nuevo Establecimiento</a>