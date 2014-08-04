<?php use_helper('I18N') ?>
<div id="main-container" class="box_bottom_round box_shadow white-background">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-canvas box_round gray-background">
        <div class="main-canvas">
            <div class="error-face"></div>
            <div class="error-title lightblue" class="main-canvas-title ">
                <?php echo __('Algo no está bien...') ?>
            </div>
            <div class="error-content">
                <p>Hemos detectado que el código de confirmación de tu cuenta es inválido.</p>
                <br>
                <p>
                    Esto ha podido suceder porque ya tu cuenta ha sido confirmada. Si es así, 
                    ingresa con tu correo y contraseña haciendo clic <b class="darkblue"><?php echo link_to('AQUÍ', url_for('sf_guard_signin'))?></b>.
                </p>
                <br>
                <p class="text-align-left" style="margin:0 3em">Otras explicaciones posibles:</p>
                <br>
                <ol class="text-align-left" style="margin:0 3em">
                    <li>
                        Si copiaste y pegaste el URL desde el correo de confirmación, por favor verifica 
                        haberlo hecho correctamente y haberlo copiado todo.
                    </li>
                    <li>
                        Si recibiste el correo de confirmación hace mucho tiempo y no confirmaste tu cuenta es posible que tu 
                        cuenta haya sido purgada del sistema por inactividad. En este caso, deberá registrarse nuevamente.
                    </li>
                </ol>
                <br>
                <p>Disculpa las molestias.</p>
                <p>El equipo de LealTag</p>
            </div>
            <div class="main-canvas-footer">
                <div class="lightgray-separator separator"></div>
                <b class="darkblue"><?php include_partial('sfApply/continue') ?></b>
            </div>
        </div>
    </div>
</div>

<div id="main-container" class="span-24">
    <?php include_partial('user/colorBanner') ?>
    <div class="main-container-inner">
        <div id="apply-mailer-error-container" class="main-canvas">
            <div id="apply-mailer-error-title" class="main-canvas-title">
                <h2><?php echo __('Algo no está bien...') ?></h2>
            </div>
            <div id="apply-mailer-error-content" class="main-canvas-content apply-content">
                <p>
                    Hemos detectado que el código de confirmación de tu cuenta es inválido.
                </p>
                <br>
                <p>
                    Esto ha podido suceder porque ya tu cuenta ha sido confirmada. Si es así, 
                    ingresa con tu correo y contraseña haciendo clic <?php echo link_to('aquí', url_for('sf_guard_signin'))?>.
                </p>
                <br>
                <p>
                    Otras explicaciones posibles:
                    <br>
                    1. Si copiaste y pegaste el URL desde el correo de confirmación, por favor verifica
                    haberlo hecho correctamente y haberlo copiado todo.
                    <br>
                    2. Si recibiste el correo de confirmación hace mucho tiempo y no confirmaste tu cuenta
                    es posible que tu cuenta haya sido purgada del sistema por inactividad. En este caso,
                    deberá registrarse nuevamente.
                </p>
                <br>
                <p>
                    Disculpa las molestias.
                </p>
                <br>
                <p>El equipo de LealTag</p>
                <div class="main-canvas-content-footer">
                    <?php include_partial('sfApply/continue') ?>                    
                </div>
            </div>
        </div>
    </div>
</div>
