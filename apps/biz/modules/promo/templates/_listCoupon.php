<?php if($pager->getResults()->count() > 0): ?>
    <div class="list-titles">
        <div class="list-title title-small"><p>Serial</p></div>
        <div class="list-title title-big"><p>Premio</p></div>
        <div class="list-title title-small"><p>Estado</p></div>
        <div class="list-title title-medium"><p>Afiliado</p></div>
        <div class="list-title title-small"><p>Fecha de vencimiento</p></div>
        <div class="list-title title-small"><p>Fecha de canje</p></div>
        <div class="list-title title-small last"><p>Acciones</p></div>
    </div>
    <?php foreach ($pager->getResults() as $user): ?>
        <div class="promo-row">
            <div class="promo-row-cell promo-row-cell-small">
                <a href=""><?php echo $user->getSerial() ?></a>
            </div>
            <div class="promo-row-cell promo-row-cell-big"><?php echo $user->getPrize() ?></p></div>
            <div class="promo-row-cell promo-row-cell-small">
                <?php
                switch ($user->getStatus()) {
                    case 'active':
                        echo 'Activo';
                        break;
                    case 'used':
                        echo 'Canjeado';
                        break;
                    case 'expired':
                        echo 'Expirado';
                        break;
                    default:
                        break;
                }
                ?>
            </div>
            <div class="promo-row-cell promo-row-cell-medium">
                <?php echo $user->getUser()->getFullname() ?><br>
                <?php echo $user->getUser()->getUserProfile()->getIdNumber() ?>
            </div>
            <div class="promo-row-cell promo-row-cell-small">
                <?php echo $promo->getDateTimeObject('expires_at')->format('d/m/Y') ?>
            </div>
            <div class="promo-row-cell promo-row-cell-small">
                <?php if($user->getUsedAt()==NULL){ 
                        echo "Sin usar";
                }else {
                        echo $user->getDateTimeObject('used_at')->format('d/m/Y');
                    }?>
            </div>
            <div class="promo-row-cell promo-row-cell-small last">
                <?php if($user->hasStatus('active')): ?>
                    <a class="prize-redeem cbox-form"
                       href="<?php echo url_for('promo_redeem_coupon').'?serial='.$user->getSerial()?>">
                        Canjear
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if ($pager->haveToPaginate()): ?>
        <div class="pagination-container">
            <a title="Primera Página" href="<?php echo url_for('promo_list_coupon', $promo) ?>?page=1">
                << <!--<img src="/images/first.png" alt="First page" title="First page" />-->
            </a>

            <a title="Página Anterior" href="<?php echo url_for('promo_list_coupon', $promo) ?>?page=<?php echo $pager->getPreviousPage() ?>">
                < <!--<img src="/images/previous.png" alt="Previous page" title="Previous page" />-->
            </a>

            <?php foreach ($pager->getLinks() as $page): ?>
                <a class="<?php echo $page == $pager->getPage() ? 'current' : '' ?>" title="Página <?php echo $page ?>" 
                href="<?php echo url_for('promo_list_coupon', $promo) ?>?page=<?php echo $page ?>">
                    <?php echo $page ?>
                </a>
            <?php endforeach; ?>

            <a title="Página Siguiente" href="<?php echo url_for('promo_list_coupon', $promo) ?>?page=<?php echo $pager->getNextPage() ?>">
                > <!--<img src="/images/next.png" alt="Next page" title="Next page" />-->
            </a>

            <a title="Última Página" href="<?php echo url_for('promo_list_coupon', $promo) ?>?page=<?php echo $pager->getLastPage() ?>">
                >> <!--<img src="/images/last.png" alt="Last page" title="Last page" />-->
            </a>
        </div>
    <?php endif; ?>
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