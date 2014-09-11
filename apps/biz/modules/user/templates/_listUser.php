<?php if(count($users) > 0): ?>
    <div class="list-titles">
        <div class="list-title title-medium"><p>Nombre</p></div>
        <div class="list-title title-big"><p>Correo</p></div>
        <div class="list-title title-small"><p>Número de Visitas</p></div>
        <div class="list-title title-medium"><p>Nivel</p></div>
        <div class="list-title title-medium last"><p>Acciones</p></div>
    </div>
        <?php foreach ($users as $user): ?>
            <div class="promo-row">
                <div class="promo-row-cell promo-row-cell-medium">
                    <a href=""><?php echo $user['fullname'] ?></a>
                </div>
                <div class="promo-row-cell promo-row-cell-big">
                    <?php echo $user['email'] ?>
                </div>
                <div class="promo-row-cell promo-row-cell-small">
                    <?php echo $user['tags'] ?>
                </div>
                <div class="promo-row-cell promo-row-cell-medium">
                    <?php echo $user['level'] ?>
                </div>
                <div class="promo-row-cell promo-row-cell-medium last">
                    <a href="<?php echo url_for('change_user_level').'?u='.$user['id']?>">
                        Cambiar de Nivel
                    </a><br>
                    Modificar visitas
                </div>
            </div>
        <?php endforeach; ?>
<?php else: ?>
<div class="main-canvas-title">
    <h2>No hay premios o no se han encontrado resultados en la busqueda.</h2>
</div>
<?php endif; ?>
<script type="text/javascript">
    jQuery('.cbox-form').colorbox({
        width: '50%'
    });
</script>