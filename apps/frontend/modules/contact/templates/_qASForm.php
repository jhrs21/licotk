<?php use_helper('I18N') ?>
<form id="qas-form" action="<?php echo url_for('procesar_pregunta_sugerencia') ?>" method="post" name="epQASForm">
    <div class="form-container">
        <?php if ($form->hasGlobalErrors()) :?>
            <?php echo $form->renderGlobalErrors() ?>
        <?php endif; ?>
        <?php echo $form['name']->renderRow() ?>
        <?php echo $form['email']->renderRow() ?>
        <?php echo $form['message']->renderRow() ?>
        <div class="form_row">
            <div class="form_row_field" style="width:100%;text-align:center">
                <?php echo $form['captcha']->render() ?>
            </div>
            <?php echo $form->renderHiddenFields() ?>
        </div>
    </div>
    <div class="form-submit-container">
        <input class="lt-button lt-button-darkgray box_round opensanscondensedlight submit" value="Enviar" type="submit"/>
    </div>
</form>
<script type="text/javascript">
    function validateregex(str) {
        var regx = /^[a-zA-ZáéíóúÁÉÍÓÚñÑü'.\s]*$/;
        return str.match(regx); 
    }
    
    $(document).ready(function(){
        $('#qas-form').bValidator();
        $('.submit').click(function(event){
            event.preventDefault();
            $('input[type="submit"]').attr('disabled','disabled');
            if($('#qas-form').data('bValidator').validate()){
                $('#qas-form').submit();
            }
            else {
                $('input[type="submit"]').removeAttr('disabled');
                return false;
            }
        });      
    })
</script>
