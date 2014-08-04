<div class="waw-item white-background box_round box_shadow">
    <div class="waw-image box_round box_shadow_bottom">
        <a href="<?php echo url_for('donde_estamos_afiliado',$affiliate) ?>">
            <img width="120px" height="120px" src="/uploads/<?php echo $affiliate->getLogo(); ?>">
        </a>
    </div>
    <div class="waw-name darkgray">
        <a href="<?php echo url_for('donde_estamos_afiliado',$affiliate) ?>" class="darkgray">
            <?php if (strlen($affiliate->getName()) <= 17) : ?>
                <?php echo $affiliate->getName(); ?>
            <?php else : ?>
                <?php echo substr($affiliate->getName(),0,14) . "...";?>
            <?php endif; ?>
        </a>
    </div>
</div>