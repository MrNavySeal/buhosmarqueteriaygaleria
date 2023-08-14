<?php
    headerPage($data);
    $productos = $data['products'];
?>
    <div id="modalItem"></div>
    <div id="modalPoup"></div>
    <main class="addFilter">
        <div class="container">
            <?php if(!empty($productos)){?>
            <div class="row mt-5 mb-5">
                <h1 class="text-center">Mis favoritos</h1>
                <div class="col-lg-12 mt-5">
                    <table class="table table-borderless text-center table-cart">
                        <thead class="position-relative af-b-line">
                            <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                for ($i=0; $i < count($productos) ; $i++) { 
                                    $id = openssl_encrypt($productos[$i]['idproduct'],METHOD,KEY);
                                    $discount = "";
                                    $reference = $productos[$i]['reference']!="" ? "REF: ".$productos[$i]['reference'] : "";
                                    $variant = $productos[$i]['product_type'] == 2? "Desde " : "";
                                    $price ='</span><span class="current">'.$variant.formatNum($productos[$i]['price']).'</span>';
                                    $favorite="";
                                    $btnAdd="";
                                    $route = base_url()."/tienda/producto/".$productos[$i]['route'];
                                    if($productos[$i]['product_type'] == 1 && $productos[$i]['stock'] > 0){
                                        $btnAdd = '<button title="Agregar al carrito" type="button" class="btn btn-bg-1" data-id="'.$id.'" data-topic="2" onclick="addCart(this)"><i class="fas fa-shopping-cart"></i></button>';
                                    }elseif($productos[$i]['product_type'] == 2){
                                        $btnAdd = '<a title="Ver opciones" href="'.$route.'" class="btn btn-bg-1"><i class="fas fa-exchange-alt"></i></a>';
                                    }else{
                                        $btnAdd = '<a title="Ver producto" href="'.$route.'" class="btn btn-bg-2"><i class="fas fa-eye"></i></a>';
                                    }

                                    if($productos[$i]['discount'] > 0 && $productos[$i]['stock'] > 0){
                                        $discount = '<span class="discount">-'.$productos[$i]['discount'].'%</span>';
                                        $price ='<span class="current sale me-2">'.$variant.formatNum($productos[$i]['price']*(1-($productos[$i]['discount']*0.01)),false).'</span><span class="compare">'.$variant.formatNum($productos[$i]['price']).'</span>';
                                    }else if($productos[$i]['stock'] == 0){
                                        $price = '<span class="current sale me-2">Agotado</span>';
                                    }
                            ?>
                            <tr data-id="<?=$id?>">
                            <td>
                                <div class="position-relative">
                                    <img src="<?= $productos[$i]['url']?>"  class="p-2" height="100px" width="100px" alt="<?= $productos[$i]['name']?>">
                                    <div class="c-p position-absolute btn-del bg-color-2 rounded-circle ps-2 pe-2 top-0 start-0"><i class="fas fa-times"></i></div>
                                    <?=$discount?>
                                </div>
                            </td>
                            <td>
                                <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>"><?= $reference?></a>
                            </td>
                            <td>
                                <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>"><?= $productos[$i]['name']?></a>
                            </td>
                            <td><?=$price?></td>
                            <td>
                                <div class="wishlist-actions">
                                    <?=$btnAdd?>
                                </div>
                            </td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php }else{?>
            <div class="mt-5 mb-5 text-center">
                <h1 class="mb-3">¡Uy! No tienes productos favoritos.</h1>
                <a class="btn btn-bg-1"href="<?=base_url()?>/tienda">Comprar ahora</a>
            </div>
            <?php }?>
        </div>
    </main>
<?php
    footerPage($data);
?>