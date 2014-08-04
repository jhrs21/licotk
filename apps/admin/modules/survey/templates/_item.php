<?php use_helper('I18N') ?>

<div class="item-details span-24">
    <div class="span-24"><b>Item: "<?php echo $item->getLabel() ?>"</b></div>
    <div class="prepend-1 span-23 last"><b>Descripción:</b> <?php echo ($item->getHelp() ? $item->getHelp() : 'No se ha agregado una descripción') ?></div>
    <div class="prepend-1 span-23 last">
        <b>Tipo:</b>
        <?php if (strcasecmp($item->getItemType(), 'multiple_selection') == 0) : ?>
            <?php echo __('Selección multiple') ?>
        <?php elseif (strcasecmp($item->getItemType(), 'simple_selection') == 0) : ?>
            <?php echo __('Selección simple') ?>
        <?php elseif (strcasecmp($item->getItemType(), 'text') == 0) : ?>
            <?php echo __('Campo de texto') ?>
        <?php elseif (strcasecmp($item->getItemType(), 'date') == 0) : ?>
            <?php echo __('Campo de fecha') ?>
        <?php else : ?>
            <?php echo $item->getItemType() ?>
        <?php endif; ?>
    </div>
    <?php if ($item->usesOptions()) : ?>
        <div class="prepend-1 span-23 last">
            <b>Opciones:</b>
            <ul>
                <?php foreach ($item->getOptions() as $option) : ?>
                    <li><?php echo $option->getLabel() ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>