<form id="surveys-form" method="post" action="<?php echo url_for('survey_promo_application', $promo) ?>">
    <div class="form-canvas box_round white-background">
        <?php foreach ($forms->getEmbeddedForms() as $survey): ?>
            <div class="main-canvas-title">
                <?php echo $survey->getObject()->getSurvey()->getName() ?>
            </div>
            <?php include_partial('survey/surveyForm', array('form' => $survey)) ?>
        <?php endforeach; ?>
        <?php echo $forms->renderHiddenFields() ?>
        <div class="form-canvas-footer"></div>
    </div>
    <div class="form_submit">
        <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" 
               type="submit" value="<?php echo __('Completar la encuesta') ?>" />
    </div>
</form>