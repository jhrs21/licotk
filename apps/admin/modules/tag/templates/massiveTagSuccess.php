<?php if ($sf_user->hasFlash('massive_tags')): ?>
    <div class="span-24" style="color:green;text-align:center">
        <?php echo $sf_user->getFlash('massive_tags') ?>
    </div>
<?php endif; ?>
<div class="span-24">
    <form action="<?php echo url_for('tag_do_massive_asset')?>" method="POST">
        <?php echo $form?>
        <input type="submit" value="Asignar Tags">
    </form>
</div>