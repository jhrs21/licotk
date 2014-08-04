<?php use_helper('I18N') ?>

<form id="register-tag-form" action="<?php echo url_for('user_create_ticket') ?>" 
    method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="form-canvas box_round white-background">
        <?php echo $form ?>
    </div>
    <div class="form_submit">
        <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" 
               type="submit" value="<?php echo __('Registrar visita', null, 'sf_guard') ?>" />
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('#register-tag-form').bValidator();
        $('#register-tag-form .submit').click(function(event){
            event.preventDefault();
            if($('#register-tag-form').data('bValidator').validate()){
                $('#register-tag-form').submit();
            }
            else{
                return false;
            }
        });
    });
</script>