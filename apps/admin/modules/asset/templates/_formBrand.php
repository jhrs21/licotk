<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo $form->getObject()->isNew() ? url_for('asset_create_brand') : url_for('asset_update_brand',$form->getObject())?>" 
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    <table>
        <tfoot>
            <tr>
                <td colspan="2">
                    <a href="<?php echo url_for('asset_brands') ?>">Back to list</a>
                    <input type="submit" value="Save" />
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form ?>
        </tbody>
    </table>
</form>
