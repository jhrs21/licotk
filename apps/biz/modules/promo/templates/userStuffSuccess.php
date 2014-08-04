<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('coupon_validation' => true))) ?>
    <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash flash_error">
            <p class="flash_message"><?php echo $sf_user->getFlash('error') ?></p>
        </div>
    <?php endif ?>
    <div id="redeem-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Premios del Cliente: <?php echo $userClient->getFullName().' - C.I.:'.$userClient->getUserProfile()->getIdNumber() ?></h2>
        </div>
        <div class="main-canvas-content">
            <div id="redeem-prize-form-container">
                <div class="white-frame">
                    <?php foreach ($forms as $key => $form): ?>
                        <form id="redeem-prize-form-<?php echo $key ?>" action="<?php echo url_for('promo_redeem_prizes',array('user' => $userClient->getAlphaId(), 'promo' => $promo->getAlphaId())) ?>" method="post">
                            <div class="form_row">
                                <?php echo $form ?>
                                <div class="form_row_label">
                                    <?php echo $form->getPrize()->getThreshold() . ($form->getPrize()->getThreshold() > 1 ? ' Visitas' : ' Visita') . ': ' ?>
                                </div>
                                <div class="form_row_field">
                                    <input id="rpf-submit-<?php echo $key ?>" 
                                           class="form_submit <?php echo $form->getCanBeRedeemed() ? 'active' : 'disabled' ?>" 
                                           type="submit" 
                                           value="<?php echo $form->getPrize().' - '.($form->getCanBeRedeemed() ? 'Canjeable' : 'No canjeable') ?>" <?php echo $form->getCanBeRedeemed() ? '' : 'disabled="disabled"' ?>>
                                </div>
                            </div>
                        </form>
                        <div id="dialog-confirm-<?php echo $key ?>" title="Confirmar Canje" style="display:none">
                            <h3>¿Está seguro de canjear el premio "<?php echo $form->getPrize() ?>"?</h3>
                            <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
                            <h3>Esta acción no puede ser revertida.</h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">    
    $(document).ready(function(){
        <?php foreach ($forms as $key => $form): ?>
            $('#redeem-prize-form-<?php echo $key ?>').bValidator();
            $('#rpf-submit-<?php echo $key ?>').click(function(event){
                event.preventDefault();
                $('#redeem-prize-form-<?php echo $key ?> > input[type="submit"]').attr('disabled','disabled');

                if ($('#redeem-prize-form-<?php echo $key ?>').data('bValidator').validate()) {
                    $( "#dialog-confirm-<?php echo $key ?>" ).dialog({
                        resizable: false,
                        height:220,
                        modal: true,
                        buttons: {
                            "Canjear Premio": function() {
                                $( this ).dialog( "close" );
                                $('#redeem-prize-form-<?php echo $key ?>').submit();
                            },
                            "Cancelar": function() {
                                $( this ).dialog( "close" );
                            }
                        },
                        close: function( event, ui ) {$('#redeem-prize-form-<?php echo $key ?> > input[type="submit"]').removeAttr('disabled');}
                    });
                }
                else {
                    $('#redeem-prize-form-<?php echo $key ?> > input[type="submit"]').removeAttr('disabled');
                    return false;
                }
            });
        <?php endforeach; ?>      
    })
</script>