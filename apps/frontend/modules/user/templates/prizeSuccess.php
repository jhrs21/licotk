<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background many-canvas">
    <?php include_partial('userHeader', array('isActive' => array('user_prizes' => true)))?>
    <?php if ($sf_user->hasFlash('tag_registration_succeeded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('tag_registration_succeeded') ?></p>
        </div>
    <?php endif ?>
    <?php if ($sf_user->hasFlash('lt_pts_awarded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('lt_pts_awarded') ?></p>
        </div>
    <?php endif; ?>
    <div class="main-canvas box_round gray-background prize-details">
        <div class="prize-image box_round box_shadow_bottom">
            <?php $image = $card->getPromo()->getPhoto() ? $card->getPromo()->getPhoto() : $card->getPromo()->getAffiliate()->getLogo(); ?>
            <img src="/uploads/<?php echo $image; ?>"/>
        </div>
        <div class="program-info box_round white-background darkgray">
            <div class="program-name"><?php echo $card->getPromo()->getName();?></div>
            <div class="separator darkgray-background"></div>
        </div>
        <div class="prize-detail-block box_round white-background darkgray">
            <div class="waw-affiliate-program-description">
                <div class="waw-affiliate-title"><?php echo __('Descripción') ?></div>
                <div class="waw-affiliate-content">
                    <?php echo $card->getPromo()->getDescription() ?>
                </div>
            </div>
            <div class="program-dates-container">
                <div class="program-dates">
                    <div class="waw-affiliate-title"><?php echo __('Período para acumular visitas') ?></div>
                    <div class="waw-affiliate-content">
                        <?php echo __('Desde').': '.$card->getPromo()->getDateTimeObject('starts_at')->format('d/m/Y').' - '.__('Hasta').': '.$card->getPromo()->getDateTimeObject('ends_at')->format('d/m/Y'); ?>
                    </div>
                </div>
                <div class="program-dates">
                    <div class="waw-affiliate-title"><?php echo __('Período para canjear Premios') ?></div>
                    <div class="waw-affiliate-content">
                        <?php echo __('Desde').': '.$card->getPromo()->getDateTimeObject('begins_at')->format('d/m/Y').' - '.__('Hasta').': '.$card->getPromo()->getDateTimeObject('expires_at')->format('d/m/Y'); ?>
                    </div>
                </div>
            </div>
            <div class="program-user-details">
                <div class="program-user-progress darkgray-background box_round">
                    <div class="program-user-tags white">
                        <?php echo __('Visitas').': <span class="lightblue">'.$card->countTickets().'</span>/'.$card->getPromo()->getGreatestThreshold()?>
                    </div>
                    <ul>
                        <?php for($i = 1; $i <= $card->countTickets(); $i++): ?>
                            <li class="<?php echo $i>6 ? 'top-space' : ''?> <?php echo ($i%6 == 0) ? 'last' : ''?>">
                                <span class="lt-gift lt-gift-blue"></span>
                            </li>
                        <?php endfor; ?>
                        <?php for($j = $i; $j <= $card->getPromo()->getGreatestThreshold(); $j++): ?>
                            <li class="<?php echo $j>6 ? 'top-space' : ''?> <?php echo ($j%6 == 0) ? 'last' : ''?>">
                                <span class="lt-gift lt-gift-white"></span>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </div>
                <div class="promo-actions">
                    <?php if($card->hasStatus('active')): ?>
                        <div class="register-tag">
                            <?php include_partial('user/tagForm',array('form'=>$tagForm)); ?>
                        </div>
                    <?php endif;?>
                    <?php if($card->hasStatus('complete') || ($card->hasStatus('active') && count($card->getCanBeExchangedFor()))): ?>
                        <div class="claim-prize" class="last">
                            <?php include_partial('user/claimPrizeForm',array('form'=>$prizeForm,'card'=>$card)); ?>
                        </div>
                    <?php endif;?>
                    <?php if($card->hasStatus('exchanged')): ?>
                        <div class="show-prize">
                            <a class="lt-button lt-button-purple box_round box_shadow_bottom opensanscondensedlight" 
                               href="<?php echo url_for('generate_coupon',array("alpha_id" => $card->getAlphaId(), "prize" => $card->getCoupon()->getPrize()->getAlphaId())) ?>"
                               target="_blank">
                                Ver Premio
                            </a>
                        </div>
                    <?php endif;?>
                    <?php if($card->hasStatus('redeemed')): ?>
                        <div class="no-action darkgray main-canvas-title">
                            <?php echo __('El premio ya ha sido canjeado')?>
                        </div>
                    <?php endif;?>
                    <?php if($card->hasStatus('expired')): ?>
                        <div class="no-action darkgray main-canvas-title">
                            <?php echo __('El premio ya ha expirado')?>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <div class="prize-detail-block box_round white-background darkgray">
            <div class="waw-affiliate-program-prizes">
                <div class="waw-affiliate-title"><?php echo __('Premios') ?></div>
                <div class="waw-affiliate-content">
                    <ul>
                        <?php foreach ($card->getPromo()->getPrizes() as $prize) : ?>
                            <li>
                                <?php echo $prize->getThreshold()?> Visitas(s): <?php echo $prize->getPrize()?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="prize-detail-block box_round white-background darkgray">
            <div class="waw-affiliate-title">
                <?php echo $card->getPromo()->getAssets()->getFirst()->getAssetType() == 'place' ?
                            __('Estableciminetos participantes') : __('Marcas participantes');?>
            </div>
            <div class="waw-affiliate-content">
                <ul>
                    <?php foreach ($card->getPromo()->getAssets() as $asset) : ?>
                        <li>
                            <?php echo $asset->getName().
                                    ($asset->getAssetType() == 'place' ? ' - Dirección: '.$asset->getLocation()->getFirst()->getAddress() : '') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="prize-detail-block box_round white-background darkgray">
            <div class="waw-affiliate-title"><?php echo __('Condiciones'); ?></div>
            <div class="waw-affiliate-content">
                <ul>
                    <?php foreach($card->getPromo()->getTerms() as $term): ?>
                        <li>
                            <?php echo $term->getTerm() ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="prize-detail-block box_round white-background darkgray">
            <div class="waw-affiliate-content">
                INDEPABIS: <?php echo $card->getPromo()->getIndepabis() ?>
            </div>
        </div>
    </div>
</div>
<?php if($sf_user->getAskForFeedback()): ?>
    <?php $asset = $sf_user->getTaggedPromoCode()->getAsset();?>
    <?php $str = $asset->getAssetType() == 'place' ? __('en') : __('con');?>
    <?php $title = __('¿Qué tal fue tu experiencia').' '.$str.' '.$asset->getName().'?';?>
    <div style="display: none">
        <a id="feedback-modal-opener"></a>
        <div id="feedback-modal" class="ep-modal">
            <div class="ep-modal-title blue text-align-center"><b><?php echo __('¡Tu opinión es importante para nosotros!')?></b></div>
            <div class="ep-modal-title darkgray text-align-center"><?php echo $title?></div>
            <div class="ep-modal-form-container">
                <?php include_partial('user/feedbackForm',array('form' => $feedbackForm))?>
            </div>
            <div class="ep-modal-text-small text-align-left darkgray"><?php echo '*'.__('Opina libremente, tus datos personales no serán compartidos con nadie.')?></div>
            <div class="ep-modal-text-small text-align-left darkgray"><?php echo '**'.__('Evita realizar comentarios ofensivos al dar tu opinión.')?></div>
        </div>
        <div id="modal-loader" class="loader">
            <img alt="Cargando..." src="/images/loader90x90whitebackground.gif">
        </div>
        <a id="feedback-modal-success-opener"></a>
        <div id="feedback-modal-success" class="ep-modal"></div>
    </div>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function(){
        var effect = 'blind';
        var options = {};
        $('.flash_notice').click(function(){
            $(this).hide( effect, options, 1000);
        });
        <?php if($sf_user->getAskForFeedback()): ?>
            $( "#feedback-modal-opener" ).colorbox({
                inline:true,
                width:"60%",
                heigth:"60%",
                href:"#feedback-modal",
                open:true,
                onComplete: function(){
                        $('#feedback-form').bValidator();
                        $('#feedback-form-submit').click(function(event){
                            event.preventDefault();
                            $('input[type="submit"]').attr('disabled','disabled');
                            if($('#feedback-form').data('bValidator').validate()){
                                $.ajax({
                                    type: 'POST',
                                    url: $('#feedback-form').attr('action'),
                                    data: $('#feedback-form').serialize(),
                                    beforeSend: function(){
                                            $('#feedback-modal').hide();
                                        },
                                    success: function(response){
                                            $('#feedback-modal-success').html(response);
                                        },
                                    complete: function() {
                                            $('#feedback-modal-success-opener').colorbox({
                                                inline:true,
                                                width:"70%",
                                                href:"#feedback-modal-success",
                                                open:true
                                            })
                                        }
                                });
                            }
                            else{
                                $('input[type="submit"]').removeAttr('disabled');
                                return false;
                            }
                        });
                    }
            });
        <?php endif; ?>
    });
</script>