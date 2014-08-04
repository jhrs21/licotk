<?php foreach ($form['items'] as $item): ?>
    <?php echo $item['answer']->renderRow() ?>
<?php endforeach; ?>