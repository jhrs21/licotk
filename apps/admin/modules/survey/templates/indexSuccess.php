<div class="span-8">
    <h1>Listado de Encuestas</h1>
</div>
<div class="span-16 last align-right">
    <a href="<?php echo url_for('survey/new') ?>">Crear Encuesta</a>
</div>
<div class="span-24">
    <?php include_partial('survey/list', array('surveys' => $surveys)) ?>
</div>
