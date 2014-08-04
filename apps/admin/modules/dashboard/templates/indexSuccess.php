<?php use_helper('sdInteractiveChart'); ?>
<div class="form-fields">
    <form action="#" method="post">
        <?php echo $form ?>
        <input type="submit" value="Filtrar" />
        <a href="<?php echo url_for('homepage') ?>">Reiniciar</a>
    </form>
</div>
<table id="general-stats">
    <thead>
        <tr><th colspan="3">General</th><th colspan="3">Tipos de tags</th><th colspan="3">Medios de canje</th></tr>
        <tr><th></th><th>total</th><th>filtro</th><th></th><th>total</th><th>filtro</th><th></th><th>total</th><th>filtro</th></tr>
    </thead>
    <tbody>
        <tr>
            <td>usuarios preregistrados</td>    <td><?php echo $user_list['1'] ?></td>       <td>0</td>  <td>app</td>            <td><?php echo $tag_list['app'] ?></td>   <td>0</td>  <td>tablet</td>     <td>0</td>                                  <td>0</td>
        </tr>
        <tr>
            <td>usuarios registrados</td>       <td><?php echo $user_list['0'] ?></td>       <td>0</td>  <td>web</td>            <td><?php echo $tag_list['web'] ?></td>                                  <td>0</td>  <td>biz</td>        <td>0</td>                                  <td>0</td>
        </tr>
        <tr>
            <td>afiliados (*no se filtra)</td>  <td><?php echo $affiliates->count() ?></td>  <td>0</td>  <td>web email</td>      <td><?php echo $tag_list['web_email'] ?></td>      <td>0</td>  <td></td>           <td></td>                                   <td></td>
        </tr>
        <tr>
            <td>canjes</td>                     <td><?php echo $total_exchanged ?></td>      <td>0</td>  <td>web card</td>       <td><?php echo $tag_list['web_card'] ?></td>                                  <td>0</td>  <th colspan="3" bgcolor="#C3D9FF">Comentarios</th>
        </tr>
        <tr>
            <td>empleados</td>                  <td><?php echo $employees ?></td>            <td>0</td>  <td>tablet</td>         <td><?php echo $tag_list['tablet'] ?></td>                                  <td>0</td>  <td>buenos</td>     <td><?php echo $feedback_list['2'] ?></td>   <td>0</td>
        </tr>
        <tr>
            <td>locales</td>                    <td><?php echo $asset_list['place'] ?></td>  <td>0</td>  <td>tablet email</td>   <td><?php echo $tag_list['tablet_email'] ?></td>    <td>0</td>  <td>neutros</td>    <td><?php echo $feedback_list['1'] ?></td>   <td>0</td>
        </tr>
        <tr>
            <td>marcas</td>                     <td><?php echo $asset_list['brand'] ?></td>  <td>0</td>  <td>tablet card</td>    <td><?php echo $tag_list['tablet_card'] ?></td>                                   <td>0</td>   <td>malos</td>      <td><?php echo $feedback_list['0'] ?></td>   <td>0</td>
        </tr>
        <tr>
            <td></td>                           <td></td>                                   <td></td>   <td>indefinido</td>     <td><?php echo $tag_list['other'] ?></td>                                   <td>0</td>   <td></td>      <td></td>   <td></td>
        </tr>
    </tbody>
</table>
<div class="charts">
    <table id="composition_chart">
        <tr>
            <td>
                <div id="first" class="rec first_c"></div>
                <div id="second" class="rec first_c"></div>
                <div id="third" class="rec first_c"></div>
                <div id="fourth" class="rec first_c"></div>
            </td>

            <td>
                <div id="first" class="rec second_c" style="height: <?php echo $total_user_list + 1 ?>px;"></div>
                <div id="second" class="rec second_c" style="height: <?php echo (200 - $total_user_list) / 3 ?>px;"></div>
                <div id="third" class="rec second_c" style="height: <?php echo (200 - $total_user_list) / 3 ?>px;"></div>
                <div id="fourth" class="rec second_c" style="height: <?php echo (200 - $total_user_list) / 3 ?>px;"></div>
            </td>

            <td>
                <div id="first" class="rec third_c" style="height: <?php echo $total_user_list + 1 ?>px;"></div>
                <div id="second" class="rec third_c" style="height: <?php echo (200 - $total_user_list) / 3 ?>px;"></div>
                <div id="third" class="rec third_c" style="height: <?php echo (200 - $total_user_list) / 3 ?>px;"></div>
                <div id="fourth" class="rec third_c" style="height: <?php echo (200 - $total_user_list) / 3 ?>px;"></div>
            </td>
        </tr>
        <tr>
            <th><?php echo $user_list['1'] + $user_list['0'] ?></th><th><?php echo $user_list['1'] ?></th><th><?php echo $user_list['0'] ?></th>
        </tr>
        <tr>
            <th><div class="verticalText">Users</div></th><th><div class="verticalText">Pre-reg</div></th><th><div class="verticalText">Registered</div></th>
        </tr>
    </table>
    <table id="composition_chart_2">
        <tr>
            <td>
                <div id="first" class="rec first_c"></div>
                <div id="second" class="rec first_c"></div>
                <div id="third" class="rec first_c"></div>
            </td>
            <?php if ($totalCard == 0): ?>
                <td>
                    <div id="first" class="rec second_c" style="height: 0px;"></div>
                    <div id="second" class="rec second_c" style="height: 0px;"></div>
                    <div id="third" class="rec second_c" style="height: 0px;"></div>
                </td>
                <td>
                    <div id="first" class="rec third_c" style="height: 0px;"></div>
                    <div id="second" class="rec third_c" style="height: 0px;"></div>
                    <div id="third" class="rec third_c" style="height: 0px;"></div>
                </td>
                <td>
                    <div id="first" class="rec fourth_c" style="height: 0px;"></div>
                    <div id="second" class="rec fourth_c" style="height: 0px;"></div>
                    <div id="third" class="rec fourth_c" style="height: 0px;"></div>
                </td>
            <?php else: ?>
                <td>
                    <div id="first" class="rec second_c" style="height: <?php echo (($card_list['active'] + $card_list['complete']) * 150) / ($totalCard) ?>px;"></div>
                    <div id="second" class="rec second_c" style="height: <?php echo ($card_list['exchanged'] * 150) / ($totalCard) ?>px;"></div>
                    <div id="third" class="rec second_c" style="height: <?php echo ($card_list['redeemed'] * 150) / ($totalCard) ?>px;"></div>
                </td>

                <td>
                    <div id="first" class="rec third_c" style="height: <?php echo (($card_list['active'] + $card_list['complete']) * 150) / ($totalCard) ?>px;"></div>
                    <div id="second" class="rec third_c" style="height: <?php echo ($card_list['exchanged'] * 150) / ($totalCard) ?>px;"></div>
                    <div id="third" class="rec third_c" style="height: <?php echo ($card_list['redeemed'] * 150) / ($totalCard) ?>px;"></div>
                </td>

                <td>
                    <div id="first" class="rec fourth_c" style="height: <?php echo (($card_list['active'] + $card_list['complete']) * 150) / ($totalCard) ?>px;"></div>
                    <div id="second" class="rec fourth_c" style="height: <?php echo ($card_list['exchanged'] * 150) / ($totalCard) ?>px;"></div>
                    <div id="third" class="rec fourth_c" style="height: <?php echo ($card_list['redeemed'] * 150) / ($totalCard) ?>px;"></div>
                </td>
            <?php endif; ?>
        </tr>
        <tr>
            <th><?php echo $totalCard ?></th><th><?php echo $card_list['active'] + $card_list['complete'] ?></th><th><?php echo $card_list['exchanged'] ?></th><th><?php echo $card_list['redeemed'] ?></th>
        </tr>
        <tr>
            <th><div class="verticalText">Cards</div></th><th><div class="verticalText">In process</div></th><th><div class="verticalText">Exchanged</div></th><th><div class="verticalText">Redeemed</div></th>
        </tr>
    </table>
</div>
<table id="myTable" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
    <thead>
        <tr>
            <th class="header">Color</th>
            <th class="header">Afiliado</th>
            <th class="header">Fans</th>
            <th class="header">Tags</th>
            <th class="header">Canjes</th>
            <th class="header">Categoria</th>
            <th class="header">% Registro</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($affiliates as $key => $affiliate): ?>
            <?php if (($key % 2) != 0): ?>
                <tr class="even">
                <?php else: ?>
                <tr class="odd">
                <?php endif; ?>    
                <!-- COLOR (se que hay formas mas elegantes, pero esto pronto se ira con el nuevo modulo de admin) -->
                <?php if ($affiliate['color'] == 'Rojo'): ?><td style="background-color:red"><?php echo $affiliate['color'] ?></td><?php endif; ?>
                <?php if ($affiliate['color'] == 'Verde'): ?><td style="background-color:green"><?php echo $affiliate['color'] ?></td><?php endif; ?>
                <?php if ($affiliate['color'] == 'Amarillo'): ?><td style="background-color:yellow"><?php echo $affiliate['color'] ?></td><?php endif; ?>
                <!-- FIN COLOR -->
                <td><?php echo $affiliate['name'] ?></td>
                <td><?php echo $affiliate['subscriptions'] ?></td>
                <td><?php echo $affiliate['tickets'] ?></td>
                <td><?php echo $affiliate['redeemed'] ?></td>
                <td><?php echo $affiliate['category'] ?></td>
                <td><?php echo "<" . $affiliate['pre_registered'] . "," . $affiliate['subscriptions'] . "> ";
            if ($affiliate['subscriptions'] == 0) {
                echo 0;
            } else {
                echo number_format((($affiliate['subscriptions'] - $affiliate['pre_registered']) / $affiliate['subscriptions']) * 100, 2, '.', '');
            } ?></td>
                <td><a href="<?php echo url_for('@detailsAffiliate?affiliate_id=') . $key ?>">Detalle</a> <a href="<?php echo url_for('@analyticsAdmin?affiliate_id=') . $key ?>">Analytics</a> </td>
            </tr>
<?php endforeach; ?>
    </tbody>
</table>
<script type="text/javascript">
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var monthsShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    var days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    var daysShort = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
    var daysMin = ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

    $(document).ready(function(){        
        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            monthNames: months,
            monthNamesShort: monthsShort,
            dayNames: days,
            dayNamesShort: daysShort,
            dayNamesMin: daysMin,
            dateFormat: 'dd/mm/yy',
            yearRange: 'c-5:c'
        });

        $("#myTable").tablesorter();

        $('#sign-up-form').bValidator();

        $('.submit').click(function(event){
            event.preventDefault();

            if($('#sign-up-form').data('bValidator').validate()){
                $('#sign-up-form').submit();
            }
            else
            {
                return false;
            }
        });
    })
    
</script>