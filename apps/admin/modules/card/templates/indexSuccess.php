<div class="span-8">
    <h1>Seleccionar Promoción</h1>
</div>
<div class="span-24">
    <form action="<?php echo url_for('card_unify')?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
        <table id="survey">
            <tfoot>
                <tr>
                    <td class="align-right">
                        <input type="submit" value="Unificar tarjetas" />
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php echo $form ?>
            </tbody>
        </table>
    </form>
</div>