<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
		<!--
			<div class="error-title lightblue" class="main-canvas-title ">
                		<?php echo __('¡Ya tienes una cuenta en TuDescuenton.com!') ?>
			</div>
			<div class="error-content">
                		<p>
                			Ya tienes una cuenta en 
                			<a target="_blank" href="http://tudescuenton.com">TuDescuenton.com</a> 
                			con la dirección de correo que indicaste.
                		</p>
                		<br>
                		<p>
                			Puedes <a class="darkblue" href="<?php echo url_for('sf_guard_signin')?>">ingresar</a> 
                			en LealTag con el mismo correo y contraseña haciendo clic 
                			<b><a class="darkblue" href="<?php echo url_for('sf_guard_signin')?>">AQUÍ</a></b>.
                		</p>
                		<br>
                		<p>El equipo de LealTag</p>
			</div>
		-->
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php include_partial('sfApply/continue') ?></b>
            </div>
        </div>
    </div>
</div>
