
var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Novimbre', 'Diciembre'];
var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
var days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
var daysShort = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
var daysMin = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'];

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
            yearRange: 'c-100:c'
        });


    $('#sign-up-form').bValidator();

    $('.submit').click(function(event){
        event.preventDefault();

        if($('#sign-up-form').data('bValidator').validate()){
            $('#sign-up-form').submit();
        }
        else
        {
            return false;
        }
    });      
})