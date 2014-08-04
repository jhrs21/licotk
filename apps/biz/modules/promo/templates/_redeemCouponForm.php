<div id="redeem-container" class="main-canvas">
    <div class="main-canvas-title">
     <!--   <h2>Canjear Premio</h2> -->
        <?php if ($serial) : ?>
            <h2>Serial: <span><?php echo $serial; ?></span></h2>
        <?php endif; ?>
    </div>
    <div id="redeem-form-container">
        <form id="coupon-redeem-form"
                action="<?php echo url_for('promo_redeem_coupon') . ($serial ? '?serial=' . $serial : '') ?>"
                method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
            <div class="white-frame">
                <?php echo $form ?>
            </div>
            <div class="form_footer">
                <input id="crf-submit" class="form_submit" type="submit" value="Canjear" />
            </div>
        </form>
    </div>
    <?php if ($footer) : ?>
        <div class="main-canvas-content-footer">
            <a href="<?php echo url_for('promo') ?>">Regresar a Mis Promociones</a>
        </div>
    <?php endif; ?>
</div>
<div id="dialog-confirm" title="Confirmar Canje">
    <h3>
        ¿Estás seguro que deseas canjear el siguiente premio?
    </h3>
    <h3>Serial: <b id="prize-serial"></b></h3>
    <h3>Contraseña: <b id="prize-password"></b></h3>
    <p>*Recuerda que una vez se realice el canje esta acción no se puede deshacer</p>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#dialog-confirm").dialog({
            resizable: false,
            height: 280,
            width: 400,
            modal: true,
            autoOpen: false,
            buttons: {
                'Canjear Premio': function() {
                    $(this).dialog('close');
                    $('#coupon-redeem-form').submit();
                },
                'Cancelar': function() {
                    $(this).dialog('close');
                }
            }
        });
        
        $('#coupon-redeem-form').bValidator();

        $('#crf-submit').click(function(event){
            event.preventDefault();
            
            $( "#coupon-redeem-form input[type='submit']" ).attr( 'disabled', 'disabled' );
            
            if ( $("#coupon-redeem-form" ).data( "bValidator" ).validate() ) {
                if ( confirm( "¿Estás seguro que deseas canjear el siguiente premio? Esta acción no puede ser revertida" ) ) {
                    $('#coupon-redeem-form').submit();
                }
//                $('#prize-serial').text($('#epCouponRedeemForm_serial').val());
//                $('#prize-password').text($('#epCouponRedeemForm_password').val());
//                $('#dialog-confirm').dialog('open');
            }
            
            $( "#coupon-redeem-form input[type='submit']" ).removeAttr( "disabled" );
            return false;
        });      
    })
</script>
