<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $mcard->getId() ?></td>
    </tr>
    <tr>
      <th>Alpha:</th>
      <td><?php echo $mcard->getAlphaId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $mcard->getUserId() ?></td>
    </tr>
    <tr>
      <th>Status:</th>
      <td><?php echo $mcard->getStatus() ?></td>
    </tr>
    <tr>
      <th>Validate:</th>
      <td><?php echo $mcard->getValidate() ?></td>
    </tr>
    <tr>
      <th>Asset:</th>
      <td><?php echo $mcard->getAssetId() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $mcard->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $mcard->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('membership_card/edit?id='.$mcard->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('membership_card/index') ?>">List</a>
