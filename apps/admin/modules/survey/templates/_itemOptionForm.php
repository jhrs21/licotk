<table>
    <tfoot id="<?php echo 'option_foot_' . $key ?>" >
        <tr>
            <td>
                <?php echo $option['delete']->render(array('dom_id' => 'item_' . $item . '_option_' . $key)) ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <?php echo $option['label']->renderRow() ?>
        <?php echo $option['image']->renderRow() ?>
        <?php echo $option['image_only']->renderRow() ?>
        <?php echo $option['position']->renderRow() ?>
        <?php echo $option->renderHiddenFields() ?>
    </tbody>
</table>