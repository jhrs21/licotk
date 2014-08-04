<h1>Affiliates List</h1>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Active</th>
            <th>Category</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($affiliates as $affiliate): ?>
            <tr>
                <td><a href="<?php echo url_for('affiliate_show', $affiliate) ?>"><?php echo $affiliate->getName() ?></a></td>
                <td><?php echo $affiliate->getActive() ? 'Si' : 'No' ?></td>
                <td><?php echo $affiliate->getCategory() ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="<?php echo url_for('affiliate/new') ?>">New</a>
