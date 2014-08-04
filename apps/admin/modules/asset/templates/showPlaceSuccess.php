<?php $location = $asset->getLocation()->getFirst(); ?>
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
            <th>Ubicación:</th>
            <td>
                <table>
                    <tbody>
                        <tr>
                            <th>País</th>
                            <td><?php echo $location->getCountry() ?></td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td><?php echo $location->getState() ?></td>
                        </tr>
                        <tr>
                            <th>Municipio</th>
                            <td><?php echo $location->getMunicipality() ?></td>
                        </tr>
                        <tr>
                            <th>Ciudad</th>
                            <td><?php echo $location->getCity() ?></td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td><?php echo $location->getAddress() ?></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <th>Fecha de Creación:</th>
            <td><?php echo $asset->getDateTimeObject('created_at')->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <th>Ultima Actualización:</th>
            <td><?php echo $asset->getDateTimeObject('updated_at')->format('d/m/Y') ?></td>
        </tr>
    </tbody>
</table>
<hr />
<a href="<?php echo url_for('asset_edit_place', $asset) ?>">Editar</a>
&nbsp;
<a href="<?php echo url_for('asset_places') ?>">Volver al listado</a>