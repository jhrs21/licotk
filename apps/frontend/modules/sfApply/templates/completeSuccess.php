<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div id="complete-form-title" class="main-canvas-title lightblue">
                <?php echo __('¡Completa tus datos!') ?><br>
                <?php echo __('Por favor completa o actualiza los campos a continuación') ?>
            </div>
            <div id="complete-form-container" class="form-container">
                <form id="complete-form" method="post" action="<?php echo url_for('sfApply/complete') ?>"
                      name="sf_apply_update_form">
                    <div class="form-canvas box_round white-background">
                        <?php echo $form ?>
                    </div>
                    <div class="form_submit">
                        <input id="complete-input" class="lt-button lt-button-blue box_round opensanscondensedlight submit" type="submit" value="<?php echo __("Completar Datos") ?>" />
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
        });;


        $('#complete-form').bValidator();

        $('.submit').click(function(event){
            event.preventDefault();

            if($('#complete-form').data('bValidator').validate()){
                $('#complete-form').submit();
            }
            else {
                return false;
            }
        });      
    })
</script>