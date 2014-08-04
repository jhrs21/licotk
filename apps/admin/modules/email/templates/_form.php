<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form id="custom-email-form" action="<?php echo $form->getObject()->isNew() ? url_for('email_create_custom') : url_for('email_update_custom',$form->getObject()) ?>" 
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
    <table id="email">
        <tfoot>
            <tr>
                <td>
                    <a href="">Cancelar</a>
                </td>
                <td class="align-right">
                    <input class="submit" type="submit" value="Filtrar Destinatarios" />
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form ?>
        </tbody>
    </table>
</form>
<script type="text/javascript">    
    $(document).ready(function(){
        $( '#custom-email-form' ).bValidator({
            offset:     {x:0, y:-10},
            position:   {x:'left', y:'top'}
        });
        
        tinymce.init({
            selector: "textarea",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            width: '600',
            relative_urls : false,
            remove_script_host : false,
            convert_urls : true
        });

        $( '.submit' ).click( function(event){
            event.preventDefault();
            
            $( 'input[type="submit"]' ).attr( 'disabled', 'disabled' );
            if( $( '#custom-email-form' ).data( 'bValidator' ).validate() ){
                $( '#custom-email-form' ).submit();
            }
            else {
                $( 'input[type="submit"]' ).removeAttr( 'disabled' );
                return false;
            }
        });      
    });
</script>