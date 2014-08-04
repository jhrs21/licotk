<?php use_helper('I18N') ?>
<?php slot('page_title', 'Tus premios | Licoteca') ?>
<?php slot('metas') ?>
    <meta content="" name="abstract"/>
    <meta content="Los premios que has obtenido por ser un cliente fiel" name="description"/>
    <meta content="" name="keywords"/>
    <meta content="" name="keyphrases"/>
    <meta content="index, follow" name="robots"/>
    <meta content="<?php // echo url_for('@howto_affiliate', true); ?>" property="og:url"/>
    <meta content="website" property="og:type"/>
    <meta content="Licoteca" property="og:site_name"/>
    <meta content="Los premios que he obtenido por ser un cliente fiel" property="og:description"/>
<?php end_slot() ?>
<?php $cards = $sf_data->getRaw('cards') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('userHeader', array('isActive' => array('user_prizes' => true)))?>
    <?php if ($sf_user->hasFlash('profile_update_succeeded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('profile_update_succeeded') ?></p>
        </div>
    <?php endif; ?>
    <?php if ($sf_user->hasFlash('tag_registration_succeeded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('tag_registration_succeeded') ?></p>
        </div>
    <?php endif ?>
    <?php if ($sf_user->hasFlash('prize_redeem_succeeded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo $sf_user->getFlash('prize_redeem_succeeded') ?></p>
        </div>
    <?php endif; ?>
    <?php if ($sf_user->hasFlash('lt_pts_awarded')): ?>
        <div class="flash_notice flash_success box_round box_shadow_bottom">
            <p class="flash_message"><?php echo sfOutputEscaper::unescape($sf_user->getFlash('lt_pts_awarded')) ?></p>
        </div>
    <?php endif; ?>
    <ul class="prizes-tab-menu tab-menu">
        <li id="complete-prizes" class="box_top_left_round box_top_right_round"><?php echo __('Premios por Canjear')?></li>
        <li id="active-prizes" class="box_top_left_round box_top_right_round"><?php echo __('Premios por Alcanzar')?></li>
        <li id="redeemed-prizes" class="box_top_left_round box_top_right_round"><?php echo __('Premios Canjeados')?></li>
        <li id="expired-prizes" class="box_top_left_round box_top_right_round"><?php echo __('Premios Expirados')?></li>
    </ul>
    <span class="clear"></span>
    <div id="tabs" class="main-canvas gray-background box_bottom_round box_top_right_round">  
        <div class="prizes-container complete-prizes">
            <?php if (isset($cards['complete']) && count($cards['complete'])): ?>
                <?php foreach ($cards['complete'] as $card): ?>
                    <?php $promo = $card->getPromo(); ?>
                    <?php include_partial('prizeRow', array('card'=>$card,'promo'=>$promo)) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="prizes-empty-tab">
                    <div class="main-canvas-title lightblue">
                        <?php echo __("¡Aún no tienes premios que canjear!") ?>
                    </div>
                    <div class="main-canvas-subtitle darkgray">
                        <?php echo __("¡Continúa acumulando visitas y pronto obtendrás premios!") ?>
                    </div>
                    <div class="lt-corner lt-corner-small lt-corner-small-tl"></div>
                    <div class="lt-corner lt-corner-big lt-corner-big-br"></div>
                </div>
            <?php endif; ?>
        </div>
        <div class="prizes-container active-prizes">
            <?php if (isset($cards['active']) && count($cards['active'])): ?>
                <?php foreach ($cards['active'] as $card): ?>
                    <?php $promo = $card->getPromo(); ?>
                    <?php include_partial('prizeRow', array('card'=>$card,'promo'=>$promo)) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="prizes-empty-tab">
                    <div class="main-canvas-title">
                        <?php echo __("¡No tienes premios por alcanzar!") ?>
                    </div>
                    <div class="main-canvas-subtitle">
                        <?php echo __("¡Canjea tus premios o continúa acumulando visitas para obtener premios!") ?>
                    </div>
                    <div class="lt-corner lt-corner-small lt-corner-small-tl"></div>
                    <div class="lt-corner lt-corner-big lt-corner-big-br"></div>
                </div>
            <?php endif; ?>
        </div>
        <div class="prizes-container redeemed-prizes">
            <?php if (isset($cards['redeemed']) && count($cards['redeemed'])): ?>
                <?php foreach ($cards['redeemed'] as $card): ?>
                    <?php $promo = $card->getPromo(); ?>
                    <?php include_partial('prizeRow', array('card'=>$card,'promo'=>$promo)) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="prizes-empty-tab">
                    <div class="main-canvas-title lightblue">
                        <?php echo __("¡Aún no has canjeado ningun premio!") ?>
                    </div>
                    <div class="main-canvas-subtitle darkgray">
                       <?php echo __("¡Continúa acumulando visitass para obtener premios!") ?>
                    </div>
                    <div class="lt-corner lt-corner-small lt-corner-small-tl"></div>
                    <div class="lt-corner lt-corner-big lt-corner-big-br"></div>
                </div>
            <?php endif; ?>
        </div>
        <div class="prizes-container expired-prizes">
            <?php if (isset($cards['expired']) && count($cards['expired'])): ?>    
                <?php foreach ($cards['expired'] as $card): ?>
                    <?php $promo = $card->getPromo(); ?>
                    <?php include_partial('prizeRow', array('card'=>$card,'promo'=>$promo)) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="prizes-empty-tab">
                    <div class="main-canvas-title lightblue">
                        <?php echo __("¡Que bien!") ?>
                    </div>
                    <div class="main-canvas-subtitle darkgray">
                       <?php echo __("No tienes premios expirados") ?>
                    </div>
                    <div class="lt-corner lt-corner-small lt-corner-small-tl"></div>
                    <div class="lt-corner lt-corner-big lt-corner-big-br"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function handleCompletePrizesTab(){
        //change status &amp;amp;amp; style menu  
        $("#complete-prizes").addClass("active");
        $("#active-prizes").removeClass("active");
        $("#redeemed-prizes").removeClass("active");
        $("#expired-prizes").removeClass("active");
        //display selected division, hide others  
        $("div.complete-prizes").fadeIn();
        $("div.active-prizes").css("display", "none");
        $("div.redeemed-prizes").css("display", "none");
        $("div.expired-prizes").css("display", "none");
    }
    
    function handleActivePrizesTab(){
        //change status &amp;amp;amp; style menu
        $("#active-prizes").addClass("active");
        $("#complete-prizes").removeClass("active");
        $("#redeemed-prizes").removeClass("active");
        $("#expired-prizes").removeClass("active");
        //display selected division, hide others  
        $("div.active-prizes").fadeIn();  
        $("div.complete-prizes").css("display", "none");  
        $("div.redeemed-prizes").css("display", "none");  
        $("div.expired-prizes").css("display", "none");
    }
    $(document).ready(function(){
        <?php if (isset($cards['complete']) && count($cards['complete'])): ?>
            handleCompletePrizesTab();
        <?php elseif (isset($cards['active']) && count($cards['active'])): ?>
            handleActivePrizesTab();
        <?php endif; ?>
        $(".tab-menu > li").click(function(e){  
            switch(e.target.id){  
                case "complete-prizes":  
                    handleCompletePrizesTab()
                break;  
                case "active-prizes":  
                    handleActivePrizesTab();
                break;  
                case "redeemed-prizes":  
                    //change status &amp;amp;amp; style menu  
                    $("#redeemed-prizes").addClass("active");
                    $("#complete-prizes").removeClass("active");
                    $("#active-prizes").removeClass("active");
                    $("#expired-prizes").removeClass("active");
                    //display selected division, hide others  
                    $("div.redeemed-prizes").fadeIn();  
                    $("div.complete-prizes").css("display", "none");  
                    $("div.active-prizes").css("display", "none");  
                    $("div.expired-prizes").css("display", "none");
                break;
                case "expired-prizes":  
                    //change status &amp;amp;amp; style menu  
                    $("#expired-prizes").addClass("active");
                    $("#complete-prizes").removeClass("active");
                    $("#active-prizes").removeClass("active");
                    $("#redeemed-prizes").removeClass("active");
                    //display selected division, hide others  
                    $("div.expired-prizes").fadeIn();  
                    $("div.complete-prizes").css("display", "none");  
                    $("div.active-prizes").css("display", "none");  
                    $("div.redeemed-prizes").css("display", "none");
                break;
            }  
            //alert(e.target.id);  
            return false;  
        });
        
        var effect = 'blind';
        var options = {};
        $('.flash_notice').click(function(){
            $(this).hide( effect, options, 1000);
        });  
    });
</script>
