<?php use_helper('I18N') ?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="main-canvas-title lightblue">
                <?php echo __("¡Completa tu cuenta!") ?>
            </div>
            <div class="form-container">
                <form id="sign-up-form" method="post" action="<?php echo url_for('user_complete_register',array('validate' => $validate)) ?>"
                      name="sf_apply_apply_form">
                    <div class="form-canvas box_round white-background">
                        <?php echo $form ?>
                    </div>
                    <div class="form_submit">
                        <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" type="submit" value="<?php echo __("Completar e Ingresar") ?>" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
        });
        
        $("#sfApplyApply_email").blur();


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