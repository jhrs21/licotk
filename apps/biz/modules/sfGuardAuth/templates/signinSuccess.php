<?php use_helper('I18N') ?>
<div class="main-container-inner">
    <div id="signin-form-container" class="main-canvas">
        <div id="signin-form-title" class="main-canvas-title">
            <h2><?php echo __('¡Ingresa a tu cuenta de negocio!', null, 'sf_guard') ?></h2>
        </div>
        <?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#signin-form').bValidator();

        $('.submit').click(function(event){
            event.preventDefault();

            if($('#signin-form').data('bValidator').validate()){
                $('#signin-form').submit();
            }
            else
            {
                return false;
            }
        });
    });
</script>