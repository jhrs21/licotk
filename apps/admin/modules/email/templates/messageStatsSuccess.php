<?php use_helper('I18N') ?>

<div class="span-24">
    <h1>Resultados del envio del mensaje</h1>
</div>
<div class="span-24">
    <div class="span-4">
        Alcance Máximo:
    </div>
    <div class="span-"2">
        <?php echo $emailMessage->getMaxReach() ?>
    </div>
</div>
<div class="span-24">
    <a href="<?php echo url_for('email_create_custom') ?>">
        Enviar nuevo mensaje
    </a>
</div>
