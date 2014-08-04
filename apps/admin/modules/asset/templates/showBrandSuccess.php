<table>
    <tbody>
        <tr>
            <th>Afiliado:</th>
            <td><a href="<?php echo url_for('affiliate_show', $asset->getAffiliate()) ?>"><?php echo $asset->getAffiliate() ?></a></td>
        </tr>
        <tr>
            <th>Nombre:</th>
            <td><?php echo $asset->getName() ?></td>
        </tr>
        <tr>
            <th>Descripción:</th>
            <td><?php echo $asset->getDescription() ?></td>
        </tr>
        <tr>
            <th>Logo:</th>
            <td><img src="/uploads/<?php echo $asset->getLogo() ?>"/></td>
        </tr>
        <tr>
            <th>Miniatura:</th>
            <td><img src="/uploads/<?php echo $asset->getThumb() ?>"/></td>
        </tr>
        <tr>
            <th>Categoria:</th>
            <td><?php echo $asset->getCategory() ?></td>
        </tr>
        <tr>
            <th>Created at:</th>
            <td><?php echo $asset->getDateTimeObject('created_at')->format('d/m/Y - g:i:s a') ?></td>
        </tr>
        <tr>
            <th>Updated at:</th>
            <td><?php echo $asset->getDateTimeObject('updated_at')->format('d/m/Y - g:i:s a') ?></td>
        </tr>
    </tbody>
</table>

<hr />

<a href="<?php echo url_for('asset_edit_brand', $asset) ?>">Editar</a>
&nbsp;
<a href="<?php echo url_for('asset_brands') ?>">Volver al listado</a>
