<?php use_helper('I18N') ?>
<form id="contact-form" action="negocio" method="POST" name="sf_contact_form">
    <div class="form-canvas box_round white-background">
        <?php echo $form['business']->renderRow() ?>
        <?php echo $form['address']->renderRow() ?>
        <?php echo $form['rif']->renderRow() ?>
        <?php echo $form['type']->renderRow() ?>
        <?php echo $form['name']->renderRow() ?>
        <?php echo $form['phone']->renderRow() ?>
        <?php echo $form['email']->renderRow() ?>
        <div class="form_row">
            <div class="form_row_field" style="width:100%;text-align:center">
                <?php echo $form['captcha']->renderError() ?>
                <?php echo $form['captcha']->render() ?>
            </div>
            <?php echo $form->renderHiddenFields() ?>
        </div>
    </div>
    <div class="form_submit">
            <input id="contact-input" class="lt-button lt-button-blue box_round opensanscondensedlight submit" type="submit" value="Enviar" />
    </div>
</form>
<script type="text/javascript">
    function validateregex(str) {
        var regx = /^[a-zA-ZáéíóúÁÉÍÓÚñÑü'.\s]*$/;
        return str.match(regx); 
    }
    
    $(document).ready(function(){
        $('#contact-form').bValidator();
        $('.submit').click(function(event){
            event.preventDefault();
            $('input[type="submit"]').attr('disabled','disabled');
            if($('#contact-form').data('bValidator').validate()){
                $('#contact-form').submit();
            }
            else {
                $('input[type="submit"]').removeAttr('disabled');
                return false;
            }
        });      
    })
</script>
