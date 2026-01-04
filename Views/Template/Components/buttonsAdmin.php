<?php
    $atras = $data['botones']['atras']['titulo'];
    $buscar = $data['botones']['buscar']['titulo'];
    $duplicar = $data['botones']['duplicar']['titulo'];
    $nuevo = $data['botones']['nuevo']['titulo'];
    $guardar = $data['botones']['guardar']['titulo'];
    $excel = $data['botones']['excel']['titulo'];
    $pdf = $data['botones']['pdf']['titulo'];
?>
<div class="d-flex justify-content-end flex-wrap gap-2">
    <?php if($data['botones']['atras']['mostrar']) { ?>
        <button type="button" class="btn btn-primary " <?=$data['botones']['atras']['evento']."=".'"'.$data['botones']['atras']['funcion'].'"'?>><?= $atras != "" ? $atras : "Atrás"?> <i class="fas fa-reply"></i></button>
    <?php }?>
    <?php if($data['botones']['buscar']['mostrar']) { ?>
        <button type="button" class="btn btn-primary " <?=$data['botones']['buscar']['evento']."=".'"'.$data['botones']['buscar']['funcion'].'"'?>><?= $buscar != "" ? $buscar : "Buscar"?> <i class="fas fa-search"></i></button>
    <?php }?>
    
    <?php if($data['botones']['pdf']['mostrar']) { ?>
        <button type="button" class="btn btn-primary " <?=$data['botones']['pdf']['evento']."=".'"'.$data['botones']['pdf']['funcion'].'"'?>><?= $pdf != "" ? $pdf : "PDF"?> <i class="fas fa-file-pdf"></i></button>
    <?php }?>
    <?php if($data['botones']['excel']['mostrar']) { ?>
        <button type="button" class="btn btn-primary " <?=$data['botones']['excel']['evento']."=".'"'.$data['botones']['excel']['funcion'].'"'?>><?= $excel != "" ? $excel : "Excel"?> <i class="fas fa-file-excel"></i></button>
    <?php }?>
    <?php if($data['botones']['duplicar']['mostrar']) { ?>
        <button type="button" class="btn btn-primary " <?=$data['botones']['duplicar']['evento']."=".'"'.$data['botones']['duplicar']['funcion'].'"'?>><?= $duplicar != "" ? $duplicar : "Duplicar ventana"?> <i class="fas fa-window-restore"></i></button>
    <?php }?>
    <?php if($data['botones']['nuevo']['mostrar']) { ?>
        <button type="button" class="btn btn-primary " <?=$data['botones']['nuevo']['evento']."=".'"'.$data['botones']['nuevo']['funcion'].'"'?>><?= $nuevo != "" ? $nuevo : "Nuevo"?> <i class="fas fa-plus"></i></button>
    <?php }?>
    <?php if($data['botones']['guardar']['mostrar']) { ?>
        <button type="button" class="btn btn-primary " <?=$data['botones']['guardar']['evento']."=".'"'.$data['botones']['guardar']['funcion'].'"'?>><?= $guardar != "" ? $guardar : "Guardar"?> <i class="fas fa-save"></i></button>
    <?php }?>
</div>