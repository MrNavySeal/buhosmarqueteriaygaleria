<?php
    headerPage($data);
    $product = $data['product'];
    $productos = $data['products'];
    //dep($product['image']);exit;
    $price ='</span><span class="current">'.formatNum($product['price']).'</span>';
    $discount ="";
    if($product['discount'] > 0){
        $discount = '<span class="discount">-'.$product['discount'].'%</span>';
        $price ='<span class="current sale me-2">'.formatNum($product['priceDiscount']).'</span><span class="compare">'.formatNum($product['price']).'</span>';
    }else if($product['stock'] == 0){
        $price = '<span class="current sale me-2">Agotado</span>';
    }

    $id = openssl_encrypt($product['idproduct'],METHOD,KEY);
    $urlShare = base_url()."/tienda/producto/".$product['route'];
    
?>
    <div id="modalItem"></div>
    <main id="product" class="container mb-5">
        <div class=" mt-4 mb-4">
            <nav class="mt-2 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>/tienda">Tienda</a></li>
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()."/tienda/categoria/".$product['routec']?>"><?=$product['category']?></a></li>
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()."/tienda/categoria/".$product['routes']?>"><?=$product['subcategory']?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?=$product['name']?></li>
                </ol>
            </nav>
            <div class="row ps-2 pe-2 pb-4">
                <div class="col-md-6 mb-3">
                    <div class="product-image">
                        <?=$discount?>
                        <img src="<?=$product['image'][0]['url']?>" class="d-block w-100" alt="<?=$product['category']." ".$product['subcategory']?>">
                    </div>
                    <div class="product-image-slider">
                        <div class="slider-btn-left"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
                        <div class="product-image-inner">
                            <?php
                                for ($i=0; $i < count($product['image']) ; $i++) { 
                                    $active="";
                                    if($i== 0){
                                        $active = "active";
                                    }
                            ?>
                            <div class="product-image-item <?=$active?>"><img src="<?=$product['image'][$i]['url']?>" alt="<?=$product['category']." ".$product['subcategory']?>"></div>
                            <?php }?>
                        </div>
                        <div class="slider-btn-right"><i class="fas fa-angle-right" aria-hidden="true"></i></div>
                    </div>
                </div>
                <div class="col-md-6 product-data">
                    <h1><a href="<?=base_url()."/tienda/producto/".$product['route']?>"><strong><?=$product['name']?></strong></a></h1>
                    <p class="text-secondary m-0">Stock: (<?=$product['stock']?>) unidades</p>
                    <p class="fs-3"><strong class="t-p"><?=$price?></strong></p>
                    <p class="mb-3"><?=$product['description']?></p>
                    <!--<p class="m-0">SKU: <strong></strong></p>-->
                    <a href="<?=base_url()."/tienda/categoria/".$product['routec']?>" class="m-0">Categoría:<strong> <?=$product['category']?></strong></a><br>
                    <a href="<?=base_url()."/tienda/categoria/".$product['routes']?>" class="m-0">Subcategoría:<strong> <?=$product['subcategory']?></strong></a>
                    <?php if($product['stock']> 0){?>
                    <div class="mt-4 mb-4 d-flex align-items-center">
                        <div class="d-flex justify-content-center align-items-center flex-wrap mt-3">
                            <div class="btn-qty-1 me-3" id="btnPqty">
                                <button class="btn" id="btnPDecrement"><i class="fas fa-minus"></i></button>
                                <input type="number" name="txtQty" id="txtQty" min="1" max ="<?=$product['stock']?>" value="1">
                                <button class="btn" id="btnPIncrement"><i class="fas fa-plus"></i></button>
                            </div>
                            <button type="button" class="btn btn-bg-1" onclick="addCart(this)" data-id="<?=$id?>" data-topic="2">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                        </div>
                    </div>
                    <?php }?>
                    <p class="mt-4">Compartir en:</p>
                    <div class="d-flex align-items-center">
                        <ul class="social social--dark mb-3">
                            <li title="Compartir en facebook"><a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?=$urlShare?>&amp;t=<?=$product['name']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                            <li title="Compartir en twitter"><a href="#" onclick="window.open('https://twitter.com/intent/tweet?text=<?=$product['name']?>&amp;url=<?=$urlShare?>&amp;hashtags=mediastore','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                            <li title="Compartir en linkedin"><a href="#" onclick="window.open('http://www.linkedin.com/shareArticle?url=<?=$urlShare?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a></li>
                            <li title="Compartir en whatsapp"><a href="#" onclick="window.open('https://api.whatsapp.com/send?text=<?=$urlShare?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-whatsapp" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <section class="mt-5">
            <h2 class="section--title">También te puede interesar</h2>
            <div class="row">
                <?php
                    for ($i=0; $i < count($productos) ; $i++) { 
                        $id = openssl_encrypt($productos[$i]['idproduct'],METHOD,KEY);
                        $discount = "";
                        $price ='</span><span class="current">'.formatNum($productos[$i]['price']).'</span>';
                        if($productos[$i]['discount'] > 0 && $productos[$i]['stock'] > 0){
                            $discount = '<span class="discount">-'.$productos[$i]['discount'].'%</span>';
                            $price ='<span class="current sale me-2">'.formatNum($productos[$i]['priceDiscount']).'</span><span class="compare">'.formatNum($productos[$i]['price']).'</span>';
                        }else if($productos[$i]['stock'] == 0){
                            $price = '<span class="current sale me-2">Agotado</span>';
                        }
                ?>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card--product">
                        <div class="card--product-img">
                            <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>">
                                <?=$discount?>
                                <img src="<?=$productos[$i]['url']?>" alt="<?=$productos[$i]['category']?> <?=$productos[$i]['subcategory']?>">
                            </a>
                        </div>
                        <div class="card--product-info">
                            <h4><a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>"><?=$productos[$i]['name']?></a></h4>
                            <div class="card--price">
                                <?=$price?>
                            </div>
                        </div>
                        <div class="card--product-btns">
                            <?php if($productos[$i]['stock'] > 0){?>
                            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                            <?php }?>
                            <button type="button" class="btn btn-bg-4" data-id="<?=$id?>" onclick="quickModal(this)">Vista rápida</button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>
    </main>
<?php
    footerPage($data);
?>