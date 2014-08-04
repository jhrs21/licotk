<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form id="survey-results-form-filter"
      action="<?php echo url_for('survey_filter_results', $survey) ?>" method="post" 
      <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="filter_row darkgray span-6 last">
        <?php echo $form['asset_id']->renderError(); ?>
        <div class="filter_row_label span-6 last">
            <?php echo $form['asset_id']->renderLabel(); ?>
        </div>
        <div class="filter_row_field span-6 last">
            <?php echo $form['asset_id']->render(); ?>
        </div>
    </div>
    <div class="filter_row darkgray span-6 last">
        <?php echo $form['age_range']->renderError(); ?>
        <div class="filter_row_label span-6 last">
            <?php echo $form['age_range']->renderLabel(); ?>
        </div>
        <div class="filter_row_field span-6 last">
            <?php echo $form['age_range']->render(); ?>
        </div>
    </div>
    <div class="filter_row darkgray span-6 last">
        <?php echo $form['gender']->renderError(); ?>
        <div class="filter_row_label span-6 last">
            <?php echo $form['gender']->renderLabel(); ?>
        </div>
        <div class="filter_row_field span-6 last">
            <?php echo $form['gender']->render(); ?>
        </div>
    </div>
    <div class="filter_row darkgray span-6 last">
        <?php echo $form['created_at']->renderError(); ?>
        <div class="filter_row_label span-6 last">
            <?php echo $form['created_at']->renderLabel(); ?>
        </div>
        <div class="filter_row_field span-6 last">
            <?php echo $form['created_at']->render(); ?>
            <?php echo $form->renderHiddenFields(); ?>
        </div>
    </div>
    <div class="form-submit-container span-6 last">
        <input class="form-submit" type="submit" value="Filtrar" />
    </div>
</form>