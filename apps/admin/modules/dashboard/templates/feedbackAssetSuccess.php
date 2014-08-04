<?php foreach ($pager->getResults() as $feed) : ?>
    <div class="feed-row">
        <?php if ($feed->getValoration() == 2): ?>
            <div class="feed-thumb"><img src="/images/bueno-01.jpg"></div>
        <?php endif; ?>
        <?php if ($feed->getValoration() == 1): ?>
            <div class="feed-thumb"><img src="/images/regular-01.jpg"></div>
        <?php endif; ?>
        <?php if ($feed->getValoration() == 0): ?>
            <div class="feed-thumb"><img src="/images/malo-01.jpg"></div>
        <?php endif; ?>
        <div class="feed-content">
            <div class="feed-message"><?php echo $feed->getMessage(); ?></div>
            <div class="feed-date">
                Comentario hecho el <?php echo $feed->getDateTimeObject('created_at')->format('d/m/Y - g:i:s a'); ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php if ($pager->haveToPaginate()): ?>
    <div class="pagination-container">
        <a class="pagination-link" title="Primera Página" href="<?php echo url_for('') ?>?page=1<?php echo $valoration !== false ? '&valoration='.$valoration : '' ?>">
            << <!--<img src="/images/first.png" alt="First page" title="First page" />-->
        </a>
        <a class="pagination-link" title="Página Anterior" href="<?php echo url_for('') ?>?page=<?php echo $pager->getPreviousPage() ?><?php echo $valoration !== false ? '&valoration='.$valoration : '' ?>">
            < <!--<img src="/images/previous.png" alt="Previous page" title="Previous page" />-->
        </a>
        <?php foreach ($pager->getLinks() as $page): ?>
            <a class="pagination-link <?php echo $page == $pager->getPage() ? 'current' : '' ?>" title="Página <?php echo $page ?>" 
               href="<?php url_for('') ?>?page=<?php echo $page ?><?php echo $valoration !== false ? '&valoration='.$valoration : '' ?><?php echo $valoration !== false ? '&valoration='.$valoration : '' ?>">
                   <?php echo $page ?>
            </a>
        <?php endforeach; ?>
        <a class="pagination-link" title="Página Siguiente" href="<?php echo url_for('') ?>?page=<?php echo $pager->getNextPage()?><?php echo $valoration !== false ? '&valoration='.$valoration : '' ?>">
            > <!--<img src="/images/next.png" alt="Next page" title="Next page" />-->
        </a>
        <a class="pagination-link" title="Última Página" href="<?php echo url_for('') ?>?page=<?php echo $pager->getLastPage() ?><?php echo $valoration !== false ? '&valoration='.$valoration : '' ?>">
            >> <!--<img src="/images/last.png" alt="Last page" title="Last page" />-->
        </a>
    </div>
<?php endif; ?>