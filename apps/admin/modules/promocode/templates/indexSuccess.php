<h1>Promocodes List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Type</th>
      <th>Status</th>
      <th>Digital?</th>
      <th>Serial</th>
      <th>Promo</th>
      <th>Asset</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($promocodes as $promocode): ?>
    <tr>
      <td><a href="<?php echo url_for('promocode/show?id='.$promocode->getId()) ?>"><?php echo $promocode->getId() ?></a></td>
      <td><?php echo $promocode->getType() ?></td>
      <td><?php echo $promocode->getStatus() ?></td>
      <td><?php if($promocode->getDigital()) echo "Si"; else echo "No"?></td>
      <td><?php echo $promocode->getSerial() ?></td>
      <td><?php echo $promocode->getPromoId() ?></td>
      <td><?php echo $promocode->getAsset()->getName() ?></td>
      <td><?php echo $promocode->getCreatedAt() ?></td>
      <td><?php echo $promocode->getUpdatedAt() ?></td>
      
      <td><a href="<?php echo url_for('promo_code_admin/show?id='.$promocode->getId()).'/ListPrintTickets' ?>">Tickera</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('promocode/new') ?>">New</a>
