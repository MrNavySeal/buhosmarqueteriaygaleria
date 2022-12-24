<?php
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
        $urlWeb = base_url()."/shop/product/".$data['product']['route'];
        $urlImg = $data['product']['image'][0];
        $title = $data['product']['name'];
        $description = $data['product']['shortdescription'];
    }else if(!empty($data['article'])){
        $urlWeb = base_url()."/blog/article/".$data['article']['route'];
        $urlImg = $data['article']['picture'];
        $title = $data['article']['name'];
        $description = $data['article']['description'];
    }

    if(isset($_SESSION['arrCart']) && !empty($_SESSION['arrCart'])){
        $arrProducts = $_SESSION['arrCart'];
        foreach ($arrProducts as $product) {
            $qtyCart += $product['qty'];
            $total+=$product['price']*$product['qty']; 
        }
    }
    //dep($arrProducts);exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
    <link href="<?=media();?>/plugins/fontawesome/font-awesome.min.css">
    <!------------------------------------Styles--------------------------->
    <link rel="stylesheet" href="<?=media()?>/template/Assets/css/normalize.css">
    <link rel="stylesheet" href="<?=media()."/template/Assets/css/style.css?v=".rand()?>">

</head>
<body>

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
    <header>
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
                <li class="nav-link"><a href="<?=base_url()?>/nosotros">¿Quienes somos?</a></li>
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
                </div>
                <li class="nav-link"><a href="<?=base_url()?>/contacto">Contacto</a></li>
            </ul>
            <ul class="nav--links">
                <li class="nav--icon" id="btnSearch"><i class="fas fa-search"></i></li>
                <li class="nav--icon nav--icon-cart" id="btnCart">
                    <span id="qtyCart"><?=$qtyCart?></span>
                    <i class="fas fa-shopping-cart"></i>
                </li>
                <?php
                        if(isset($_SESSION['login'])){
                    ?>
                    <div class="nav-link dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <i class="fas fa-angle-down dropdown-icon"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item " href="<?=base_url()?>/usuarios/perfil" target="__blank">Perfil</a></li>
                            <li id="logout"><a href="#" class="dropdown-item">Cerrar sesión</a></li>
                        </ul>
                    </div>
                    <?php }else{ ?>
                    <li onclick="openLoginModal();" title="My account" class="btn btn-bg-1" >Iniciar sesión</li> 
                <?php }?>
                <li class="nav--icon" id="btnNav"><i class="fas fa-bars"></i></li>
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
                    <button type="button" class="btn d-block btn-bg-1 btnCheckoutCart w-100"> Pagar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="navmobile">
        <div class="navmobile--mask"></div>
        <div class="navmobile--elements">
            <div class="navmobile--header">
                <div class="navmobile--title">
                    <a href="<?=base_url()?>">Buho's <span>Marquetería</span> <span>&</span> <span>Galería</span></a>
                </div>
                <span id="closeNav"><i class="fas fa-times"></i></span>
            </div>
            <ul class="navmobile-links">
                <li class="navmobile-link"><a href="<?=base_url()?>">Inicio</a></li>
                <div class="navmobile-link accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFraming">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFraming" aria-expanded="true" aria-controls="collapseFraming">
                            Enmarcar aquí
                        </button>
                        </h2>
                        <div id="collapseFraming" class="accordion-collapse collapse" aria-labelledby="headingFraming" data-bs-parent="#accordionExample">
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
                <div class="navmobile-link accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingCategory">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                            Tienda
                        </button>
                        </h2>
                        <div id="collapseCategory" class="accordion-collapse collapse" aria-labelledby="headingCategory" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul>
                                    <?php 
                                    for ($i=0; $i < count($subLinks['categories']); $i++) { 
                                        $link = $subLinks['categories'][$i];
                                        if($i <= 8){
                                    ?>
                                    <li class="navmobile-link"><a href="<?=base_url()."/tienda/categoria/".$link['route']?>"><?=$link['name']?></a></li>
                                    <?php } }?>
                                    <li class="navmobile-link"><a href="<?=base_url()?>/tienda">Ver todo</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <li class="navmobile-link"><a href="<?=base_url()?>/nosotros">¿Quienes somos?</a></li>
                <div class="navmobile-link accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Servicios
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul>
                                    <?php 
                                    for ($i=0; $i < count($subLinks['services']); $i++) { 
                                        $link = $subLinks['services'][$i];
                                        if($i <= 8){
                                    ?>
                                    <li class="navmobile-link"><a href="<?=base_url()."/servicios/servicio/".$link['route']?>"><?=$link['name']?></a></li>
                                    <?php } }?>
                                    <li class="navmobile-link"><a href="<?=base_url()?>/servicios">Ver todo</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <li class="navmobile-link"><a href="<?=base_url()?>/contacto">Contacto</a></li>
                
                <?php
                    if(isset($_SESSION['login'])){
                ?>
                <div class="navmobile-link accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPerfil">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePerfil" aria-expanded="true" aria-controls="collapsePerfil">
                            Mi cuenta
                        </button>
                        </h2>
                        <div id="collapsePerfil" class="accordion-collapse collapse" aria-labelledby="headingPerfil" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul>
                                    <li class="navmobile-link"><a href="<?=base_url()?>/usuarios/perfil">Perfil</a></li>
                                    <li class="navmobile-link"><a href="<?=base_url()?>/logout">Cerrar sesión</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }else{ ?>
                <li class="navmobile-link" onclick="openLoginModal();"><a href="#">Iniciar sesión</a></li>
                <?php }?>
            </ul>
        </div>
    </div>
    
    <div id="modalLogin"></div>
    <a href="#" class="back--top d-none"><i class="fas fa-backward"></i></a>
    