<?php use_helper('I18N') ?>
<div class="survey-details">
    <div class="span-14 align-left">
        <h1><?php echo $survey->getName() ?></h1>
    </div>
    <div class="span-10 last align-right">
        <?php include_partial('survey/surveyActions',array('withIndex' => true))?>
    </div>
    <div class="span-24">
        <b>Descripción:</b>
        <?php echo __($survey->getDescription() ? $survey->getDescription() : 'No se ha agregado una descripción' ); ?>
    </div>
    <div class="span-24 align-left">
        <b><?php echo __('¿Es Master?') ?>:</b>
        <?php echo __($survey->getIsMaster() ? 'Si' : 'No') ?>
    </div>
    <div class="span-24 align-left">
        <b><?php echo __('¿Activa?') ?>:</b>
        <?php echo __($survey->getIsActive() ? 'Si' : 'No') ?>
    </div>
    <div class="span-24">
        <h1>Items</h1>
        <?php foreach ($survey->getItems() as $key => $item) : ?>
            <?php include_partial('survey/item', array('item' => $item)); ?>
        <?php endforeach; ?>
    </div>
    <div class="span-24 align-left">
        <?php include_partial('survey/surveyActions',array('survey' => $survey,'withIndex' => true))?>
    </div>
</div>
