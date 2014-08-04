<?php $count = $affiliates->count(); ?>
<?php $i = 0; ?>
<?php foreach ($affiliates as $affiliate) : ?>
    <?php $i++; ?>
    <div class="featured-affiliate box_round <?php echo $i < $count ? '' : 'featured-affiliate-last'?>">
        <a href="<?php echo url_for('donde_estamos') ?>" width="120px" height="120px">
            <img width="120px" height="120px" src="/uploads/<?php echo $affiliate->getLogo(); ?>" />
        </a>
    </div>
<?php endforeach; ?>