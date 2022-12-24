<?php
    $price ='</span><span class="current">'.formatNum($data['price']).'</span>';
    $discount ="";
    if($data['discount'] > 0 && $data['stock'] > 0){
        $discount = '<span class="discount">-'.$data['discount'].'%</span>';
        $price ='<span class="current sale me-2">'.formatNum($data['priceDiscount']).'</span><span class="compare">'.formatNum($data['price']).'</span>';
    }else if($data['stock'] == 0){
        $price = '<span class="current sale me-2">Agotado</span>';
    }
    $id = $data['idproduct'];
?>
<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn-close p-2" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="container">
            <div class="row ps-2 pe-2 pb-4">
                <div class="col-md-6 mb-3">
                    <div class="product-image">
                        <?=$discount?>
                        <img src="<?=$data['image'][0]['url']?>" class="d-block w-100" alt="<?=$data['category']." ".$data['subcategory']?>">
                    </div>
                    <div class="product-image-slider">
                        <div class="slider-btn-left"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
                        <div class="product-image-inner">
                            <?php
                                for ($i=0; $i < count($data['image']) ; $i++) { 
                                    $active="";
                                    if($i== 0){
                                        $active = "active";
                                    }
                            ?>
                            <div class="product-image-item <?=$active?>"><img src="<?=$data['image'][$i]['url']?>" alt="<?=$data['category']." ".$data['subcategory']?>"></div>
                            <?php }?>
                        </div>
                        <div class="slider-btn-right"><i class="fas fa-angle-right" aria-hidden="true"></i></div>
                    </div>
                </div>
                <div class="col-md-6 product-data">
                    <h1><a href="<?=base_url()."/tienda/producto/".$data['route']?>"><strong><?=$data['name']?></strong></a></h1>
                    <p class="text-secondary m-0">Stock: (<?=$data['stock']?>) unidades</p>
                    <p class="fs-3"><strong class="t-p"><?=$price?></strong></p>
                    <p class="mb-3"><?=$data['description']?></p>
                    <!--<p class="m-0">SKU: <strong></strong></p>-->
                    <a href="<?=base_url()."/tienda/categoria/".$data['routec']?>" class="m-0">Categoría:<strong> <?=$data['category']?></strong></a><br>
                    <a href="<?=base_url()."/tienda/categoria/".$data['routes']?>" class="m-0">Subcategoría:<strong> <?=$data['subcategory']?></strong></a>
                    <?php if($data['stock']> 0){?>
                    <div class="mt-4 mb-4 d-flex align-items-center">
                        <div class="d-flex justify-content-center align-items-center flex-wrap mt-3">
                            <div class="btn-qty-1 me-3" id="btnQqty">
                                <button class="btn" id="btnQDecrement"><i class="fas fa-minus"></i></button>
                                <input type="number" name="txtQty" id="txtQQty" min="1" max ="<?=$data['stock']?>" value="1">
                                <button class="btn" id="btnQIncrement"><i class="fas fa-plus"></i></button>
                            </div>
                            <button type="button" class="btn btn-bg-1" onclick="addCart(this)" data-id="<?=$id?>" data-topic="2">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                        </div>
                    </div>
                    <?php }?>
                    <p class="mt-4">Compartir en:</p>
                    <div class="d-flex align-items-center">
                        <ul class="social social--dark mb-3">
                            <li title="Compartir en facebook"><a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?=base_url()."/tienda/producto/".$data['route']?>&amp;t=<?=$data['name']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                            <li title="Compartir en twitter"><a href="#" onclick="window.open('https://twitter.com/intent/tweet?text=<?=$data['name']?>&amp;url=<?=base_url()."/tienda/producto/".$data['route']?>&amp;hashtags=mediastore','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                            <li title="Compartir en linkedin"><a href="#" onclick="window.open('http://www.linkedin.com/shareArticle?url=<?=base_url()."/tienda/producto/".$data['route']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a></li>
                            <li title="Compartir en whatsapp"><a href="#" onclick="window.open('https://api.whatsapp.com/send?text=<?=base_url()."/tienda/producto/".$data['route']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-whatsapp" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>