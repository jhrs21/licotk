<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $promocode->getId() ?></td>
    </tr>
    <tr>
      <th>Alpha:</th>
      <td><?php echo $promocode->getAlphaId() ?></td>
    </tr>
    <tr>
      <th>Hash:</th>
      <td><?php echo $promocode->getHash() ?></td>
    </tr>
    <tr>
      <th>Type:</th>
      <td><?php echo $promocode->getType() ?></td>
    </tr>
    <tr>
      <th>Status:</th>
      <td><?php echo $promocode->getStatus() ?></td>
    </tr>
    <tr>
      <th>Used at:</th>
      <td><?php echo $promocode->getUsedAt() ?></td>
    </tr>
    <tr>
      <th>Serial:</th>
      <td><?php echo $promocode->getSerial() ?></td>
    </tr>
    <tr>
      <th>Promo:</th>
      <td><?php echo $promocode->getPromoId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $promocode->getUserId() ?></td>
    </tr>
    <tr>
      <th>Asset:</th>
      <td><?php echo $promocode->getAssetId() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $promocode->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $promocode->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('promocode/edit?id='.$promocode->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('promocode/index') ?>">List</a>
