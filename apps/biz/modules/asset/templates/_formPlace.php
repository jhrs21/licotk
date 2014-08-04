<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo $form->getObject()->isNew() ? url_for('asset_create_place') : url_for('asset_update_place', $form->getObject()) ?>" 
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
          <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    <table>
        <tfoot>
            <tr>
                <td colspan="2">
                    <a href="<?php echo url_for('asset_places') ?>">Back to list</a>
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
    function populateSelectOptions(modified, val, update){
        var r = $.ajax({
                    type: 'GET',
                    url: '<?php echo url_for('location_populate_select')?>'+'?widget='+modified+'&value='+val+'&update='+update,
                    async: false,
                    dataType: 'json'
                }).responseText;
        
        return r;
    }
    
    function countryModified(){
        $('#state-select').parent().html(populateSelectOptions('country',$('#country-select').val(),'state'));
        $('#municipality-select').parent().html(populateSelectOptions('state',$('#state-select').val(),'municipality'));
    }
    
    function stateModified(){
        $('#municipality-select').parent().html(populateSelectOptions('state',$('#state-select').val(),'municipality'));
    }
    
    $('#country-select').live("change", countryModified);
    $('#state-select').live("change", stateModified);
    
</script>