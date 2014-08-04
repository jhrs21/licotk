<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $user->getId() ?></td>
    </tr>
    <tr>
      <th>First name:</th>
      <td><?php echo $user->getFirstName() ?></td>
    </tr>
    <tr>
      <th>Last name:</th>
      <td><?php echo $user->getLastName() ?></td>
    </tr>
    <tr>
      <th>Email address:</th>
      <td><?php echo $user->getEmailAddress() ?></td>
    </tr>
    <tr>
      <th>Username:</th>
      <td><?php echo $user->getUsername() ?></td>
    </tr>
    <tr>
      <th>Algorithm:</th>
      <td><?php echo $user->getAlgorithm() ?></td>
    </tr>
    <tr>
      <th>Salt:</th>
      <td><?php echo $user->getSalt() ?></td>
    </tr>
    <tr>
      <th>Password:</th>
      <td><?php echo $user->getPassword() ?></td>
    </tr>
    <tr>
      <th>Is active:</th>
      <td><?php echo $user->getIsActive() ?></td>
    </tr>
    <tr>
      <th>Is super admin:</th>
      <td><?php echo $user->getIsSuperAdmin() ?></td>
    </tr>
    <tr>
      <th>Last login:</th>
      <td><?php echo $user->getLastLogin() ?></td>
    </tr>
    <tr>
      <th>Alpha:</th>
      <td><?php echo $user->getAlphaId() ?></td>
    </tr>
    <tr>
      <th>Hash:</th>
      <td><?php echo $user->getHash() ?></td>
    </tr>
    <tr>
      <th>Affiliate:</th>
      <td><?php echo $user->getAffiliateId() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $user->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $user->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('user/edit?id='.$user->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('user/index') ?>">List</a>
