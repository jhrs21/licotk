<?php use_helper('I18N') ?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div id="main-container" class="span-24">
    <?php include_partial('html_static/colorBanner',array('usesHighlight' => true, 'highlightThird' => true))?>
    <div class="sign-up-form-container">
        <div class="sign-up-form-container-inner">
            <div class="sign-up-form-container-inner-title">
                <h2><?php echo __("¡Crea tu cuenta!") ?></h2>
            </div>
            <div class="sign-up-form-container-inner-form">
                <form id="sign-up-form" method="post" action="<?php echo url_for('sfApply/apply') ?>"
                      name="sf_apply_apply_form">
                    <div class="frame">
                        <?php echo $form ?>
                    </div>
                    <div class="sign-up-form-submit-container">
                        <input class="submit" type="submit" value="<?php echo __("Registrate") ?>" />
                    </div>
                </form>
            </div>
            <div class="signin-link-container">
                <p>¿Ya estás registrado? <?php echo link_to("Entra aquí", "sf_guard_signin"); ?></p>                    
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
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
                yearRange: '-90:-10'
            });


        $('#sign-up-form').bValidator();

        $('.submit').click(function(event){
            event.preventDefault();

            if ($('#sign-up-form').data('bValidator').validate()) {
                $('#sign-up-form').submit();
            }
            else {
                return false;
            }
        });      
    })
</script>