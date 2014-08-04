<div class="form-container span-24">
    <form id="mcardgenerateform" action="<?php echo url_for('mcard_generate') ?>" method="post" name="mcardgenerateform">
        <label>Cantidad a generar:</label>
        <input id="quantity" name="quantity" type="number" />
        <input id="submit" type="submit" value="Generar" />
        <img id="loader" src="/images/loader.gif" style="vertical-align: middle; display: none" />
    </form>
</div>
<div id="result-container" class="span-24">
    <div id="total" class="span-24">Total: <span id="total-number"><?php echo $total ?></span></div>
    <?php if (isset($quantity)) :?>
        <?php include_partial('generateConfirmation',array('quantity' => $quantity)); ?>
    <?php endif; ?>
</div>
<script type="text/javascript">
//    function ajaxGeneration(number) {
//        $.ajax({
//            type: "POST",
//            url: '<?php echo url_for('mcard_generate') ?>',
//            data: { quantity: number },
//            async: false,
//            cache: false,
//            timeout: 30000,
//            error: function(){
//                return true;
//            },
//            success: function(msg){ 
//                if (parseFloat(msg)){
//                    return false;
//                } else {
//                    return true;
//                }
//            }
//        }).done(function( html ) {
//            $("#result-container").append(html);
//        });
//    }
//    
//    $(document).ready(function(){
//        var total = parseInt($('#total-number').text(),10);
//        var number = 0;
//        
//        $('#mcardgenerateform').submit(function(e){
//            e.preventDefault();
//            number = parseInt($('#quantity').val(),10);
//            
//            while(number > 500){
//                number = number - 500;
//                ajaxGeneration(500);
//                total = total + 500;
//                $("#total-number").html(total);
//            }
//            ajaxGeneration(number);
//            total = total + number;
//            $("#total-number").html(total);
//        });
//    });
</script>