<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('give_tag' => true))) ?>
    <?php if ($sf_user->hasFlash('tag_success')): ?>
        <div class="flash flash_success">
            <p class="flash_message"><?php echo $sf_user->getFlash('tag_success') ?></p>
        </div>
    <?php endif ?>
    <?php if ($sf_user->hasFlash('tag_error')): ?>
        <div class="flash flash_error">
            <p class="flash_message"><?php echo $sf_user->getFlash('tag_error') ?></p>
        </div>
    <?php endif ?>
    <div id="redeem-container" class="main-canvas">
        <div class="main-canvas-title">
            <h2>Acreditar una visita</h2>
        </div>
        <div class="main-canvas-content">
            <div id="redeem-form-container">
                <form id="tag-form" action="<?php echo url_for('register_tag') ?>" method="post">
                    <div class="white-frame">
                        <?php echo $form ?>
                        <?php include_component('tag', 'generatorQR', array('promoid' => $promo->getId())) ?>
                    </div>
                    <div class="form_footer">
                        <input class="form_submit" type="submit" value="Acreditar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){        
        $('#tag-form').bValidator();

        $('.form_submit').click(function(event){
            event.preventDefault();

            if ($('#tag-form').data('bValidator').validate()) {
                $('#tag-form').submit();
            }
            else {
                return false;
            }
        });      
    })
</script>