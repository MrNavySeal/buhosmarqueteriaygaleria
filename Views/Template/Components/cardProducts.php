 <?php 
    $productos = $data;
    for ($j=0; $j < count($productos); $j++) { 
        $producto = $productos[$j]; 
        echo '<div class="col-6 col-lg-3 col-md-6">';
        getComponent("cardProduct",$producto);
        echo '</div>';
    } 
?>