<div id="main-container" class="span-24">
    <?php include_partial('analytics/colorBanner') ?>
    <div class="form-container">
        <div class="container-header">
            <h1>Canjear Cupón</h1>

            <form action="<?php echo url_for('promo_redeem_coupon') ?>"
                  method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
                <table>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                              <!--a href="<?php echo url_for('promo_list_coupon', $promo) ?>">Back to list</a-->
                                <input type="submit" value="Canjear" />
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php echo $form ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>