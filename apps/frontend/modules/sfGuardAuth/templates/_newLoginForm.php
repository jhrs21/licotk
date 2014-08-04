<?php use_helper('I18N') ?>
<form id="login-form" action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
    <div class="form-canvas box_round white-background">
        <?php echo $form ?>
        <div class="form-canvas-footer">
            <a class="signin-form-forgot" href="<?php echo url_for('@sf_guard_password') ?>"><?php echo __('¿Olvidaste tu contraseña?', null, 'sf_guard') ?></a>
        </div>
    </div>
    <div class="form_submit">
        <p>¿No tienes cuenta? <?php echo link_to("Regístrate aquí", "sfApply/apply"); ?></p>
        <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" 
               type="submit" value="<?php echo __('Ingresar', null, 'sf_guard') ?>" />
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('#login-form').bValidator();
        $('.submit').click(function(event){
            event.preventDefault();
            if($('#login-form').data('bValidator').validate()){
                $('#login-form').submit();
            }
            else{
                return false;
            }
        });
    });
</script>