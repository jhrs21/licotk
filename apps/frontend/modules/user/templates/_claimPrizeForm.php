<?php use_helper('I18N') ?>
<form id="claim-prize-form" action="<?php echo url_for('pre_generate_coupon_post',$card) ?>" 
    method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?> target="_blank">
    <div class="form-canvas box_round white-background">
        <?php echo $form ?>
    </div>
   <div class="form_submit">
        <input class="lt-button lt-button-orange box_round opensanscondensedlight submit" 
               type="submit" value="<?php echo __("Solicitar Premio") ?>" />
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('#claim-prize-form').bValidator();
        $('#claim-prize-form .submit').click(function(event){
            event.preventDefault();
            if($('#claim-prize-form').data('bValidator').validate()){
                $('#claim-prize-form').submit();
            }
            else{
                return false;
            }
        });
    });
</script>