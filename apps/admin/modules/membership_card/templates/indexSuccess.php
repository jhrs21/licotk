<h1>Mcards List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Alpha</th>
      <th>User</th>
      <th>Status</th>
      <th>Validate</th>
      <th>Asset</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pager->getResults() as $mcard): ?>
    <tr>
      <td><a href="<?php echo url_for('membership_card/show?id='.$mcard->getId()) ?>"><?php echo $mcard->getId() ?></a></td>
      <td><?php echo $mcard->getAlphaId() ?></td>
      <td><?php echo $mcard->getUserId() ?></td>
      <td><?php echo $mcard->getStatus() ?></td>
      <td><?php echo $mcard->getValidate() ?></td>
      <td><?php echo $mcard->getAssetId() ?></td>
      <td><?php echo $mcard->getCreatedAt() ?></td>
      <td><?php echo $mcard->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php if ($pager->haveToPaginate()): ?>
    <div class="pagination-container">
        <a class="pagination-link" title="Primera Página" href="<?php echo url_for('mcard') ?>?page=1">
            << <!--<img src="/images/first.png" alt="First page" title="First page" />-->
        </a>
        <a class="pagination-link" title="Página Anterior" href="<?php echo url_for('mcard') ?>?page=<?php echo $pager->getPreviousPage() ?>">
            < <!--<img src="/images/previous.png" alt="Previous page" title="Previous page" />-->
        </a>
        <?php foreach ($pager->getLinks() as $page): ?>
            <a class="pagination-link <?php echo $page == $pager->getPage() ? 'current' : '' ?>" title="Página <?php echo $page ?>" 
               href="<?php url_for('mcard') ?>?page=<?php echo $page ?>">
                   <?php echo $page ?>
            </a>
        <?php endforeach; ?>
        <a class="pagination-link" title="Página Siguiente" href="<?php echo url_for('mcard') ?>?page=<?php echo $pager->getNextPage() ?>">
            > <!--<img src="/images/next.png" alt="Next page" title="Next page" />-->
        </a>
        <a class="pagination-link" title="Última Página" href="<?php echo url_for('mcard') ?>?page=<?php echo $pager->getLastPage() ?>">
            >> <!--<img src="/images/last.png" alt="Last page" title="Last page" />-->
        </a>
    </div>
<?php endif; ?>

  <a href="<?php echo url_for('membership_card/new') ?>">New</a>
