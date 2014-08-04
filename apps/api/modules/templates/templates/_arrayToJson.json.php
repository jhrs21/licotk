<?php 
    $array = $sf_data->getRaw('array');
    $is_assoc = Util::is_assoc_array($array);
    $num = count($array); 
    $i = 0;
    
    if($num == 0){
        echo '[';
    } else {
        echo $is_assoc ? '{' : '[';
    }
?>

<?php foreach ($array as $key => $value): ?>
    <?php $i++; if($is_assoc): ?>
        "<?php echo $key ?>" : 
    <?php endif; ?>
    <?php echo (is_array($value) ? include_partial('arrayToJson', array('array' => $value)): json_encode($value)) ?>
    <?php echo $num == $i ? '' : ',' ?>
<?php 
    endforeach;
    
    if($num == 0){
        echo ']';
    } else {
        echo $is_assoc ? '}' : ']';
    }
?>