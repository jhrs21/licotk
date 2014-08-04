<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $promo_code->getId() ?></td>
    </tr>
    <tr>
      <th>Alpha:</th>
      <td><?php echo $promo_code->getAlphaId() ?></td>
    </tr>
    <tr>
      <th>Hash:</th>
      <td><?php echo $promo_code->getHash() ?></td>
    </tr>
    <tr>
      <th>Type:</th>
      <td><?php echo $promo_code->getType() ?></td>
    </tr>
    <tr>
      <th>Status:</th>
      <td><?php echo $promo_code->getStatus() ?></td>
    </tr>
    <tr>
      <th>Used at:</th>
      <td><?php echo $promo_code->getUsedAt() ?></td>
    </tr>
    <tr>
      <th>Promo:</th>
      <td><?php echo $promo_code->getPromoId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $promo_code->getUserId() ?></td>
    </tr>
    <tr>
      <th>Asset:</th>
      <td><?php echo $promo_code->getAssetId() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $promo_code->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $promo_code->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('promo_code/edit?affiliate='.$affiliate->getId().'&id='.$promo_code->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('promo_code/index') ?>">List</a>
