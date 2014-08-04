<tr>
    <th>Serial</th>
    <th>Status</th>
    <th>Fecha de creación</th>
</tr>
<?php foreach ($coupons as $coupon): ?>
    <tr>
        <td><?php echo $coupon->getSerial() ?></td>
        <td><?php echo $coupon->getStatus() ?></td>
        <td><?php echo $coupon->getCreatedAt() ?></td>
    </tr>
<?php endforeach; ?>
