<?php use_helper('I18N') ?>

<h1>Módulo de envío de correos</h1><br>
<button type="button" onclick="location.href='<?php echo url_for('email_send_registration_remainder') ?>'">Enviar a pre-registrados</button><br>
<form id="send-reminder-form" action="<?php echo url_for("email_index") ?>" 
      method="post">
    <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" 
           type="submit" value="<?php echo __('Enviar recordatorio a ') ?>" />
           <?php echo $form ?>
</form>
<form id="send-reminder-form" action="<?php echo url_for("email_index") ?>" 
      method="post">
    <input class="lt-button lt-button-blue box_round opensanscondensedlight submit" 
           type="submit" value="<?php echo __('Enviar a nuevos usuarios ') ?>" />
           <?php echo $form2 ?>
</form>
<!-- TEMPORAL -->
<br><br><button type="button" onclick="location.href='<?php echo url_for('email_test') ?>'">Prueba</button>