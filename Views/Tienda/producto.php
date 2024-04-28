<?php
    headerPage($data);
    $company = getCompanyInfo();
    $product = $data['product'];
    $productos = $data['products'];
    $reviews = $data['reviews'];
    $price ='<span class="current me-2">'.formatNum($product['price'],false).'</span>';
    $rate="";
    $stock ="";
    $showBtns = !$product['is_stock'] || ($product['is_stock'] && $product['stock'] > 0) ? "" : "d-none";
    $resultDiscount = floor((1-($product['discount']/$product['price']))*100);
    $discount = $product['discount'] > 0 ? '<span class="discount" id="productDiscount">-'.$product['discount'].'%</span>' : "";
    $reference = $product['reference']!="" ? "REF: ".$product['reference'] : "";

    if($product['reference'] !=""){
        $reference = '<a href="'.base_url()."/tienda/producto/".$product['route'].'" class="m-0">Referencia:<strong> '.$product['reference'].'</strong></a><br>';
    }
    if($product['is_stock']){
        $stock = 'max="'.$product['stock'].'"';
        if($product['discount'] > 0 && $product['stock'] > 0){
            $discount = '<span class="discount" id="productDiscount">-'.$resultDiscount.'%</span>';
            $price ='<span class="current sale me-2">'.formatNum($product['discount'],false).'</span><span class="compare">'.formatNum($product['price']).'</span>';
        }else if($product['stock'] == 0){
            $price = '<span class="current sale me-2">Agotado</span>';
            $discount="";
        }
    }else{
        if($product['discount']>0){
            $discount = '<span class="discount" id="productDiscount">-'.$resultDiscount.'%</span>';
            $price ='<span class="current sale me-2">'.formatNum($product['discount'],false).'</span><span class="compare">'.formatNum($product['price']).'</span>';
        }
    }
    for ($i = 0; $i < 5; $i++) {
        if($product['rate']>0 && $i >= intval($product['rate'])){
            $rate.='<i class="far fa-star"></i>';
        }else if($product['rate'] == 0){
            $rate.='<i class="far fa-star"></i>';
        }else{
            $rate.='<i class="fas fa-star"></i>';
        }
    }

    $favorite="";

    

    $id = openssl_encrypt($product['idproduct'],METHOD,KEY);
    $urlShare = base_url()."/tienda/producto/".$product['route'];

    if($product['favorite']== 0){
        $favorite = '<button type="button" onclick="addFav(this)" data-id="'.$id.'" class="mb-3 btn btn-bg-3 btn-fav "><i class="far fa-heart "></i> Agregar a favoritos </button>';
    }else{
        $favorite = '<button type="button" onclick="addFav(this)" data-id="'.$id.'" class="mb-3 btn btn-bg-3 btn-fav active"><i class="fas fa-heart text-danger "></i> Mi favorito</button>';
    }
?>
    <?=$data['modal']?>
    <div id="modalItem"></div>
    <div class="container mb-5">
        <main id="product">
            <div class=" mt-4 mb-4">
                <nav class="mt-2 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
                      <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>/tienda">Tienda</a></li>
                      <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()."/tienda/categoria/".$product['routec']?>"><?=$product['category']?></a></li>
                      <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()."/tienda/categoria/".$product['routec']."/".$product['routes']?>"><?=$product['subcategory']?></a></li>
                      <li class="breadcrumb-item active" aria-current="page"><?=$product['name']?></li>
                    </ol>
                </nav>
                <div class="row ps-2 pe-2 pb-4">
                    <div class="col-md-7 mb-3">
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
                    <div class="col-md-5 product-data">
                        <h1><a href="<?=base_url()."/tienda/producto/".$product['route']?>"><strong><?=$product['name']?></strong></a></h1>
                        <div>
                            <div class="d-inline mb-3 review-measure">
                                <?=$rate?>                              
                            </div>
                            <p class="t-color-1 ms-2 d-inline" id="avgRate">(<?=$product['reviews']?> opiniones) </p>
                        </div>
                        <p class="fs-3 mt-3"><strong class="t-p" id="productPrice"><?=$price?></strong></p>
                        <?php
                        if($product['product_type'] == 1){
                            $variants = $product['variation'];
                            $default = $product['default'];
                        ?>
                        <div class="mb-3">
                            
                            <?php
                                for ($i=0; $i < count($variants); $i++) { 
                                    $options = $variants[$i]['options'];
                                    $selected = $default[$i]; 
                            ?>
                                <p class="t-color-3 m-0"><?=$variants[$i]['name']?></p>
                                <div class="contentVariant">
                                <?php for ($j=0; $j < count($options) ; $j++) { 
                                    $active= $selected == $options[$j] ? "active" : "";
                                ?>
                                <button onclick ="selVariant(this)" data-idp="<?=$id?>" data-name="<?= $options[$j]?>" 
                                type="button" class="<?=$active?> btn btnv btn-bg-2 m-1">
                                    <?= $options[$j]?>
                                </button>
                                <?php  } ?>
                                </div>
                                <?php } ?>
                        </div>
                        <?php  }?>
                        <p class="mb-3"><?=$product['shortdescription']?></p>
                        <?=$reference?>
                        <a href="<?=base_url()."/tienda/categoria/".$product['routec']?>" class="m-0">Categoría:<strong> <?=$product['category']?></strong></a><br>
                        <a href="<?=base_url()."/tienda/categoria/".$product['routes']?>" class="m-0">Subcategoría:<strong> <?=$product['subcategory']?></strong></a>
                        
                        <div class="mt-4 mb-4 d-flex align-items-center">
                            <div class="d-flex justify-content-center align-items-center flex-wrap mt-3">
                                <div class="d-flex justify-content-center align-items-center flex-wrap <?=$showBtns?>" id="showBtns">
                                    <div class="btn-qty-1 mb-3 me-3" id="btnPqty">
                                        <button class="btn" id="btnPDecrement"><i class="fas fa-minus"></i></button>
                                        <input type="number" name="txtQty" id="txtQty" min="1" <?=$stock?> value="1">
                                        <button class="btn" id="btnPIncrement"><i class="fas fa-plus"></i></button>
                                    </div>
                                    <button type="button" class="btn btn-bg-1 me-3 mb-3" onclick="addProductCart(this)" data-id="<?=$id?>" data-topic="2" data-type="<?=$product['product_type']?>"><i class="fas fa-shopping-cart"></i> Agregar</button>
                                </div>
                                <?=$favorite?>
                            </div>
                        </div>
                        <div class="payment__methods mt-3">
                            <p>Pago seguro garantizado</p>
                            <ul>
                                <li><img src="<?=media()?>/images/uploads/icon1.png" alt=""></li>
                                <li><a  target="_blank" href="https://icons8.com"><img src="<?=media()?>/images/uploads/icon2.png" alt=""></a></li>
                                <li><a  target="_blank" href="https://icons8.com"><img src="<?=media()?>/images/uploads/icon3.png" alt=""></a></li>
                                <li><a  target="_blank" href="https://icons8.com"><img src="<?=media()?>/images/uploads/icon4.png" alt=""></a></li>
                                <li><a  target="_blank" href="https://icons8.com"><img src="<?=media()?>/images/uploads/icon5.png" alt=""></a></li>
                            </ul>
                        </div>
                        <p class="mt-4">Compartir en:</p>
                        <div class="d-flex align-items-center">
                            <ul class="social social--dark mb-3">
                                <li title="Compartir en facebook"><a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?=$urlShare?>&amp;t=<?=$product['name']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                                <li title="Compartir en twitter"><a href="#" onclick="window.open('https://twitter.com/intent/tweet?text=<?=$product['name']?>&amp;url=<?=$urlShare?>&amp;hashtags=<?=$company['name']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                                <li title="Compartir en linkedin"><a href="#" onclick="window.open('http://www.linkedin.com/shareArticle?url=<?=$urlShare?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a></li>
                                <li title="Compartir en whatsapp"><a href="#" onclick="window.open('https://api.whatsapp.com/send?text=<?=$urlShare?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-whatsapp" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <section class="mt-3">
            <ul class="nav nav-pills mb-3" id="product-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-specification-tab" data-bs-toggle="pill" data-bs-target="#pills-specification" type="button" role="tab" aria-controls="pills-specification" aria-selected="true">Especificaciones</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-description-tab" data-bs-toggle="pill" data-bs-target="#pills-description" type="button" role="tab" aria-controls="pills-description" aria-selected="false">Descripción</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-tiempo-tab" data-bs-toggle="pill" data-bs-target="#pills-tiempo" type="button" role="tab" aria-controls="pills-tiempo" aria-selected="false">Tiempo y despacho</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-review-tab" data-bs-toggle="pill" data-bs-target="#pills-review" type="button" role="tab" aria-controls="pills-review" aria-selected="false">Opiniones (<?=$product['reviews']?>)</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-specification" role="tabpanel" aria-labelledby="pills-specification-tab" tabindex="0">
                    <?php
                        $spec = "";
                        if(!empty($product['specifications'])){
                            $spec = $product['specifications'];
                    ?>
                    <table class="table table-bordered">
                        <tbody>
                                <?php
                                for ($i=0; $i < count($spec) ; $i++) {
                                ?>
                                <tr>
                                    <td class="bg-light"><?=$spec[$i]['name']?></td>
                                    <td><?=$spec[$i]['value']?></td>
                                </tr>
                                <?php  }?>
                        </tbody>
                    </table>
                    <?php  }?>
                </div>
                <div class="tab-pane fade" id="pills-description" role="tabpanel" aria-labelledby="pills-description-tab" tabindex="0">
                    <?=$product['description']?>
                </div>
                <div class="tab-pane fade" id="pills-tiempo" role="tabpanel" aria-labelledby="pills-tiempo-tab" tabindex="0">
                    Realizamos envíos con diferentes transportadoras del país, buscando siempre la mejor opción para nuestros clientes, los tiempos pueden variar de 3 días hasta 5 días hábiles según la ciudad o municipio destino, normalmente en ciudades principales las transportadoras entregan máximo en 3 días hábiles.<br><br>
                    
                    <ul>
                        <li>
                            <p>Para marcos y retablos, de acuerdo a la cantidad solicitada, se dará a conocer el tiempo estimado de producción a partir del siguiente día hábil de haber realizado y confirmado el pedido.</p>
                        </li>
                        <li>
                            <p>Para obras de arte sobre lienzo disponibles en la tienda, su envío se realizará 2 días después a partir del siguiente día hábil de haber realizado y confirmado el pedido.</p>
                        </li>
                        <li>
                            <p>Para cuadros decorativos, su proceso de fabricación será de 8 a 10 días hábiles, el envio tarda de 1-3 días habiles.</p>
                        </li>
                        <li>
                            <p>Para obras de arte personalizadas nos pondremos en contacto para organizar los requisitos de la obra y el envío.</p>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="pills-review" role="tabpanel" aria-labelledby="pills-review-tab" tabindex="0">
                    <div class="review-general">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="d-inline mb-3 review-measure">
                                    <?=$rate?>                              
                                </div>
                                <p class="fs-4 fw-bold t-color-2 ms-2 d-inline" id="avgRate"><?=$product['rate']?></p>
                                <p class="fw-bold t-color-3">(<?=$product['reviews']?>) opiniones</p>
                            </div>
                            <div class="d-flex flex-column">
                                <button type="button" id="btnReview" class="btn btn-bg-2"><i class="fas fa-edit"></i> Escriba una opinión</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php if($reviews!=""){?>
                    <div class="d-flex justify-content-end mt-3 mb-3">
                        <div class="form-label m-0 p-1">Ordenar por:</div>
                        <div class="d-flex justify-content-between">
                            <select class="form-select" aria-label="Default select example" id="sortReviews">
                                <option value="0" selected>Seleccione</option>
                                <option value="1">Lo más reciente</option>
                                <option value="2">Calificación más alta</option>
                                <option value="3">Calificación más baja</option>
                            </select>
                        </div>
                    </div>
                    <ul class="comment-list mt-3">
                        <?=$reviews?>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn text-center btn-bg-2 t-p d-none" id="showMore">Mostrar más</button>
                        </div>
                    </ul>
                    <?php }?>
                </div>
            </div>
        </section>
        <section class="mt-4">
            <h2 class="section--title">También te puede interesar</h2>
            <div class="row">
                <div class="product-slider-cat owl-carousel owl-theme">
                <?php
                    for ($i=0; $i < count($productos) ; $i++) { 
                        $id = openssl_encrypt($productos[$i]['idproduct'],METHOD,KEY);
                        $resultDiscount = "";
                        if($productos[$i]['discount'] > 0){
                            $resultDiscount = floor((1-($productos[$i]['discount']/$productos[$i]['price']))*100);
                        }
                        $discount = "";
                        $reference = $productos[$i]['reference']!="" ? "REF: ".$productos[$i]['reference'] : "";
                        $variant = $productos[$i]['product_type']? "Desde " : "";
                        $price ='</span><span class="current">'.$variant.formatNum($productos[$i]['price']).'</span>';
                        $favorite="";
                        if($productos[$i]['favorite']== 0){
                            $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav "><i class="far fa-heart "></i></button>';
                        }else{
                            $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav active"><i class="fas fa-heart text-danger "></i></button>';
                        }
                        if($productos[$i]['is_stock']){
                            if($productos[$i]['discount'] > 0 && $productos[$i]['stock'] > 0){
                                $discount = '<span class="discount">-'.$resultDiscount.'%</span>';
                                $price ='<span class="current sale me-2">'.$variant.formatNum($productos[$i]['discount'],false).'</span><span class="compare">'.formatNum($productos[$i]['price']).'</span>';
                            }else if($productos[$i]['stock'] == 0){
                                $price = '<span class="current sale me-2">Agotado</span>';
                            }
                        }else{
                            if($productos[$i]['discount']>0){
                                $discount = '<span class="discount">-'.$resultDiscount.'%</span>';
                                $price ='<span class="current sale me-2">'.$variant.formatNum($productos[$i]['discount'],false).'</span><span class="compare">'.formatNum($productos[$i]['price']).'</span>';
                            }
                        }

                ?>
                <div class="card--product">
                    <div class="card--product-img">
                        <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>">
                            <?=$discount?>
                            <img src="<?=$productos[$i]['url']?>" alt="<?=$productos[$i]['category']." ".$productos[$i]['subcategory']?>">
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
                            <?php if(!$productos[$i]['product_type'] && $productos[$i]['is_stock'] && $productos[$i]['stock'] > 0){?>
                            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)"><i class="fas fa-shopping-cart"></i></button>
                            <?php }else if(!$productos[$i]['product_type'] && !$productos[$i]['is_stock']){?>
                            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)"><i class="fas fa-shopping-cart"></i></button>
                            <?php }else if($productos[$i]['product_type']){?>
                            <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>" class="btn btn-bg-1 w-100"><i class="fas fa-exchange-alt"></i></a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>
    </div>
<?php
    footerPage($data);
?>