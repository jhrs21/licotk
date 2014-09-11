<?php use_helper('I18N') ?>
<div class="prizes-row box_round box_shadow_bottom white-background">
    <div class="prizes-row-thumbnail box_round box_shadow_bottom">
        <?php if ($card->getPromo()->getThumb()): ?>
            <img width="60px" height="60px" src="/uploads/<?php echo $card->getPromo()->getThumb(); ?>">
        <?php else: ?>
            <img width="60px" height="60px" src="/uploads/<?php echo $card->getPromo()->getAffiliate()->getThumb(); ?>">
        <?php endif; ?>
    </div>
    <div class="prizes-row-promo-details">
        <div class="prizes-row-promo">
            <a class="darkgray" href="<?php echo url_for('user_prize',$card)?>">
                <?php echo $card->getPromo()->getName();?>
            </a>
        </div>
        <div class="prizes-row-actions">
            <?php if (strcasecmp($card->getStatus(), 'active') == 0) :?>
                <a class="lt-button lt-button-blue box_round" href="<?php echo url_for('user_prize',$card)?>"><?php echo __('Ver mas');?></a>
                <?php if (count($card->getCanBeExchangedFor())): ?>
                    <a class="lt-button lt-button-orange box_round" href="<?php echo url_for('pre_generate_coupon',$card)?>"><?php echo __('Solicitar premio');?></a>
                <?php endif; ?>
            <?php elseif (strcasecmp($card->getStatus(), 'complete') == 0) :?>
                <a class="lt-button lt-button-orange box_round" href="<?php echo url_for('pre_generate_coupon',$card)?>"><?php echo __('Solicitar premio');?></a>
            <?php elseif (strcasecmp($card->getStatus(), 'exchanged') == 0) : ?>
                <?php $user = $card->getCoupon(); ?>
                <a class="lt-button lt-button-purple box_round" target="_blank"
                   href="<?php echo url_for('generate_coupon',array('alpha_id' => $card->getAlphaId(),'prize' => $card->getCoupon()->getPrize()->getAlphaId()))?>">
                       <?php echo __('Ver premio');?>
                </a>               
            <?php endif; ?>
        </div>
        <div class="prizes-row-description">
            <?php echo $card->getPromo()->getDescription() ?>
        </div>
    </div>
    <div class="prizes-row-info darkgray">
        <div class="prizes-row-progress">
            <?php if (strcasecmp($card->getStatus(), 'active') == 0) :?>
                <?php echo '<span class="lightblue">'.$card->getTickets()->count().'</span>/'.$card->getPromo()->getGreatestThreshold().' Visita(s).' ?>
            <?php elseif (strcasecmp($card->getStatus(), 'complete') == 0) :?>
                <?php echo __('¡Aún no has reclamado tu premio!') ?>
            <?php elseif (strcasecmp($card->getStatus(), 'exchanged') == 0) : ?>
                <?php $timeObj = $card->getPromo()->getDateTimeObject('expires_at'); ?>
                <?php echo __('Tienes').' <span class="lightblue">'.$timeObj->diff(new DateTime("now"))->format('%a').' días</span> para usar tu premio' ?>
            <?php elseif (strcasecmp($card->getStatus(), 'redeemed') == 0) :?>
                <?php echo __('Usado el día')?>: 
                <?php if ($card->getCoupon()->getUsedAt()) : ?>
                    <?php echo $card->getCoupon()->getDateTimeObject('used_at')->format('d/m/Y') ?>
                <?php else : ?>
                    <?php echo $card->getDateTimeObject('updated_at')->format('d/m/Y') ?>
                <?php endif; ?>
            <?php elseif (strcasecmp($card->getStatus(), 'expired') == 0) :?>
                <?php echo __('Expiró el').': '.$card->getPromo()->getDateTimeObject('expires_at')->format('d/m/Y') ?>
            <?php endif; ?>
        </div>
    </div>
</div>