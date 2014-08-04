<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo ($form->getObject()->isNew() ? url_for('promo_create') : url_for('promo_update', $form->getObject())) ?>"
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
          <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    <table id="promo">
        <tfoot>
            <tr>
                <td colspan="2">
                    <noscript>
                        <p><strong>NOTE:</strong> To add more Prizes, please save this form, and more spaces will be presented.</p>
                    </noscript>
                    &nbsp;<a href="<?php echo url_for('promo') ?>">Back to list</a>
                    <input type="submit" value="Save" />
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form ?>
        </tbody>
    </table>
</form>

<script type="text/javascript">
    var prizes = <?php echo $form['prizes']->count()?>;
    var terms = <?php echo $form['terms']->count()?>;

    function addPrize(num) {
        var r = $.ajax({
            type: 'GET',
            url: '<?php echo url_for('promo_add_prize')?>'+'<?php echo ($form->getObject()->isNew() ? '' : '?id='.$form->getObject()->getId()).($form->getObject()->isNew() ? '?count=' : '&count=')?>'+num,
            async: false
        }).responseText;
        
        return r;
    }
    
    function addTerm(num) {
        var r = $.ajax({
            type: 'GET',
            url: '<?php echo url_for('promo_add_term')?>'+'<?php echo ($form->getObject()->isNew() ? '' : '?id='.$form->getObject()->getId()).($form->getObject()->isNew() ? '?count=' : '&count=')?>'+num,
            async: false
        }).responseText;
        
        return r;
    }
    
    function populateAssets(value){
        var r = $.ajax({
                    type: 'POST',
                    url: '<?php echo url_for('promo_populate_assets')?>'+'?value='+value,
                    async: false,
                    dataType: 'json'
                }).responseText;
        
        return r;
    }
    
    function affiliateModified(){
        $('#assets_list').parent().html(populateAssets($('#affiliate-select').val()));
    }
    
    $(document).ready(function() {
        $( "#promo > tbody tr td table:first" ).append('<?php echo escape_javascript("<tfoot><tr><td colspan=\"2\">".
                                                "<a id='add_prize'>Agregar Premio</a>&nbsp;<img id=\"prize_loader\" src=\"/images/loader16x16.gif\">"
                                            ."</td></tr></tfoot>")?>'
                                        );
        $( "#promo > tbody > tr:last td table:first" ).append('<?php echo escape_javascript("<tfoot><tr><td colspan=\"2\">".
                                                "<a id='add_term'>Agregar Condición</a>&nbsp;<img id=\"term_loader\" src=\"/images/loader16x16.gif\">"
                                            ."</td></tr></tfoot>")?>'
                                        );
        
        $("#prize_loader").hide();
        
        $("#term_loader").hide();
        
        $("a#add_prize").click(function() {
                $("#prize_loader").show();
                $("#promo table tbody").first().append(addPrize(prizes));
                $("#prize_loader").hide();
                prizes = prizes + 1;
            });
            
       $("a#add_term").click(function() {
                $("#term_loader").show();
                $("#promo > tbody > tr:last td table:first tbody:first").append(addTerm(terms));
                $("#term_loader").hide();
                terms = terms + 1;
            });
            
        $("#affiliate-select").live("change", affiliateModified);
        
        $(".delete-trigger").live("click", function(event){
            event.preventDefault();
            
            var deleteUrl = $(this).attr("url");
            
            $(this).parents("tr").first().hide("slow", function(){
                if (deleteUrl) {
                    var r = $.ajax({type: "POST", url: deleteUrl, async: false, dataType: "json"})
                }
                $(this).remove()
            });
        });
    });
</script>
