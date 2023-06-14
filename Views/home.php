<?php
    //dep($data['categories']);exit;
    headerPage($data);
    $social = getSocialMedia();
    $company = getCompanyInfo();
    $links ="";

    $categories = $data['categories'];
    $categorie1 = $data['categorie1'];
    $categorie2 = $data['categorie2'];
    $categorie3 = $data['categorie3'];

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
    //dep($productos);exit;
?>
    <div id="modalItem"></div>
    <div id="modalPoup"></div>
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
    <div class="container">
        <section class="mt-5">
            <h2 class="section--title">Enmarcaciones modernas sin salir de casa</h2>
            <div id="carouselEnmarcar" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                        for ($i=0; $i < 2 ; $i++) { 
                            $active="";
                            if($i == 0)$active="active";
                        
                    ?>
                    <div class="carousel-item <?=$active?>">
                        <div class="enmarcaciones">
                            <?php
                                for ($j=0; $j < count($tipos) ; $j++) { 
                                    $url = base_url()."/enmarcar/personalizar/".$tipos[$j]['route'];
                                    $img = media()."/images/uploads/".$tipos[$j]['image'];
                                    if($i == 0 && $j == 4){
                                        break;
                                    }else if($i == 1 && $j < 4){
                                        continue;
                                    } 
                            ?>
                            <div class="card--enmarcar shadow">
                                <div class="card--enmarcar-img img--contain">
                                    <a href="<?=$url?>"><img src="<?=$img?>" alt="Enmarcar <?=$tipos[$j]['name']?>"></a>
                                </div>
                                <div class="card--enmarcar-info">
                                    <a href="<?=$url?>">
                                        <h3 class="enmarcar--title"><?=$tipos[$j]['name']?></h3>
                                        <p><?=$tipos[$j]['description']?></p>
                                    </a>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselEnmarcar" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselEnmarcar" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
            </div>
            <div class="text-center">
                <a href="<?=base_url()?>/enmarcar" class="btn btn-bg-2">Ver todo</a>
            </div>
        </section>
        <section class="mt-5">
            <h2 class="section--title">¿Cómo funciona?</h2>
            <div class="row">
                <div class="col-md-6 how-img mb-3 d-flex align-items-center">
                    <img src="<?=media()?>/images/uploads/cta2.jpg" class="d-block w-100" alt="Enmarcaciones en linea">
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
            </div>
        </section>
        <section class="mt-5">
            <h2 class="section--title">Nuestra tienda</h2>
            <div class="row mb-3">
                <?php
                    for ($i=0; $i < count($categories); $i++) { 
                ?>
                <div class="col-4 mb-3">
                    <div class="card--category">
                        <div class="card--category-img">
                            <a href="<?=base_url()."/tienda/categoria/".$categories[$i]['route']?>">
                                <img src="<?=media()."/images/uploads/".$categories[$i]['picture']?>" alt="<?=$categories[$i]['name']?>">
                            </a>
                        </div>
                        <h3><a href="<?=base_url()."/tienda/categoria/".$categories[$i]['route']?>" class="fs-5 fw-bold text-black"><?=$categories[$i]['name']?></a></h3>
                    </div>
                    
                </div>
                <?php }?>
            </div>
            <div class="row mb-3">
                <h2 class="section--title"><?=$categorie1[0]['category']?></h2>
                <?php
                    for ($i=0; $i < count($categorie1) ; $i++) { 
                        $id = openssl_encrypt($categorie1[$i]['idproduct'],METHOD,KEY);
                        $discount = "";
                        $reference = $categorie1[$i]['reference']!="" ? "REF: ".$categorie1[$i]['reference'] : "";
                        $variant = $categorie1[$i]['product_type'] == 2? "Desde " : "";
                        $price ='</span><span class="current">'.$variant.formatNum($categorie1[$i]['price']).'</span>';

                        if($categorie1[$i]['discount'] > 0){
                            $discount = '<span class="discount">-'.$categorie1[$i]['discount'].'%</span>';
                            $price ='<span class="current sale me-2">'.$variant.formatNum($categorie1[$i]['price']*(1-($categorie1[$i]['discount']*0.01)),false).'</span><span class="compare">'.$variant.formatNum($categorie1[$i]['price']).'</span>';
                        }else if($categorie1[$i]['stock'] == 0){
                            $price = '<span class="current sale me-2">Agotado</span>';
                        }

                ?>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card--product">
                        <div class="card--product-img">
                            <a href="<?=base_url()."/tienda/producto/".$categorie1[$i]['route']?>">
                                <?=$discount?>
                                <img src="<?=$categorie1[$i]['url']?>" alt="Cuadros decorativos <?=$categorie1[$i]['subcategory']?>">
                            </a>
                        </div>
                        <div class="card--product-info">
                            <h4><a href="<?=base_url()."/tienda/producto/".$categorie1[$i]['route']?>"><?=$categorie1[$i]['name']?></a></h4>
                            <p class="text-center t-color-3 m-0 fs-6"><?=$reference?></p>
                            <div class="card--price">
                                <?=$price?>
                            </div>
                            
                        </div>
                        <div class="card--product-btns">
                            <?php if($categorie1[$i]['product_type'] == 1 && $categorie1[$i]['stock'] > 0){?>
                            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                            <?php }else if($categorie1[$i]['product_type'] == 2){?>
                            <a href="<?=base_url()."/tienda/producto/".$categorie1[$i]['route']?>" class="btn btn-bg-1 w-100">Ver más</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="text-center mt-3">
                    <a href="<?=base_url()."/tienda/categoria/".$categorie1[0]['routec']?>" class="btn btn-bg-2">Ver todo</a>
                </div>
            </div>
            <div class="row mb-3">
                <h2 class="section--title"><?=$categorie2[0]['category']?></h2>
                <?php
                    for ($i=0; $i < count($categorie2) ; $i++) { 
                        $id = openssl_encrypt($categorie2[$i]['idproduct'],METHOD,KEY);
                        $discount = "";
                        $reference = $categorie2[$i]['reference']!="" ? "REF: ".$categorie2[$i]['reference'] : "";
                        $variant = $categorie2[$i]['product_type'] == 2? "Desde " : "";
                        $price ='</span><span class="current">'.$variant.formatNum($categorie2[$i]['price']).'</span>';

                        if($categorie2[$i]['discount'] > 0){
                            $discount = '<span class="discount">-'.$categorie2[$i]['discount'].'%</span>';
                            $price ='<span class="current sale me-2">'.$variant.formatNum($categorie2[$i]['price']*(1-($categorie2[$i]['discount']*0.01)),false).'</span><span class="compare">'.$variant.formatNum($categorie2[$i]['price']).'</span>';
                        }else if($categorie2[$i]['stock'] == 0){
                            $price = '<span class="current sale me-2">Agotado</span>';
                        }

                ?>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card--product">
                        <div class="card--product-img">
                            <a href="<?=base_url()."/tienda/producto/".$categorie2[$i]['route']?>">
                                <?=$discount?>
                                <img src="<?=$categorie2[$i]['url']?>" alt="Cuadros decorativos <?=$categorie2[$i]['subcategory']?>">
                            </a>
                        </div>
                        <div class="card--product-info">
                            <h4><a href="<?=base_url()."/tienda/producto/".$categorie2[$i]['route']?>"><?=$categorie2[$i]['name']?></a></h4>
                            <p class="text-center t-color-3 m-0 fs-6"><?=$reference?></p>
                            <div class="card--price">
                                <?=$price?>
                            </div>
                            
                        </div>
                        <div class="card--product-btns">
                            <?php if($categorie2[$i]['product_type'] == 1 && $categorie2[$i]['stock'] > 0){?>
                            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                            <?php }else if($categorie2[$i]['product_type'] == 2){?>
                            <a href="<?=base_url()."/tienda/producto/".$categorie2[$i]['route']?>" class="btn btn-bg-1 w-100">Ver más</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="text-center mt-3">
                    <a href="<?=base_url()."/tienda/categoria/".$categorie2[0]['routec']?>" class="btn btn-bg-2">Ver todo</a>
                </div>
            </div>
            <div class="row mb-3">
                <h2 class="section--title"><?=$categorie3[0]['category']?></h2>
                <?php
                    for ($i=0; $i < count($categorie3) ; $i++) { 
                        $id = openssl_encrypt($categorie3[$i]['idproduct'],METHOD,KEY);
                        $discount = "";
                        $reference = $categorie3[$i]['reference']!="" ? "REF: ".$categorie3[$i]['reference'] : "";
                        $variant = $categorie3[$i]['product_type'] == 2? "Desde " : "";
                        $price ='</span><span class="current">'.$variant.formatNum($categorie3[$i]['price']).'</span>';

                        if($categorie3[$i]['discount'] > 0){
                            $discount = '<span class="discount">-'.$categorie3[$i]['discount'].'%</span>';
                            $price ='<span class="current sale me-2">'.$variant.formatNum($categorie3[$i]['price']*(1-($categorie3[$i]['discount']*0.01)),false).'</span><span class="compare">'.$variant.formatNum($categorie3[$i]['price']).'</span>';
                        }else if($categorie3[$i]['stock'] == 0){
                            $price = '<span class="current sale me-2">Agotado</span>';
                        }

                ?>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card--product">
                        <div class="card--product-img">
                            <a href="<?=base_url()."/tienda/producto/".$categorie3[$i]['route']?>">
                                <?=$discount?>
                                <img src="<?=$categorie3[$i]['url']?>" alt="Cuadros decorativos <?=$categorie3[$i]['subcategory']?>">
                            </a>
                        </div>
                        <div class="card--product-info">
                            <h4><a href="<?=base_url()."/tienda/producto/".$categorie3[$i]['route']?>"><?=$categorie3[$i]['name']?></a></h4>
                            <p class="text-center t-color-3 m-0 fs-6"><?=$reference?></p>
                            <div class="card--price">
                                <?=$price?>
                            </div>
                            
                        </div>
                        <div class="card--product-btns">
                            <?php if($categorie3[$i]['product_type'] == 1 && $categorie3[$i]['stock'] > 0){?>
                            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                            <?php }else if($categorie3[$i]['product_type'] == 2){?>
                            <a href="<?=base_url()."/tienda/producto/".$categorie3[$i]['route']?>" class="btn btn-bg-1 w-100">Ver más</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="text-center mt-3">
                    <a href="<?=base_url()."/tienda/categoria/".$categorie3[0]['routec']?>" class="btn btn-bg-2">Ver todo</a>
                </div>
            </div>
            <div class="row">
                <h2 class="section--title">Lo más reciente</h2>
                <?php
                    for ($i=0; $i < count($productos) ; $i++) { 
                        $id = openssl_encrypt($productos[$i]['idproduct'],METHOD,KEY);
                        $discount = "";
                        $reference = $productos[$i]['reference']!="" ? "REF: ".$productos[$i]['reference'] : "";
                        $variant = $productos[$i]['product_type'] == 2? "Desde " : "";
                        $price ='</span><span class="current">'.$variant.formatNum($productos[$i]['price']).'</span>';

                        if($productos[$i]['discount'] > 0){
                            $discount = '<span class="discount">-'.$productos[$i]['discount'].'%</span>';
                            $price ='<span class="current sale me-2">'.$variant.formatNum($productos[$i]['price']*(1-($productos[$i]['discount']*0.01)),false).'</span><span class="compare">'.$variant.formatNum($productos[$i]['price']).'</span>';
                        }else if($productos[$i]['stock'] == 0){
                            $price = '<span class="current sale me-2">Agotado</span>';
                        }

                ?>
                <div class="col-6 col-lg-3 col-md-6">
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
                            <?php if($productos[$i]['product_type'] == 1 && $productos[$i]['stock'] > 0){?>
                            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                            <?php }else if($productos[$i]['product_type'] == 2){?>
                            <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>" class="btn btn-bg-1 w-100">Ver más</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="text-center mt-3">
                    <a href="<?=base_url()?>/tienda" class="btn btn-bg-2">Ver todo</a>
                </div>
            </div>
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
    