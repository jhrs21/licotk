<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div id="surveys-forms-container" class="main-canvas box_round gray-background">
        <div class="main-canvas-title lightblue">
            <?php echo __('¡Participa y Gana!', null, 'sf_guard') ?>
        </div>
        <div class="form-container">
            <form id="surveys-form" method="post" action="<?php echo url_for('survey_application', array(), true).'?h='.$participationRequest->getHash() ?>">
                <div class="form-canvas box_round white-background">
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
                </div>
                <div class="form_submit">
                    <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" 
                           type="submit" value="<?php echo __('Completar la encuesta') ?>" />
                </div>
            </form>
        </div>
        <div class="main-canvas-footer">
            <div class="lightgray-separator separator"></div>
            No deseo completar esta encuesta, <a href="<?php echo url_for('user_prizes') ?>">Ir a Mis Premios</a>
        </div>
        <div id="signup-left-top-corner" class="lt-corner lt-corner-small lt-corner-small-tl"></div>
        <div id="signup-right-bottom-corner" class="lt-corner lt-corner-big lt-corner-big-br"></div>
    </div>
</div>
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
            position: {x:'center', y:'top'}
        };
        
        $('#surveys-form').bValidator(options);

        //$('#surveys-form').submit(function(event){
        $('.submit').submit(function(event){
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