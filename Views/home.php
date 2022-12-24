<?php
    headerPage($data);
    $social = getSocialMedia();
    $company = getCompanyInfo();
    $links ="";
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
?>
    <div id="modalItem"></div>
    <div id="modalPoup"></div>
    <main>
        <div id="mainSlider" class="carousel slide" data-bs-ride="carousel" data-bs-touch="false" >
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="main--show">
                        <div class="show--text">
                            <h1 class="text-title">Marquetería en línea, marcos en madera para tus cuadros</h1>
                            <a href="<?=base_url()?>/enmarcar" class="btn btn-bg-1">Empieza ahora</a>
                        </div>
                        <div class="show--img">
                            <img src="<?=media()?>/images/uploads/slider1.jpg" class="d-block w-100" alt="Enmarcar obras y fotos en línea">
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="main--show">
                        <div class="show--text">
                            <h2 class="text-title">Cuadros decorativos para sala y mucho más</h2>
                            <a href="<?=base_url()?>/tienda" class="btn btn-bg-1">Ver tienda</a>
                        </div>
                        <div class="show--img">
                            <img src="<?=media()?>/images/uploads/slider2.jpg" class="d-block w-100" alt="Cuadros decorativos para mi hogar">
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
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
            <div class="section--cta">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center mb-3">
                        <div class="cta-info">
                            <h4>Marcos para cuadros</h4>
                            <p>Elige el marco ideal para tu cuadro con nuestros mejores marcos en madera</p>
                            <a href="<?=base_url()."/enmarcar/personalizar/".$tipos[2]['route']?>" class="btn btn-bg-1 mt-3">Enmarcar ahora</a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="cta-img">
                            <img src="<?=media()?>/images/uploads/cta1.jpg" class="d-block w-100" alt="Cuadros decorativos para mi hogar">
                        </div>
                    </div>
                </div>
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
                            <p>Enviamos el pedido a tu domicilio o puedes recogerlo en nuestro local</p>
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
            <div class="section--cta">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center mb-3">
                        <div class="cta-info">
                            <h4>Cuadros decorativos modernos</h4>
                            <p>Elige nuestros cuadros abstractos, cuadros para sala, cuadros para oficina y mucho más!</p>
                            <a href="<?=base_url()?>/tienda" class="btn btn-bg-1 mt-3">Ver tienda</a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="cta-img">
                            <img src="<?=media()?>/images/uploads/cta3.jpg" class="d-block w-100" alt="Enmarcaciones en linea">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="mt-5">
            <h2 class="section--title">Algunos de nuestros productos</h2>
            <div class="row">
                <?php
                    for ($i=0; $i < count($productos) ; $i++) { 
                        $id = openssl_encrypt($productos[$i]['idproduct'],METHOD,KEY);
                        $discount = "";
                        $price ='</span><span class="current">'.formatNum($productos[$i]['price']).'</span>';
                        if($productos[$i]['discount'] > 0){
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
                                <img src="<?=$productos[$i]['url']?>" alt="Cuadros decorativos <?=$productos[$i]['subcategory']?>">
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
            <div class="text-center mt-3">
                <a href="<?=base_url()?>/tienda" class="btn btn-bg-2">Ver todo</a>
            </div>
        </section>
        <section class="mt-5">
            <div class="section--contact">
                <h2 class="section--title">¿No encuentras lo que buscas? <br> Contáctanos</h2>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <form class="form--contact" id="formContact">
                            <p>Nos encontramos en <?=$company['addressfull']?></p> 
                            <div class="form--contact-data">
                                <label>¿Cuál es tu nombre?</label>
                                <input type="text" id="txtContactName" name="txtContactName" placeholder="Nombre">
                                <span class="form-focus-effect"></span>
                            </div>
                            <div class="form--contact-data">
                                <label>¿Cuál es tu teléfono?</label>
                                <input type="text" id="txtContactPhone" name="txtContactPhone" placeholder="310 123 1234">
                                <span class="form-focus-effect"></span>
                            </div>
                            <div class="form--contact-data">
                                <label>¿Cuál es tu correo?</label>
                                <input type="text" id="txtContactEmail" name="txtContactEmail" placeholder="micorreo@ejemplo.com">
                                <span class="form-focus-effect"></span>
                            </div>
                            <div class="form--contact-data">
                                <label>Tu mensaje</label>
                                <textarea name="txtContactMessage" id="txtContactMessage" rows="3" placeholder="Escribe tu mensaje"></textarea>
                                <span class="form-focus-effect"></span>
                            </div>
                            <div class="alert alert-danger mt-3 d-none" id="alertContact" role="alert"></div>
                            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
                                <button type="submit" class="btn btn-bg-1 mb-3" id="btnMessage">Enviar mensaje</button>
                                
                                <ul class="social mb-3">
                                    <?=$links?>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 mb-3">
                        <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d497.43094086071005!2d-73.62887549945499!3d4.132008249047646!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3e2e72bdc34df1%3A0xd7ff9e6fdd7a5cbb!2sCra.%2036%20%2315a3%2C%20Villavicencio%2C%20Meta!5e0!3m2!1ses!2sco!4v1665440386579!5m2!1ses!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </section>
        <?php
            if($social[3]['link']!=""){
        ?>
        <section class="mt-5">
            <h2 class="section--title">Nuestro instagram</h2>
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6 mb-3">
                    <div class="instagram">
                        <a href="<?=$social[3]['link']?>" target="_blank">
                            <div class="instagram-img">
                                <img src="<?=media()?>/images/uploads/instagram1.png" alt="Cuadros y enmarcaciones en linea">
                                <div><i class="fab fa-instagram"></i></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6 mb-3">
                    <div class="instagram">
                        <a href="<?=$social[3]['link']?>" target="_blank">
                            <div class="instagram-img">
                                <img src="<?=media()?>/images/uploads/instagram2.png" alt="Cuadros y enmarcaciones en linea">
                                <div><i class="fab fa-instagram"></i></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6 mb-3">
                    <div class="instagram">
                        <a href="<?=$social[3]['link']?>" target="_blank">
                            <div class="instagram-img">
                                <img src="<?=media()?>/images/uploads/instagram3.png" alt="Cuadros y enmarcaciones en linea">
                                <div><i class="fab fa-instagram"></i></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6 mb-3">
                    <div class="instagram">
                        <a href="<?=$social[3]['link']?>" target="_blank">
                            <div class="instagram-img">
                                <img src="<?=media()?>/images/uploads/instagram4.png" alt="Cuadros y enmarcaciones en linea">
                                <div><i class="fab fa-instagram"></i></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php }?>
    </div>
<?php
    footerPage($data);
?>
    