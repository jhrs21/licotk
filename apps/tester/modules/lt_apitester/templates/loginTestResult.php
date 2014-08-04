<?php include_partial('functionsMenu')?>
<div class="span-24">
    <h1>Probando: Login</h1>
    <div class="span-8">
        <h2>Valores consultados</h2>
        <?php foreach ($values as $key => $value): ?>
        <div class="span-3"><?php echo ucfirst($key) ?>:</div>
            <div class="span-5 last"><?php echo $value ?></div>
        <?php endforeach; ?>
    </div>
    <div class="span-16 last">
        <h2>Respuesta del Api</h2>
        <div class="span-16 last">
            <?php var_dump($sf_data->getRaw('result')) ?>
        </div>
    </div>
</div>
<div class="span-24">
    <h2>Probar otra vez</h2>
    <?php include_partial('testForm', array('form' => $form, 'route' => 'lt_apitester_login_test', 'method' => 'post'))?>
</div>