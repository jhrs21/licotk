<?php use_helper('I18N') ?>
<form id="sign-up-form" method="post" action="<?php echo url_for('sfApply/apply') ?>">
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put"/>
    <?php endif; ?>
    <div class="form-canvas box_round white-background">
        <?php echo $form ?>
        <?php if (isset($showTermCheck) && $showTermCheck) :?>
            <div class="form_row_small ">
                <div class="form_row_label">
                    <label for="sfApplyApply_privacyPolicy">
                        Acepto los <a href="<?php echo url_for('privacy_policy') ?>" target="_blank">Términos de Privacidad</a>
                    </label>
                </div>
                <div class="form_row_field">
                    <input type="checkbox" name="sfApplyApply[privacyPolicy]" id="sfApplyApply_privacyPolicy" data-bvalidator-msg="Debes aceptar los terminos de privacidad para crear tu cuenta" data-bvalidator="required">
                </div>
            </div>
        <?php endif; ?>
        <div class="form-canvas-footer"></div>
    </div>
    <div class="form_submit">
        <p>¿Ya estás registrado? Ingresa <a href="<?php echo url_for('sf_guard_signin') ?>">AQUÍ</a></p>
        <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" 
               type="submit" value="<?php echo __('Regístrate') ?>" />
    </div>
</form>
<script type="text/javascript">
    function validateregex(str) {
        var regx = /^[a-zA-ZáéíóúÁÉÍÓÚñÑü'.\s]*$/;
        return str.match(regx); 
    }
    
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo','Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
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
            yearRange: '-100:-10'
        })
            
        $('.uses-datepicker').focus(function(){
            $(this).blur();
        });;


        $('#sign-up-form').bValidator();

        $('.submit').click(function(event){
            event.preventDefault();
            $('input[type="submit"]').attr('disabled','disabled');
            if($('#sign-up-form').data('bValidator').validate()){
                $('#sign-up-form').submit();
            }
            else {
                $('input[type="submit"]').removeAttr('disabled');
                return false;
            }
        });      
    })
</script>