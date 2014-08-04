<form action="<?php echo url_for($route)?>" method="<?php echo $method?>">
    <table>
        <tfoot>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Probar" />
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form ?>
        </tbody>
    </table>
</form>