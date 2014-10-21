<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('user_list' => true))) ?>
    <div id="prizes-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Listado de Usuarios</h2>
        </div>
        <div id="list_container">
            <?php include_partial('listUser', array('users' => $users)) ?>
        </div>
    </div>
    <!--    <div>
            <a href="<?php echo url_for('promo_redeem_coupon_validation', $promo) ?>">Canjear cupón</a>
        </div>-->
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var effect = 'blind';
        var options = {};
        $('.flash_notice').click(function(){
            $(this).hide( effect, options, 1000);
        });
        
        $('.filter input[type="submit"]').hide();
        
        $('#serial-filter').keyup(function(key)
        {
            if (this.value.length >= 2 || this.value == '')
            {
                $('#loader').show();
                $('#list_container').load(
                $(this).parents('form').attr('action'),
                { serial: this.value, id_number: $('#idnumber-filter').val(), begin_date : $('#dates_begin_date').val(), end_date : $('#dates_end_date').val() },
                function() { $('#loader').hide(); }
            );
            }
        });
        $('#idnumber-filter').keyup(function(key)
        {
            if (this.value.length >= 2 || this.value == '')
            {
                $('#loader').show();
                $('#list_container').load(
                $(this).parents('form').attr('action'),
                { id_number: this.value, serial: $('#serial-filter').val(), begin_date : $('#dates_begin_date').val(), end_date : $('#dates_end_date').val() },
                function() { $('#loader').hide(); }
            );
            }
        });
    });
    
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    var daysShort = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
    var daysMin = ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];
    $(document).ready(function(){
        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            monthNames: months,
            monthNamesShort: monthsShort,
            dayNames: days,
            dayNamesShort: daysShort,
            dayNamesMin: daysMin,
            dateFormat: 'dd/mm/yy',
            yearRange: 'c-5:c',
            onSelect: function(dateText, inst){
                $('#loader').show();
                $('#list_container').load(
                    $(this).parents('form').attr('action'),
                    { id_number: $('#idnumber-filter').val(), serial: $('#serial-filter').val(), begin_date : $('#dates_begin_date').val(), end_date : $('#dates_end_date').val() },
                    function() { $('#loader').hide(); }
                );
            }
        });
    });
</script>
