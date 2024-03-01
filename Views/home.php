<?php
    //dep($data['categories']);exit;
    headerPage($data);
    $social = getSocialMedia();
    $company = getCompanyInfo();
    $links ="";
    $posts = $data['posts'];
    for ($i=0; $i < count($social) ; $i++) { 
        if($social[$i]['link']!=""){
            if($social[$i]['name']=="whatsapp"){
                $links.='<li><a href="https://wa.me/'.$social[$i]['link'].'" target="_blank"><i class="fab fa-'.$social[$i]['name'].'"></i></a></li>';
            }else{
                $links.='<li><a href="'.$social[$i]['link'].'" target="_blank"><i class="fab fa-'.$social[$i]['name'].'"></i></a></li>';
            }
        }
    }

    $tipos = $data['tipos'];
    $productos = $data['productos'];
    $banners = $data['banners'];
    $proCant = 4;
    $sliders = round(count($productos)/$proCant);
    $activeSlider = "active";
    $indexProduct=0;
    $categories = $data['categories'];
    $tipos = $data['tipos'];
?>
    <div id="modalItem"></div>
    <div id="modalPoup"></div>
    <div class="container">
        <main>
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                        for ($i=0; $i < count($banners); $i++) { 
                            $active="";
                            $a= $banners[$i]['button'] != "" ? '<button class="m-1 btn btn-bg-1">'.$banners[$i]['button'].'</button>' : "";
                            $p = $banners[$i]['description'] !="" ? '<p>'.$banners[$i]['description'].'</p>' : "";
                            if($i == 0)$active="active";
                            $img = media()."/images/uploads/".$banners[$i]['picture'];
                    ?>
                    <div class="carousel-item slider_item <?=$active?>">
                        <a href="<?=$banners[$i]['link']?>" class="slider_description">
                            <h2><?=$banners[$i]['name']?></h2>
                            <?=$p?>
                            <?=$a?>
                        </a>
                        <img src="<?=$img?>" class="d-block w-100" alt="<?=$banners[$i]['name']?>">
                    </div>
                    <?php }?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </main>
        <section class="mt-5">
            <h2 class="section--title fs-2">Enmarcaciones modernas sin salir de casa ¿Cómo funciona?</h2>
            <div class="row">
                <div class="col-md-6 mb-3 d-flex align-items-center">
                    <img src="<?=media()?>/images/uploads/cta2.jpg" class="d-block img-fluid" alt="Enmarcaciones en linea">
                </div>
                <div class="col-md-6 how-list mb-3 d-flex align-items-start flex-column">
                    <ol>
                        <li>
                            <p>Elige el marco ideal</p>
                            <p>Escoge el marco y estilo de enmarcación que más se adapte</p>
                        </li>
                        <li>
                            <p>Personaliza tu marco</p>
                            <p>Elige las molduras, colores y estilos de enmarcación</p>
                        </li>
                        <li>
                            <p>Recibelo en tu puerta</p>
                            <p>Envíos nacionales, recibelo en tu puerta o puedes recogerlo en nuestro local</p>
                        </li>
                    </ol>
                    <a href="<?=base_url()?>/enmarcar" class="btn btn-bg-1 mt-3">Empieza a enmarcar ahora</a>
                </div>
            </div>
            <!--
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card--plus shadow">
                        <i class="fas fa-certificate"></i>
                        <h3>Trabajo de calidad</h3>
                        <p>Nuestros materiales y mano de obra  te garantiza un producto de calidad que te hará volver.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card--plus shadow">
                        <i class="fas fa-receipt"></i>
                        <h3>Pagos seguros</h3>
                        <p>Todas las transacciones están seguras y protegidas a través de la pasarela de mercadopago.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card--plus shadow">
                        <i class="fas fa-dollar-sign"></i>
                        <h3>Precios claros</h3>
                        <p>El precio se basa en el tipo de enmarcación, tamaño, moldura y estilos.</p>
                    </div>
                </div>
            </div>-->
            <div class="row">
                <div class="product-slider-cat-2 owl-carousel owl-theme">
                    <?php
                        for ($i=0; $i < count($tipos); $i++) { 
                            $url = base_url()."/enmarcar/personalizar/".$tipos[$i]['route'];
                            $img = media()."/images/uploads/".$tipos[$i]['image'];
                    ?>
                        <div class="card--product">
                            <a href="<?=$url?>">
                                <img class="img-fluid" src="<?=$img?>" alt="Cuadros decorativos <?=$tipo[$i]['name']?>">
                            </a>
                            <div class="card--product-info mt-3">
                                <h2 class="enmarcar--title"><?=$tipos[$i]['name']?></h2>
                                <p><?=$tipos[$i]['description']?></p>
                            </div>
                            <?php
                                if($tipos[$i]['button']!=""){
                            ?>
                            <div class="card--product-btns">
                                <a href="<?=$url?>" class="btn btn-bg-1 w-100"><?=$tipos[$i]['button']?></a>
                            </div>
                            <?php }?>
                        </div>
                    <?php }?>
                </div>
            </div>
        </section>
        <section class="mt-5">
            <h2 class="section--title fs-2">Nuestra tienda</h2>
            <div class="row mb-3">
                <h3>Lo más reciente</h3>
                <div class="row">
                    <div class="product-slider-cat owl-carousel owl-theme mb-5" data-bs-ride="carousel">
                        <?php
                            for ($i=0; $i < count($productos); $i++) { 
                                $id = openssl_encrypt($productos[$i]['idproduct'],METHOD,KEY);
                                $discount = "";
                                
                                $reference = $productos[$i]['reference']!="" ? "REF: ".$productos[$i]['reference'] : "";
                                $variant = $productos[$i]['product_type'] == 2? "Desde " : "";
                                $price ='</span><span class="current">'.$variant.formatNum($productos[$i]['price']).'</span>';
                                $favorite="";
                                if($productos[$i]['favorite']== 0){
                                    $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav "><i class="far fa-heart "></i></button>';
                                }else{
                                    $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav active"><i class="fas fa-heart text-danger "></i></button>';
                                }
                                if($productos[$i]['discount'] > 0 && $productos[$i]['stock'] > 0){
                                    $discount = '<span class="discount">-'.$productos[$i]['discount'].'%</span>';
                                    $price ='<span class="current sale me-2">'.$variant.formatNum($productos[$i]['price']*(1-($productos[$i]['discount']*0.01)),false).'</span><span class="compare">'.$variant.formatNum($productos[$i]['price']).'</span>';
                                }else if($productos[$i]['stock'] == 0){
                                    $price = '<span class="current sale me-2">Agotado</span>';
                                }
                        ?>
                        <div class="card--product">
                            <div class="card--product-img">
                                <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>">
                                    <?=$discount?>
                                    <img src="<?=$productos[$i]['url']?>" alt="Cuadros decorativos <?=$productos[$i]['subcategory']?>">
                                </a>
                            </div>
                            <div class="card--product-info">
                                <h4><a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>"><?=$productos[$i]['name']?></a></h4>
                                <p class="text-center t-color-3 m-0 fs-6"><?=$reference?></p>
                                <div class="card--price">
                                    <?=$price?>
                                </div>
                                
                            </div>
                            <div class="card--product-btns">
                                <div class="d-flex">
                                    <?=$favorite?>
                                    <?php if($productos[$i]['product_type'] == 1 && $productos[$i]['stock'] > 0){?>
                                    <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)"><i class="fas fa-shopping-cart"></i></button>
                                    <?php }else if($productos[$i]['product_type'] == 2){?>
                                    <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>" class="btn btn-bg-1 w-100"><i class="fas fa-exchange-alt"></i></a>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
                
                for ($i=0; $i < count($categories) ; $i++) { 
            ?>
            <div class="row mb-5">
                <h3 class="mb-3">
                    <?=$categories[$i]['name']?>
                </h3>
                <div class="col-lg-4 col-md-12 ">
                    <div class="card--category d-flex align-items-center">
                        <div class="card--category-img">
                            <a href="<?=base_url()."/tienda/categoria/".$categories[$i]['route']?>">
                                <img src="<?=media()."/images/uploads/".$categories[$i]['picture']?>" alt="<?=$categories[$i]['name']?>">
                            </a>
                            <h3><a href="<?=base_url()."/tienda/categoria/".$categories[$i]['route']?>" class="text-white"><?=$categories[$i]['name']?></a></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <?php
                        $productos =$categories[$i]['products'];
                    ?>
                    <div class="row">
                        <div class="product-slider-cat-1 owl-carousel owl-theme">
                            <?php
                                for ($j=0; $j < count($productos); $j++) { 
                                    $id = openssl_encrypt($productos[$j]['idproduct'],METHOD,KEY);
                                    $discount = "";
                                    
                                    $reference = $productos[$j]['reference']!="" ? "REF: ".$productos[$j]['reference'] : "";
                                    $variant = $productos[$j]['product_type'] == 2? "Desde " : "";
                                    $price ='</span><span class="current">'.$variant.formatNum($productos[$j]['price']).'</span>';
                                    $favorite="";
                                    if($productos[$j]['favorite']== 0){
                                        $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav "><i class="far fa-heart "></i></button>';
                                    }else{
                                        $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav active"><i class="fas fa-heart text-danger "></i></button>';
                                    }
                                    if($productos[$j]['discount'] > 0 && $productos[$j]['stock'] > 0){
                                        $discount = '<span class="discount">-'.$productos[$j]['discount'].'%</span>';
                                        $price ='<span class="current sale me-2">'.$variant.formatNum($productos[$j]['price']*(1-($productos[$j]['discount']*0.01)),false).'</span><span class="compare">'.$variant.formatNum($productos[$j]['price']).'</span>';
                                    }else if($productos[$j]['stock'] == 0){
                                        $price = '<span class="current sale me-2">Agotado</span>';
                                    }
                            ?>
                            <div class="card--product">
                                <div class="card--product-img">
                                    <a href="<?=base_url()."/tienda/producto/".$productos[$j]['route']?>">
                                        <?=$discount?>
                                        <img src="<?=$productos[$j]['url']?>" alt="Cuadros decorativos <?=$productos[$j]['subcategory']?>">
                                    </a>
                                </div>
                                <div class="card--product-info">
                                    <h4><a href="<?=base_url()."/tienda/producto/".$productos[$j]['route']?>"><?=$productos[$j]['name']?></a></h4>
                                    <p class="text-center t-color-3 m-0 fs-6"><?=$reference?></p>
                                    <div class="card--price">
                                        <?=$price?>
                                    </div>
                                    
                                </div>
                                <div class="card--product-btns">
                                    <div class="d-flex">
                                        <?=$favorite?>
                                        <?php if($productos[$j]['product_type'] == 1 && $productos[$j]['stock'] > 0){?>
                                        <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)"><i class="fas fa-shopping-cart"></i></button>
                                        <?php }else if($productos[$j]['product_type'] == 2){?>
                                        <a href="<?=base_url()."/tienda/producto/".$productos[$j]['route']?>" class="btn btn-bg-1 w-100"><i class="fas fa-exchange-alt"></i></a>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
        </section>
        <?php if(!empty($posts)){?>
        <section class="mt-5">
            <h2 class="section--title">Últimos artículos publicados</h2>
            <div class="row">
                <?php for ($i=0; $i < count($posts) ; $i++) {
                    $img=media()."/images/uploads/category.jpg";
                    if($posts[$i]['picture'] !=""){
                        $img=media()."/images/uploads/".$posts[$i]['picture']; 
                    }
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card" style="width: 100%; height:100%;">
                        <img src="<?=$img?>" alt="<?=$posts[$i]['name']?>">
                        <div class="card-body">
                        <h5 class="card-title"><a class="t-color-2 link-hover-none" href="<?=base_url()."/blog/articulo/".$posts[$i]['route']?>"><?=$posts[$i]['name']?></a></h5>
                            <p class="card-text"><?=$posts[$i]['shortdescription']?></p>
                            <a href="<?=base_url()."/blog/articulo/".$posts[$i]['route']?>" class="btn btn-bg-2">Ver más</a>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </section>
        <?php }?>
    </div>
<?php
    footerPage($data);
?>
    