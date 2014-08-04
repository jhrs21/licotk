<h1>Marcas</h1>

<a href="<?php echo url_for('asset_new_brand')?>">Registrar nueva Marca</a>

<table>
    <thead>
        <tr>
            <th>Afiliado</th>
            <th>Nombre</th>
            <th>Categorias</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($assets as $asset): ?>
            <tr>
                <td><a href="<?php echo url_for('affiliate_show', $asset->getAffiliate()) ?>"><?php echo $asset->getAffiliate() ?></a></td>
                <td><a href="<?php echo url_for('asset_show_brand', $asset) ?>"><?php echo $asset ?></a></td>
                <td><?php echo $asset->getCategory(); ?></td>
                <td>
                    <a href="<?php echo url_for('asset_show_brand', $asset) ?>">Detalles</a>
                    <a href="<?php echo url_for('asset_edit_brand', $asset) ?>">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="<?php echo url_for('asset_new_brand')?>">Registrar nueva Marca</a>