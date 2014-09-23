<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('promo' => true))) ?>
    <div id="prizes-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Cambiar de nivel</h2>
            <table id="licoteca-levels">
                <?php foreach ($result as $res): ?>
                <tr>
                    <td><?php echo $res['level']?></td>
                    <td><?php echo $res['bottom']?></td>
                    <td><?php echo $res['top']?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div id="licoteca-form-container" style="margin: auto; width: 400px;">
                <form id="licoteca-level-form" action="<?php echo url_for('change_user_level_post') ?>" method="post">
                    <div class="white-frame">
                        <?php echo $form ?>
                    </div>
                    <div class="form_footer">
                        <input id="supf-submit" class="form_submit" type="submit" value="Cambiar Datos del Nivel"/>
                    </div>
                </form>
            </div>
            <div class="main-canvas-content-footer">
                <a href="<?php echo url_for('promo') ?>">Regresar a Mis Promociones</a>
            </div>
        </div>
    </div>
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
