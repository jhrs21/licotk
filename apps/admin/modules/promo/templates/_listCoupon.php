<tr>
    <th>Serial</th>
    <th>Status</th>
    <th>Fecha de creación</th>
</tr>
<?php foreach ($coupons as $user): ?>
    <tr>
        <td><?php echo $user->getSerial() ?></td>
        <td><?php echo $user->getStatus() ?></td>
        <td><?php echo $user->getCreatedAt() ?></td>
    </tr>
<?php endforeach; ?>
