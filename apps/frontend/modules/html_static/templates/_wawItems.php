<?php foreach ($pager->getResults() as $affiliate): ?> 
    <?php include_partial('wawItem', array('affiliate' => $affiliate))?>
<?php endforeach; ?>
<?php if ($pager->haveToPaginate()): ?>
<div class="waw-items-footer">
    <div class="lightgray-separator separator"></div>
    <div class="waw-pagination">
        <a href="<?php echo url_for('html_static/whereAreWe') ?>?page=1">
            <<
        </a>
        <a href="<?php echo url_for('html_static/whereAreWe') ?>?page=<?php echo $pager->getPreviousPage() ?>">
            <
        </a>
        <?php foreach ($pager->getLinks() as $page): ?>
            <?php if ($page == $pager->getPage()): ?>
                <?php echo $page ?>
            <?php else: ?>
                <a href="<?php echo url_for('html_static/whereAreWe') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
        <a href="<?php echo url_for('html_static/whereAreWe') ?>?page=<?php echo $pager->getNextPage() ?>">
            >
        </a>
        <a href="<?php echo url_for('html_static/whereAreWe') ?>?page=<?php echo $pager->getLastPage() ?>">
            >>
        </a>
    </div>
</div>
<?php endif; ?>