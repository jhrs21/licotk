<?php use_helper('I18N') ?>
<form id="feedback-form" action="<?php echo url_for('user_feedback_create') ?>" 
    method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="form-canvas text-align-center">
        <div class="form_row">
            <?php echo $form['valoration']->renderError() ?>
            <?php echo $form['valoration'] ?>
        </div>
        <div class="form_row">
            <?php echo $form['message']->renderError() ?>
            <div class="form_row_label">
                <?php echo $form['message']->renderLabel() ?>
            </div>
            <div class="form_row_field text-align-left">
                <?php echo $form['message'] ?>
            </div>
        </div>
        <?php echo $form->renderHiddenFields() ?>
    </div>
    <div class="form_submit">
        <input id="feedback-form-submit" class="lt-button lt-button-blue box_round opensanscondensedlight submit" type="submit" value="<?php echo __("Enviar") ?>" />
    </div>
</form>
<!--<script type="text/javascript">
    $(document).ready(function(){
        $('#feedback-form').bValidator();
        $('.submit').click(function(event){
            event.preventDefault();
            $('input[type="submit"]').attr('disabled','disabled');
            if($('#feedback-form').data('bValidator').validate()){
                $('#feedback-form').submit();
            }
            else {
                $('input[type="submit"]').removeAttr('disabled');
                return false;
            }
        });      
    })
</script>-->