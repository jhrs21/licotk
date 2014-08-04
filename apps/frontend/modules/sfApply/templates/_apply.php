<?php use_helper('I18N') ?>
<form id="sign-up-form" method="post" action="<?php echo url_for('sfApply/apply') ?>"
      name="sf_apply_apply_form">
          <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put"/>
    <?php endif; ?>
    <table class="sign-up-form">
        <tfoot>
		<!--
			<tr>
                		<td colspan="2">
                    			<p id="td-message">
                        			Si tienes una cuenta en 
                        			<a target="_blank" href="http://www.tudescuenton.com">TuDescuenton.com</a> 
                        			úsala para ingresar <a href="<?php echo url_for('sf_guard_signin') ?>">Aquí</a>.
                    			</p>
                		</td>
            		</tr>
		-->
            <tr class="sf_apply_submit_row">
                <td colspan="2" class="sign-up-form-submit-container">
                    <input class="submit" type="submit" value="<?php echo __("Registrate") ?>" />
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form ?>
        </tbody>
    </table>
</form>
<script type="text/javascript">
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Novimbre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo','Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
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
        });
            
        $('.uses-datepicker').focus(function(){
            $(this).blur();
        });


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
