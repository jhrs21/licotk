<h1>Assets List</h1>

<table>
    <thead>
        <tr>
            <?php 
                $isAdmin = $sf_user->getGuardUser()->hasGroup('admin');
                if($isAdmin):
            ?>
                <th>Affiliate</th>
            <?php endif; ?>
            <th>Name</th>
            <th>Active</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($assets as $asset): ?>
            <tr>
                <?php if($isAdmin): ?>
                    <td><a href="<?php echo url_for('affiliate_show', $asset->getAffiliate()) ?>"><?php echo $asset->getAffiliate() ?></a></td>
                <?php endif; ?>
                <td><a href="<?php echo url_for('asset_show_brand', $asset) ?>"><?php echo $asset ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="<?php echo url_for('asset_new_brand')?>">Registrar nueva Marca</a>