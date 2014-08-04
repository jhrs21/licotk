<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('promo' => true))) ?>
    <?php if ($sf_user->hasFlash('success')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('success') ?></p>
        </div>
    <?php endif; ?>
    <div id="prizes-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Listado de Premios</h2>
        </div>
        <?php if ($pager->count()) : ?>
            <div class="options-container">
                <div class="form-filter-container filter">
                    <form action="<?php echo url_for('promo_list_coupon', $promo) ?>" method="post">
                        <label>Buscar por serial:</label>
                        <input type="text" name="query" value="<?php echo $sf_request->getParameter('serial') ?>" id="serial-filter" />
                        <label>Buscar por cédula:</label>
                        <input type="text" name="query" value="<?php echo $sf_request->getParameter('id_number') ?>" id="idnumber-filter" />
                        <input type="submit" value="search" />
                        <img id="loader" src="/images/loader.gif" style="vertical-align: middle; display: none" />
                        <br><label>Canje: </label><?php echo $form ?>
                        <input type="submit" value="filtrar" />
                        <a href="<?php echo url_for('promo_list_coupon', $promo)?>">Reiniciar</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <div id="list_container">
            <?php include_partial('listCoupon', array('pager' => $pager, 'promo' => $promo)) ?>
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
