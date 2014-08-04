<div id="color_nav" class="span-24">
    <div id="color-banner" class="span-24 color-banner <?php echo isset($usesHighlight) && $usesHighlight ? 'highlight' : ''?>">
        <div class="purpule <?php echo isset($highlightFirst) && $highlightFirst ? 'highlight' : ''?>">
            <a href="<?php echo url_for('howto_user') ?>"><h1>¿Cómo funciona?</h1></a>
        </div>
        <div class="blue <?php echo isset($highlightSecond) && $highlightSecond ? 'highlight' : ''?>">
            <a href="#" class="opener"><h1>Descarga la aplicación</h1></a>
        </div>
        <div class="orange <?php echo isset($highlightThird) && $highlightThird ? 'highlight' : ''?>">
            <a href="<?php echo url_for('apply') ?>"><h1>Crea tu cuenta</h1></a>
        </div>
    </div>
    <?php if(isset($usesHighlight) && $usesHighlight): ?>
        <div class="color_nav_grey_line last">
            <?php 
                $class = '';
                    if(isset($highlightFirst) && $highlightFirst){
                        $class = 'color_nav_pike_purple';
                    } elseif(isset($highlightSecond) && $highlightSecond){
                        $class = 'color_nav_pike_blue';
                    } elseif (isset($highlightThird) && $highlightThird) {
                        $class = 'color_nav_pike_orange';
                    }
            ?>
            <div class="color_nav_pike <?php echo $class ?>"></div>
        </div>
    <?php endif; ?>
</div>

<div style='display:none'>
    <div id="download-modal" class="ep-modal">
        <h2 class="blue big">¡Descarga LealTag ya!</h2>
        <h2>Ingresa desde tu celular o tablet en <span class="blue">www.lealtag.com/descarga</span>, o busca <span class="blue">LealTag</span> en la tienda de 
            aplicaciones.</h2>
        <img alt="http://lealtag.com/descarga" src="/images/download_qr.png"/>
        <h2>También puedes escanear este código QR para descargar la aplicación</h2>
    </div>
</div>