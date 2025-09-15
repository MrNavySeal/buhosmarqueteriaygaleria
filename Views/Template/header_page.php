<?php
    $notification = emailNotification(); 
    $comments = commentNotification();
    $reviews = $comments['total'];
    $navCategories=getNavCat();
    $subLinks = navSubLinks();
    $company = getCompanyInfo();
    $qtyCart = 0;
    $total = 0;
    $arrProducts = array();
    
    $title = $company['name'];
    $urlWeb = base_url();
    $urlImg =media()."/images/uploads/".$company['logo'];
    $description =$company['description'];
    //dep($data['article']);exit;
    if(!empty($data['product'])){
        $urlWeb = base_url()."/tienda/producto/".$data['product']['route'];
        $urlImg = $data['product']['image'][0]['url'];
        $title = $data['product']['name'];
        $description = $data['product']['shortdescription'];
    }else if(!empty($data['article'])){
        $urlWeb = base_url()."/blog/articulo/".$data['article']['route'];
        if($data['article']['picture']!=""){
            $urlImg = media()."/images/uploads/".$data['article']['picture'];
        }
        $title = $data['article']['name'];
        $description = $data['article']['shortdescription'];
    }
    if(isset($_SESSION['arrCart']) && !empty($_SESSION['arrCart'])){
        $arrProducts = $_SESSION['arrCart'];
        foreach ($arrProducts as $product) {
            $qtyCart += $product['qty'];
            $total+=$product['price']*$product['qty']; 
        }
    }
    $emails = "";
    
    if($notification>0){
        $emails = '<span class="badge badge-sm bg-danger ms-auto">'.$notification.'</span>';
    }else{
        $emails = "";
    }
    $notifyReview = "";
    if($reviews>0){
        $notifyReview='<span class="badge badge-sm bg-danger ms-auto">'.$reviews.'</span>';
    }
    //dep($arrProducts);exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1407018373490206');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1407018373490206&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?=$company['description']?>">
    <meta name="author" content="<?=$company['name']?>" />
    <meta name="copyright" content="<?=$company['name']?>"/>
    <meta name="robots" content="index,follow"/>
    <meta name="keywords" content="<?=$company['keywords']?>"/>

    <title><?=$data['page_title']?></title>
    <link rel ="shortcut icon" href="<?=media()."/images/uploads/".$company['logo']?>" sizes="114x114" type="image/png">

    <meta property="fb:app_id"          content="1234567890" /> 
    <meta property="og:locale" 		content='es_ES'/>
    <meta property="og:type"        content="article" />
    <meta property="og:site_name"	content="<?= $company['name']; ?>"/>
    <meta property="og:description" content="<?=$description?>"/>
    <meta property="og:title"       content="<?= $title; ?>" />
    <meta property="og:url"         content="<?= $urlWeb; ?>" />
    <meta property="og:image"       content="<?= $urlImg; ?>" />
    <meta name="twitter:card" content="summary"></meta>
    <meta name="twitter:site" content="<?= $urlWeb; ?>"></meta>
    <meta name="twitter:creator" content="<?= $company['name']; ?>"></meta>
    <link rel="canonical" href="<?= $urlWeb?>"/>

    <!------------------------------Frameworks--------------------------------->
    <link rel="stylesheet" href="<?=media();?>/frameworks/bootstrap/bootstrap.min.css">
    
    <!------------------------------Plugins--------------------------------->
    <link href="<?= media();?>/plugins/datepicker/jquery-ui.min.css" rel="stylesheet">
    <link href="<?=media();?>/plugins/fontawesome/font-awesome.min.css">
    <link rel="stylesheet" href="<?=media();?>/plugins/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="<?=media();?>/plugins/owlcarousel/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?=media();?>/plugins/datatables/datatables.min.css">
    <!------------------------------------Styles--------------------------->
    <link rel="stylesheet" href="<?=media()?>/template/Assets/css/normalize.css">
    <link rel="stylesheet" href="<?=media()."/template/Assets/css/style.css?v=".rand()?>">
    <script src="<?= media();?>/plugins/tinymce/tinymce.min.js"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8MPBNE6BYH"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-8MPBNE6BYH');
    </script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MRN4742X');</script>
    <!-- End Google Tag Manager -->

</head>
<body>
    
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MRN4742X"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <img src="..." class="rounded me-2" alt="..." height="20" width="20">
        <strong class="me-auto" id="toastProduct"></strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        Hello, world! This is a toast message.
        </div>
    </div>
</div>
    <div id="divLoading">
        <div></div>
        <span>Cargando...</span>
    </div>
    <header class="container">
        <div class="logo">
            <img src="<?=media()."/images/uploads/".$company['logo']?>" alt="<?=$company['name']?>">
        </div>
        <nav class="nav--bar">
            <div class="icon-mobile">
                <a href="<?=base_url()?>">
                    <img src="<?=media()."/images/uploads/".$company['logo']?>" alt="<?=$company['name']?>">
                </a>
            </div>
            <ul class="nav--links">
                <li class="nav-link"><a href="<?=base_url()?>">Inicio</a></li>
                <div class="nav-link dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Enmarcar aquí
                        <i class="fas fa-angle-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php 
                            for ($i=0; $i < count($subLinks['framing']); $i++) { 
                                $link = $subLinks['framing'][$i];
                                if($i <= 8){
                        ?>
                        <li><a class="dropdown-item" href="<?=base_url()."/enmarcar/personalizar/".$link['route']?>"><?=$link['name']?></a></li>
                        <?php } }?>
                        <hr>
                        <li><a class="dropdown-item" href="<?=base_url()?>/enmarcar">Ver todo</a></li>
                    </ul>
                </div>
                <div class="nav-link dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Tienda
                        <i class="fas fa-angle-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php 
                            for ($i=0; $i < count($subLinks['categories']); $i++) { 
                                $link = $subLinks['categories'][$i];
                                if($i <= 8){
                        ?>
                        <li><a class="dropdown-item" href="<?=base_url()."/tienda/categoria/".$link['route']?>"><?=$link['name']?></a></li>
                        <?php } }?>
                        <hr>
                        <li><a class="dropdown-item" href="<?=base_url()?>/tienda">Ver todo</a></li>
                    </ul>
                </div>
                <li class="nav-link"><a href="<?=base_url()?>/favoritos">Mis favoritos</a></li>
                <!--
                <div class="nav-link dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Servicios
                        <i class="fas fa-angle-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php 
                            for ($i=0; $i < count($subLinks['services']); $i++) { 
                                $link = $subLinks['services'][$i];
                                if($i <= 8){
                        ?>
                        <li><a class="dropdown-item" href="<?=base_url()."/servicios/servicio/".$link['route']?>"><?=$link['name']?></a></li>
                        <?php } }?>
                        <hr>
                        <li><a class="dropdown-item" href="<?=base_url()?>/servicios">Ver todo</a></li>
                    </ul>
                </div>-->
                <li class="nav-link"><a href="<?=base_url()?>/contacto">Contacto</a></li>
                <li class="nav-link"><a href="<?=base_url()?>/blog">Blog</a></li>
            </ul>
            <ul class="nav--links">
                <li class="nav--icon" id="btnSearch"><i class="fas fa-search"></i></li>
                <li class="nav--icon nav--icon-cart" id="btnCart">
                    <span id="qtyCart"><?=$qtyCart?></span>
                    <i class="fas fa-shopping-cart"></i>
                </li>
                <?php
                        if(isset($_SESSION['login'])){
                            //getPermits(2);
                    ?>
                    <div class="nav-link dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <i class="fas fa-angle-down dropdown-icon"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item " href="<?=base_url()?>/sistema/usuarios/perfil">Perfil</a></li>
                            <li id="logout"><a href="#" class="dropdown-item">Cerrar sesión</a></li>
                        </ul>
                    </div>
                    <?php }else{ ?>
                    <li onclick="openLoginModal();" title="My account" class="btn btn-bg-1" >Iniciar sesión</li> 
                <?php }?>
                <!--<li class="nav--icon" id="btnNav"><i class="fas fa-bars"></i></li>-->
            </ul>
        </nav>
    </header>
    <div class="search">
        <span id="closeSearch"><i class="fas fa-times"></i></span>
        <form action="<?=base_url()?>/tienda/buscar" method="GET">
            <input type="search" name="b" id="" placeholder="Buscar...">
            <button type="submit" class="btn"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="cartbar">
        <div class="cartbar--mask"></div>
        <div class="cartbar--elements">
            <div class="cartbar--header">
                <div class="cartbar--title">
                    Mi carrito <span id="qtyCartbar"><?=$qtyCart?></span>
                </div>
                <span id="closeCart"><i class="fas fa-times"></i></span>
            </div>
            <div class="cartbar--inner">
                <ul class="cartlist--items"></ul>
            </div>
            <div class="cartbar--info">
                <div class="info--total">
                    <span>Total</span>
                    <span id="totalCart"><?=formatNum($total)?></span>
                </div>
                <div id="btnsCartBar" class="d-none">
                    <a href="<?=base_url()?>/carrito" class="btn btn-bg-2 d-block w-100 mb-3"> Ver carrito</a>
                    <button type="button" class="btn d-block btn-bg-1 btnModalCheckout w-100" onclick="modalCheckout(this)"> Pagar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="navmobile">
        <div class="navmobile--mask"></div>
        <div class="navmobile--elements">
            <div class="navmobile--header">
                <div class="navmobile--title">
                    <p class="t-color-2 fw-bold"href="<?=base_url()?>">Buho's <span class="t-color-1">Marquetería</span> <span>&</span> <span class="t-color-1">Galería</span></p>
                </div>
                <span id="closeNav" class="t-color-2"><i class="fas fa-times"></i></span>
            </div>
            <ul class="navmobile-links d-none" id="navProfile">                      
                <?php
                    if(!isset($_SESSION['login'])){
                ?>
                <li class="navmobile-link" onclick="openLoginModal();"><a href="#">Iniciar sesión</a></li>
                <?php }?>
            </ul>
            <ul class="navmobile-links d-none" id="filterNav">
                <div class="navmobile-link accordion" id="accordionFraming">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFraming">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFraming" aria-expanded="true" aria-controls="collapseFraming">
                            <strong class="fs-5">Enmarcar aquí</strong>
                        </button>
                        </h2>
                        <div id="collapseFraming" class="accordion-collapse collapse show" aria-labelledby="headingFraming" data-bs-parent="#accordionFraming">
                            <div class="accordion-body">
                                <ul>
                                    <?php 
                                    for ($i=0; $i < count($subLinks['framing']); $i++) { 
                                        $link = $subLinks['framing'][$i];
                                        if($i <= 8){
                                    ?>
                                    <li class="navmobile-link"><a href="<?=base_url()."/enmarcar/personalizar/".$link['route']?>"><?=$link['name']?></a></li>
                                    <?php } }?>
                                    <li class="navmobile-link"><a href="<?=base_url()?>/enmarcar">Ver todo</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navmobile-link accordion" id="accordionCategory">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-categories">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseCategories" aria-expanded="false" aria-controls="flush-collapseCategories">
                            <strong class="fs-5">Tienda</strong>
                        </button>
                        </h2>
                        <div id="flush-collapseCategories" class="accordion-collapse collapse show" aria-labelledby="flush-categories" data-bs-parent="#accordionFlushCategories">
                        <div class="accordion-body">
                            <div class="accordion accordion-flush" id="accordionFlushCategorie">
                                <?php
                                    for ($i=0; $i < count($navCategories) ; $i++) { 
                                        $routeC = base_url()."/tienda/categoria/".$navCategories[$i]['route'];
                                ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-categorie<?=$i?>">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseCategorie<?=$i?>" aria-expanded="false" aria-controls="flush-collapseCategorie<?=$i?>">
                                    </button>
                                    <a href="<?=$routeC?>" class="text-decoration-none"><?=$navCategories[$i]['name']?></a>
                                    </h2>
                                    <div id="flush-collapseCategorie<?=$i?>" class="accordion-collapse collapse" aria-labelledby="flush-categorie<?=$i?>" data-bs-parent="#accordionFlushCategorie<?=$i?>">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <?php
                                                for ($j=0; $j < count($navCategories[$i]['subcategories']) ; $j++) { 
                                                    $navSubCategories = $navCategories[$i]['subcategories'][$j];
                                                    if($navSubCategories['total'] >0){
                                                        $routeS = base_url()."/tienda/categoria/".$navCategories[$i]['route']."/".$navSubCategories['route'];
                                                ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="<?=$routeS?>"><?=$navSubCategories['name']?></a>
                                                            <span class="badge bg-color-2 rounded-pill"><?=$navSubCategories['total']?></span>
                                                        </li>
                                            <?php } }?>
                                        </ul>
                                    </div>
                                    </div>
                                </div>
                                <?php }?>
                                <li class="navmobile-link"><a href="<?=base_url()?>/tienda">Ver todo</a></li>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <li class="navmobile-link"><a href="<?=base_url()?>/blog"><strong class="fs-5">Blog</strong></a></li>
            </ul>
        </div>
    </div>
    <div class="mobileOptions container">
        <ul>
            <li><a class="text-black" href="<?="https://wa.me/".$company['phonecode'].$company['phone']?>" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
            <li><a class="text-black" href="<?=base_url()?>"><i class="fas fa-home"></i></a></li>
            <li class="c-p" id="btnNav"><i class="fas fa-exchange-alt filter"></i></li>
            <?php
                if(isset($_SESSION['login'])){
            ?>
            <li><a class="text-black" href="<?=base_url()?>/favoritos"><i class="fas fa-heart"></i></a></li>
            <li><a class="text-black" href="<?=base_url()?>/sistema/usuarios/perfil"><i class="fas fa-user c-p" id="btnProfile"></i></a></li>
            <?php }else{ ?>
            <li onclick="openLoginModal();" class="c-p"><i class="fas fa-heart"></i></li>
            <li onclick="openLoginModal();" class="c-p"><i class="fas fa-user"></i></li>
            <?php }?>
        </ul>
    </div>
    <!--<a href="#" class="back--top d-none"><i class="fas fa-backward"></i></a><a id="btnWhatsapp" href="<?="https://wa.me/".$company['phonecode'].$company['phone']?>" target="_blank"><i class="fab fa-whatsapp"></i></a>-->
    <div id="modalLogin"></div>
    <?php getModal("modalPago");?>
    
    