<?php use_helper('I18N') ?>

<div class="top-separator-colors box_shadow_bottom">
    <div class="purple-background"></div>
    <div class="blue-background"></div>
    <div class="orange-background"></div>
</div>
<form id="surveys-form" method="post" 
      action="<?php echo url_for('survey_feedback_register') . '?h=' . $participationRequest->getHash() ?>">
    <div class="form-canvas white-background">
        <?php $str = $participationRequest->getAsset()->getAssetType() == 'place' ? __('en') : __('con'); ?>
        <?php $title = '¿' . __('Qué tal fue tu experiencia') . ' ' . $str . ' ' . $participationRequest->getAsset()->getName() . '?'; ?>
        <div class="main-canvas-title darkgray">
            <?php echo $title ?>
        </div>
        <?php echo $feedbackForm['valoration']->renderRow(array('id' => 'feedback-valoration')) ?>
        <?php echo $feedbackForm['message']->renderRow() ?>
        <?php echo $feedbackForm->renderHiddenFields() ?>
        <?php if ($participationRequest->getSurveys()) : ?>
            <div class="lightgray-separator separator"></div>
            <?php $forms = $surveysForm->getEmbeddedForms(); ?>
            <?php $count = count($forms); $i = 0; ?>
                <?php foreach ($forms as $form): ?>
                <div class="main-canvas-title darkgray">
                    <?php $survey = $form->getObject()->getSurvey() ?>
                    <?php echo $survey->getName() ?>
                </div>
                <?php foreach ($surveysForm[$survey->getAlphaId()]['items'] as $item): ?>
                    <?php echo $item['answer']->renderRow() ?>
                <?php endforeach; ?>
                <?php if ($i != $count - 1) : ?>
                    <div class="lightgray-separator separator"></div>
                <?php endif; ?>
                    <?php $i++; ?>
                <?php endforeach; ?>
            <div class="form-canvas-footer">
                <?php echo $surveysForm->renderHiddenFields() ?>
            </div>
        <?php endif; ?>
        <div class="form-canvas-footer">
            <div class="text-align-left darkgray"><?php echo '*' . __('Opina libremente, tus datos personales no serán compartidos con nadie.') ?></div>
            <div class="text-align-left darkgray"><?php echo '**' . __('Evita realizar comentarios ofensivos al dar tu opinión.') ?></div>
        </div>
    </div>
    <div class="form_submit">
        <input class="lt-button lt-button-blue box_round opensanscondensedlight submit wm-submit-button" 
               type="submit" value="<?php echo __('Completar la encuesta') ?>" />
    </div>
</form>
<script type="text/javascript">
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Novimbre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo','Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    var daysShort = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
    var daysMin = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];
    
    $(document).ready(function(){
        $('.uses-datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            monthNames: months,
            monthNamesShort: monthsShort,
            dayNames: days,
            dayNamesShort: daysShort,
            dayNamesMin: daysMin,
            dateFormat: 'dd/mm/yy',
            yearRange: 'c-1:c+1'
        });
            
        $('.uses-datepicker').focus(function(){
            $(this).blur();
        });
        
        var options = {
            position: {x:'right', y:'top'}
        };
        
        $('#surveys-form').bValidator(options);

        //$('#surveys-form').submit(function(event){
        $('.submit').click(function(event){
            event.preventDefault();
            $('input[type="submit"]').attr('disabled','disabled');
            if($('#surveys-form').data('bValidator').validate()){
                $('#surveys-form').submit();
            }
            else {
                $('input[type="submit"]').removeAttr('disabled');
                return false;
            }
        });      
    })
</script>