<div class="main-canvas-content">
    <div id="redeem-form-container">
        <form id="search-user-prizes-form" action="<?php echo url_for('promo_search_user_prizes_post') ?>" method="post">
            <div class="white-frame">
                <?php echo $form ?>
            </div>
            <div class="form_footer">
                <input id="supf-submit"class="form_submit" type="submit" value="Buscar Premios" />
            </div>
        </form>
    </div>
    <?php if ($footer) :?>
        <div class="main-canvas-content-footer">
            <a href="<?php echo url_for('promo') ?>">Regresar a Mis Promociones</a>
        </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#search-user-prizes-form').bValidator();

        $('#supf-submit').click(function(event){
            event.preventDefault();
            $('input[type="submit"]').attr('disabled','disabled');
            
            if ($('#search-user-prizes-form').data('bValidator').validate()) {
                $('#search-user-prizes-form').submit();
            }
            else {
                $('input[type="submit"]').removeAttr('disabled');
                return false;
            }
        });      
    })
</script>