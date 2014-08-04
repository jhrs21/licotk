<table>
    <tfoot>
        <tr>
            <td><?php echo $item['delete']->render(array('dom_id' => 'item_' . $key)) ?></td>
            <td>
                <?php echo $item['add_option']->render(array('dom_id' => 'item_' . $key . '_options')) ?>
                <img class="option_loader" style="display:none" src="/images/loader16x16.gif">
            </td>
        </tr>
    </tfoot>
    <tbody>
        <?php echo $item['label']->renderRow() ?>
        <?php echo $item['help']->renderRow() ?>
        <?php echo $item['is_required']->renderRow() ?>
        <?php echo $item['is_active']->renderRow() ?>
        <?php echo $item['position']->renderRow() ?>
        <?php echo $item['item_type']->renderRow() ?>
        <?php if (isset($item['options'])) : ?>
            <tr id="<?php echo 'item_' . $key . '_options' ?>">
                <th>Opciones</th>
                <td>
                    <table>
                        <tbody>
                            <?php foreach ($item['options'] as $k => $option) : ?>
                                <tr id="<?php echo 'item_' . $key . '_option_' . $k ?>">
                                    <th><?php echo $k+1 ?></th>
                                    <td><?php include_partial('itemOptionForm', array('item' => $key, 'key' => $k, 'option' => $option)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        <?php endif; ?>
        <?php echo $item['id']->render() ?>
    </tbody>
</table>