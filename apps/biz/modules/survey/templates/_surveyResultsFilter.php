<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form id="survey-results-form-filter"
      action="<?php echo url_for('survey_results_filter',$survey) ?>" method="post" 
    <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php echo $form['asset_id']->renderRow(); ?>
    <?php echo $form['age_range']->renderRow(); ?>
    <?php echo $form['gender']->renderRow(); ?>
    <?php echo $form['created_at']->renderRow(); ?>
    <?php echo $form->renderHiddenFields(); ?>
    <div class="form-submit-container">
        <input class="form-submit" type="submit" value="Filtrar" />
    </div>
</form>