<h2>Detalles de la Promoción</h2>
<table>
    <tbody>
        <?php if ($sf_user->getGuardUser()->hasGroup('admin')): ?>
            <tr>
                <th>Affiliate:</th>
                <td><a href="<?php echo url_for('affiliate_show', $promo->getAffiliate()) ?>"><?php echo $promo->getAffiliate() ?></a></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th>Name:</th>
            <td><?php echo $promo->getName() ?></td>
        </tr>
        <tr>
            <th>Description:</th>
            <td><?php echo $promo->getDescription() ?></td>
        </tr>
        <tr>
            <th>Thumb:</th>
            <td><img src="/uploads/<?php echo $promo->getThumb() ?>" /></td>
        </tr>
        <tr>
            <th>Photo:</th>
            <td><img src="/uploads/<?php echo $promo->getPhoto() ?>" /></td>
        </tr>
        <tr>
            <th>Starts at:</th>
            <td><?php echo $promo->getDateTimeObject('starts_at')->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <th>Ends at:</th>
            <td><?php echo $promo->getDateTimeObject('ends_at')->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <th>Begins at:</th>
            <td><?php echo $promo->getDateTimeObject('begins_at')->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <th>Expires at:</th>
            <td><?php echo $promo->getDateTimeObject('expires_at')->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <th>Prizes:</th>
            <td>
                <table>
                    <tbody>
                        <?php foreach ($promo->getPrizes() as $key => $prize): ?>
                            <tr>
                                <th><?php echo $key + 1 ?></th>
                                <td>
                                    <table>
                                        <tbody>
                                            <tr><td>Thumb</td><td><img src="/uploads/<?php echo $prize->getThumb(); ?>"/></td></tr>
                                            <tr><td>Prize</td><td><?php echo $prize->getPrize(); ?></td></tr>
                                            <tr><td>Threshold:</td><td><?php echo $prize->getThreshold(); ?></td></tr>
                                            <tr><td>Stock</td><td><?php echo $prize->getStock(); ?></td></tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <th>Terms:</th>
            <td>
                <table>
                    <tbody>
                        <?php foreach ($promo->getTerms() as $key => $term): ?>
                            <tr>
                                <th><?php echo 'Termino ' . ($key + 1) . ':' ?></th>
                                <td>
                                    <?php echo $term->getTerm(); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <th>Created at:</th>
            <td><?php echo $promo->getDateTimeObject('created_at')->format('d/m/Y - g:i:s a') ?></td>
        </tr>
        <tr>
            <th>Updated at:</th>
            <td><?php echo $promo->getDateTimeObject('updated_at')->format('d/m/Y - g:i:s a') ?></td>
        </tr>
    </tbody>
</table>

<hr />

<a href="<?php echo url_for('promo/edit?id=' . $promo->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('promo/index') ?>">List</a>