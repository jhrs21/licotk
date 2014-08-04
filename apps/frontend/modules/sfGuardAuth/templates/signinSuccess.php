<?php use_helper('I18N') ?>
<div id="main-container" class="span-24">
    <?php include_partial('html_static/colorBanner')?>
    <div class="main-container-inner">
        <div id="signin-form-container" class="main-canvas">
            <div id="signin-form-title" class="main-canvas-title">
                <h2><?php echo __('¡Ingresa a tu cuenta!', null, 'sf_guard') ?></h2>
		<!-- 
                	<h3>
                	    ¿Tienes una cuenta en <a target="_blank" href="http://www.tudescuenton.com">TuDescuenton.com</a>?
                	</h3>
                	<h3>
                	    ¡Úsala para ingresar!
                	</h3>
		-->
            </div>
            <div class="form-container-centered">
                <?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>
                <div class="form-container-footer">
                    <p>¿No tienes cuenta? <?php echo link_to("Registrate aquí", "sfApply/apply"); ?></p>
                </div>
            </div>
        </div>
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
