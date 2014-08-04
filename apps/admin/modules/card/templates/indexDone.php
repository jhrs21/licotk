<div class="span-8">
    <h1>Resultados</h1>
</div>
<div class="span-24">
    <h1>Promoción: <?php echo $promo ?></h1>
    <h1>Usuarios recuperados: <?php echo $users ?></h1>
    <br>
    <h1>Antes de unificar:</h1>
    <h1>Tarjetas totales (activas y completadas): <?php echo $oCards ?></h1>
    <h1>Tags totales (en tarjetas activas y completadas): <?php echo $oTags ?></h1>
    <br>
    <h1>Luego de unificar:</h1>
    <h1>Tarjetas totales (activas y completadas): <?php echo $fCards ?></h1>
    <h1>Tags totales (en tarjetas activas y completadas): <?php echo $fTags ?></h1>
    <br>
    <a href="<?php echo url_for('card_unify')?>">Volver</a>
</div>