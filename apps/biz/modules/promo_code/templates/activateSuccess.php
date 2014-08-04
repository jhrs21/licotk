<h1>Activar Códigos QR</h1>

<form action="<?php echo url_for('promo_code_activate_post', $affiliate)?>"
      method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <a href="<?php echo url_for('promo_code', $affiliate) ?>">Back to list</a>
          <input type="submit" value="Activar" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>