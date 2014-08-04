<?php use_helper('I18N') ?>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>¿Es Master?</th>
            <th>¿Acitva?</th>
            <th>Ultima actualización</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($surveys as $survey): ?>
            <tr>
                <td><a href="<?php echo url_for('survey_show', $survey) ?>"><?php echo $survey->getName() ?></a></td>
                <td><?php echo __($survey->getIsMaster() ? 'Si' : 'No') ?></td>
                <td><?php echo __($survey->getIsActive() ? 'Si' : 'No') ?></td>
                <td><?php echo $survey->getUpdatedAt() ?></td>
                <td>
                    <a href="<?php echo url_for('survey_edit', $survey) ?>">Editar</a>
                    &nbsp;&nbsp;
                    <a href="<?php echo url_for('survey_show', $survey) ?>">Ver</a>
                    &nbsp;&nbsp;
                    <a href="<?php echo url_for('survey_results', $survey) ?>">Estadísticas</a>
                    &nbsp;&nbsp;
                    <a href="<?php echo url_for('survey_show', $survey) ?>">Datos</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>