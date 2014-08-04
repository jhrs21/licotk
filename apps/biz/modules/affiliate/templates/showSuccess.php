<?php use_helper('I18N') ?>
<h1><?php echo $affiliate->getName() ?></h1>
<table>
    <tbody>
        <tr>
            <th><?php echo __('Logo', null, 'elperro') ?>:</th>
            <td><img title="logo" src="/uploads/<?php echo $affiliate->getLogo() ?>"/></td>
        </tr>
        <tr>
            <th><?php echo __('Miniatura', null, 'elperro') ?>:</th>
            <td><img title="logo" src="/uploads/<?php echo $affiliate->getThumb() ?>"/></td>
        </tr>
        <tr>
            <th><?php echo __('Categoria', null, 'elperro') ?>:</th>
            <td><?php echo $affiliate->getCategory() ?></td>
        </tr>
        <tr>
            <th><?php echo __('Fecha de Creación', null, 'elperro') ?>:</th>
            <td><?php echo $affiliate->getDateTimeObject('created_at')->format('d/m/Y - g:i:s a') ?></td>
        </tr>
        <tr>
            <th><?php echo __('Ultima Actualización', null, 'elperro') ?>:</th>
            <td><?php echo $affiliate->getDateTimeObject('updated_at')->format('d/m/Y - g:i:s a') ?></td>
        </tr>
    </tbody>
</table>

<hr />

<a href="<?php echo url_for('affiliate_edit', $affiliate) ?>">Edit</a>
<?php if($sf_user->hasGroup('admin')): ?>
&nbsp;
<a href="<?php echo url_for('affiliate') ?>">List</a>
<?php endif; ?>
