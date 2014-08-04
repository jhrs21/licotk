<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo $form->getObject()->isNew() ? url_for('survey_create') : url_for('survey_update', $survey) ?>" 
    method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    <table id="survey">
        <tfoot>
            <tr>
                <td>
                    <a href="<?php echo url_for('survey') ?>">Volver al listado</a>
                    &nbsp;&nbsp;
                    <?php if (!$form->getObject()->isNew()): ?>
                        <a href="<?php echo url_for('survey_show',$form->getObject()) ?>">Volver a detalles</a>
                        &nbsp;&nbsp;
                    <?php endif; ?>
                </td>
                <td class="align-right">
                    <input type="submit" value="Guardar" />
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form->renderGlobalErrors(); ?>
            <?php echo $form['id']->render(); ?>
            <?php echo $form['_csrf_token']->render(); ?>
            <?php echo $form['name']->renderRow(); ?>
            <?php echo $form['description']->renderRow(); ?>
            <?php echo $form['is_active']->renderRow(); ?>
            <?php echo $form['is_master']->renderRow(); ?>
            <?php echo $form['all_promos']->renderRow(); ?>
            <?php echo $form['promos_list']->renderRow(); ?>
            <tr>
                <th>Items</th>
                <td>
                    <table id="items">
                        <tfoot id="items_foot">
                            <tr>
                                <td>
                                    <a id="add_item">Agregar Item</a>
                                    &nbsp;
                                    <img id="item_loader" src="/images/loader16x16.gif" style="display:none">
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ($form['items'] as $key => $item) : ?>
                                <tr id="<?php echo 'item_' . $key ?>">
                                    <th>
                                        <?php echo $key+1 ?>
                                    </th>
                                    <td>
                                        <?php include_partial('itemForm', array('key' => $key, 'item' => $item)) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php echo $form->renderHiddenFields(false) ?>
                </td>
            </tr>
        </tbody>
    </table>
</form>


<script type="text/javascript">
    var items = <?php echo $form['items']->count() ?>;
    var itemsoptions = {};

    function addItem(num) {
        var r = $.ajax({
            type: "GET",
            url: "<?php echo url_for('survey_add_item') . ($form->getObject()->isNew() ? '' : '?id=' . $form->getObject()->getId()) . ($form->getObject()->isNew() ? '?count=' : '&count=') ?>"+num,
            async: false
        }).responseText;
        
        return r;
    }
    
    function addItemOption(item, option) {
        var r = $.ajax({
            type: "GET",
            url: "<?php echo url_for('survey_add_item_option') ?>" + "?item=" + item + "&option=" + option,
            async: false
        }).responseText;
        
        return r;
    }
    
    $(document).ready(function() {
        $( "a#add_item" ).click(function() {
            $( "#item_loader" ).show();
            $( "#items > tbody" ).append( "<tr id='item_" + items + "'><th>" + (items+1) + "</th><td>" + addItem( items ) + "</td></tr>" );
            $( "#item_loader" ).hide();
            items = items + 1;
        });
        
        $( ".add-option" ).live( "click", function() {
            var option = itemsoptions[ "item_" + item ];
            var item = $( this ).attr( "id" ).replace( "survey_items_", "" ).replace( "_add_option", "" );
            
            if ( option == undefined ) {
                option = $( "#item_" + item + "_options tbody:first > tr" ).length;
            }
            
            var domElem = $( this ).attr( "dom_id" );
            
            $( this ).siblings( ".option_loader" ).show();
            $( "#" + domElem + " tbody:first" ).append( "<tr id='item_" + item + "'><th>" + ( option + 1 ) + "</th><td>" + addItemOption( item, option ) + "</td></tr>" );
            $( this ).siblings( ".option_loader" ).hide();
            
            itemsoptions[ "item_" + item ] = option + 1;
        });
        
        $( ".item_type_widget" ).live( "change", function(){
            var itemType = $( this ).val();
            var item = $( this ).attr( "id" ).replace( "survey_items_", "" ).replace( "_item_type", "" );
            
            if ( itemType == "text" || itemType == "date" ) {
                $( "#item_" + item + "_options" ).remove();
                $( "#survey_items_" + item + "_add_option" ).hide();
            }
            else {
                if( $( "#item_" + item + "_options" ).length === 0 ) {
                    $( "#item_" + item + " tbody:first" ).append( "<tr id='item_" + item + "_options'><th>Opciones</th><td><table><tbody></tbody></table></td></tr>" )
                }
                $( "#survey_items_" + item + "_add_option" ).show();
            }
        });

        $( ".delete-trigger" ).live( "click", function(event){
            event.preventDefault();

            var deleteUrl = $( this ).attr( "delete_url" );
            var domElem = $( this ).attr( "dom_id" );
            
            $( "#" + domElem ).hide( "slow", function(){
                if ( deleteUrl ) {
                    var r = $.ajax( { type: 'POST', url: deleteUrl, async: false, dataType: 'json' } );
                }
                $( this ).remove()
            });
        });
    });
</script>