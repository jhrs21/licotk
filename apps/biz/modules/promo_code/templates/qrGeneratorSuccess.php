<?php use_helper('I18N') ?>
<div class="main-container-inner">
    <?php include_partial('html_static/optionsMenu', array('isActive' => array('promo' => true))) ?>
    <div class="form-container">
        <div id="signin-form-container" class="form-container-inner">
            <div class="main-canvas-title">
                <h2>Generador de visitas</h2>
            </div>
            <div class="content">
                <div class="generator">
                    <div class="generated-qr">
                        <img class="qr-code" src="<?php echo Util::auto_version($suffix) ?>">
                        <?php if ($sf_user->hasFlash('no_pc_validation_required')): ?>
                            <div id="no-digital-qr" class="flash_notice box_round box_shadow_bottom"><?php echo $sf_user->getFlash('no_pc_validation_required') ?></div>
                        <?php endif ?>
                    </div>
                    <div class="vcode">
                        <div class="validation-code"><b>Código:</b> <?php echo $vcode ?></div>
                    </div>
                    <form class="generate-button">
                        <?php if (!$sf_user->hasFlash('no_pc_validation_required')): ?>
                            <input class="submit" type="submit" onClick="history.go(0)" value="Generar">
                        <?php endif ?>
                    </form>
                </div>
                <div class="instructions">
                    <h1>Instrucciones</h1>
                    <p>Este es un generador de visitas únicos, cada vez que un cliente solicite escanear su código de Licoteca presione el botón <b>GENERAR</b>. De esta manera el cliente podrá escanearlo al presionar el botón "escanear" desde la aplicación Licoteca.</p><br>
                    <p>Recuerde generar un nuevo código para cada nuevo cliente ya que éstos solo podrán ser escaneados o registrados una sola vez.</p><br>
                    <p>Si el cliente no posee un teléfono inteligente indíquele que registre el código que encontrará encima del botón <b>GENERAR</b>.</p>
                </div>
            </div>
            <div class="main-canvas-content-footer">
                <a href="<?php echo url_for('promo') ?>">Regresar a Mis Promociones</a>
            </div>
        </div>
    </div>
</div>