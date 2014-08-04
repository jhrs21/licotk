<div id="gtd-header">
    <div id="gtd-header-container" class="container">
        <div id="td_header">
            <ul>

            <!--
                <li class="separador"></li>
                <li>
                    <a href="http://www.tudescuenton.com" target="blank"><img src="/images/td.png" height="33px" style="padding: 0 10px;" alt=""></a>	
                </li>
                <li class="separador"></li>
                <li>
                    <a href="http://www.lealtag.com"><img src="/images/lt.png" height="33px" style="padding: 1px 10px;" alt=""></a>
                </li>
                <li class="separador"></li>
                <li><a href="http://www.sucucho.com" target="blank"><img src="/images/su.png" height="35px" style="padding: 0 10px;" alt=""></a></li>
                <li class="separador"></li>
            -->

                <?php if ($sf_user->isAuthenticated()): ?>
                    <li class='button text-shadow'>
                        <a href="<?php echo url_for('sf_guard_signout') ?>">Salir</a>
                    </li>
                    <li class='button text-shadow'>
                        <a href="<?php echo url_for('user_prizes') ?>">Tu Cuenta</a>
                    </li>
                <?php else: ?>
                    <li class='button'>
                        <a href="<?php echo url_for('apply') ?>">Regístrate</a>
                    </li>
                    <li id="ingresa" class='button'>
                        <a href="<?php echo url_for('sf_guard_signin') ?>">Ingresa</a>
                    </li>
                <?php endif; ?>	
            </ul>
            <div id="new_login_div" class="box_bottom_round"><div class="blue-background"></div>
                <div class="formNewLogin">
                    <div class="newlogin_all">
                        <form id="login-user-form" method="post" action="<?php echo url_for('@sf_guard_login') ?>" class="validator">
                            <?php echo new epUserLoginForm() ?>
                            <a class="forget_password" href="<?php echo url_for('@sf_guard_password') ?>">¿Olvidaste tu Constraseña?</a><br>
                            <div class="newlogin_recordar">
                                <input type="checkbox" value="1" name="recordar" class="remember">
                                Recordar
                                <input class="newlogin_formbutton lt-button lt-button-blue box_round opensanscondensedlight" type="submit" name="commit" value="Login">
                            </div>
                        </form>     
                    </div>
                </div>
                <div class="newlogin_all_registrer blue-background box_bottom_round">
                    ¿No tienes cuenta? <b>
                        <a href="<?php echo url_for('apply') ?>"> Regístrate Aquí </a></b>
                </div>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
</div>
<div id="gtd-header-bottom" class="box_shadow_bottom"></div>
