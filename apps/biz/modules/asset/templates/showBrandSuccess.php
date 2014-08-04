<table>
    <tbody>
        <?php
            $isAdmin = $sf_user->getGuardUser()->hasGroup('admin');
            if ($isAdmin):
        ?>
            <tr>
                <th>Affiliate</th>
                <td><a href="<?php echo url_for('affiliate_show', $asset->getAffiliate()) ?>"><?php echo $asset->getAffiliate() ?></a></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th>Name:</th>
            <td><?php echo $asset->getName() ?></td>
        </tr>
        <tr>
            <th>Created at:</th>
            <td><?php echo $affiliate->getDateTimeObject('created_at')->format('d/m/Y - g:i:s a') ?></td>
        </tr>
        <tr>
            <th>Updated at:</th>
            <td><?php echo $affiliate->getDateTimeObject('updated_at')->format('d/m/Y - g:i:s a') ?></td>
        </tr>
    </tbody>
</table>

<hr />

<a href="<?php echo url_for('asset_edit_brand', $asset) ?>">Editar</a>
&nbsp;
<a href="<?php echo url_for('asset_brands') ?>">Volver al listado</a>
