<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form id="user-filter" action="<?php echo url_for('email_send_message',$email) ?>" 
    method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <table id="email">
        <tfoot>
            <tr>
                <td>
                    <a href="">Cancelar</a>
                </td>
                <td class="align-right">
                    <input class="submit" type="submit" value="Enviar" />
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form?>
        </tbody>
    </table>
</form>


<script type="text/javascript">    
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo','Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    var daysShort = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
    var daysMin = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];
    
    function checkMyInputs(value, field, id){
        if(field == '1'){
            if(!value && $('#val_2').val())
                return false;
        }
        else if(field == '2'){
            if(!value && $('#val_1').val())
                return false;
        }
        return true;
    }

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
            yearRange: '-10:+5'
        })
            
        $('.uses-datepicker').focus(function(){
            $(this).blur();
        });


        $('#user-filter').bValidator();

        $('.submit').click(function(event){
            event.preventDefault();
            $('input[type="submit"]').attr('disabled','disabled');
            if($('#user-filter').data('bValidator').validate()){
                $('#user-filter').submit();
            }
            else {
                $('input[type="submit"]').removeAttr('disabled');
                return false;
            }
        });      
    })
</script>