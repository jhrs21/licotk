<?php if ($withIndex) : ?>
    <a href="<?php echo url_for('survey'); ?>">Ir al listado</a>
    &nbsp;&nbsp;
<?php endif; ?>
<?php if (isset($survey)) : ?>
    <a href="<?php echo url_for('survey_edit',$survey) ?>">Editar</a>
    &nbsp;&nbsp;
    <a href="<?php echo url_for('survey_results',$survey) ?>">Estadísticas</a>
    &nbsp;&nbsp;
<?php endif; ?>
<a href="<?php echo url_for('survey_new') ?>">Datos</a>