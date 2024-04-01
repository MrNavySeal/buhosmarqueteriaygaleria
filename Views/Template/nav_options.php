<?php
    $notification = emailNotification(); 
    $comments = commentNotification();
    $reviews = $comments['total'];
?>

    <?php if($_SESSION['permit'][1]['r']){ ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-categorie">
            <button class="btn" type="button"></button>
            <a href="<?=base_url()?>/dashboard" class="text-decoration-none t-color-2 t-color-h-2 w-100"><i class="fas fa-chart-line"></i> Dashboard </a>
        </h2>
    </div>
    <?php } ?>
        
    
    <?php  if($_SESSION['permit'][2]['r']){?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-headingTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="true" aria-controls="flush-collapseTwo">
            
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-users"></i> Usuarios</a>
        </h2>
        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo">
            <div class="accordion-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/usuarios" class="w-100">Usuarios</a>
                    </li> 
                    <?php
                        if($_SESSION['idUser'] == 1 && $_SESSION['permit'][2]['r']){
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/roles" class="w-100">Roles</a>
                    </li>   
                    <?php 
                        }
                    ?>     
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <?php  if($_SESSION['permit'][3]['r']){ ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-categorie">
            <button class="btn" type="button"></button>
            <a href="<?=base_url()?>/clientes" class="text-decoration-none t-color-2 t-color-h-2 w-100"><i class="fas fa-user"></i> Clientes </a>
        </h2>
    </div>
    <?php } ?>

    <?php  if($_SESSION['permit'][11]['r']){ ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-heading20">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse20" aria-expanded="true" aria-controls="flush-collapse5"> 
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Productos</a>
        </h2>
        <div id="flush-collapse20" class="accordion-collapse collapse" aria-labelledby="flush-heading20">
            <div class="accordion-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/ProductosCategorias/categorias" class="w-100">Categorias</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/ProductosCategorias/subcategorias" class="w-100">Subcategorias</a>
                    </li>      
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/productos/productos" class="w-100">Productos</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>//ProductosOpciones/variantes" class="w-100">Variantes de producto</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/ProductosOpciones/unidades" class="w-100">Unidades de medida</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/ProductosOpciones/caracteristicas" class="w-100">Características de producto</a>
                    </li> 
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php  if($_SESSION['permit'][11]['r']){ ?>

    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-heading4">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="true" aria-controls="flush-collapse4"> 
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-crop-alt"></i> Marqueteria</a>
        </h2>
        <div id="flush-collapse4" class="accordion-collapse collapse" aria-labelledby="flush-heading4">
            <div class="accordion-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/marqueteria/molduras" class="w-100">Molduras</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/marqueteria/materiales" class="w-100">Materiales</a>
                    </li>      
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/marqueteria/colores" class="w-100">Colores</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/marqueteria/categorias" class="w-100">Categorias</a>
                    </li>   
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/marqueteria/calculadora" class="w-100">Calculadora de costos</a>
                    </li>  
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if($_SESSION['permit'][4]['r']){ ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-heading5">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse5" aria-expanded="true" aria-controls="flush-collapse5"> 
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-archive"></i> Inventario</a>
        </h2>
        <div id="flush-collapse5" class="accordion-collapse collapse" aria-labelledby="flush-heading5">
            <div class="accordion-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/inventario" class="w-100">Inventario</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/inventario/entradas" class="w-100">Entradas</a>
                    </li>      
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/inventario/salidas" class="w-100">Salidas</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/inventario/kardex" class="w-100">Kardex</a>
                    </li> 
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <?php if($_SESSION['permit'][6]['r']){ ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-categorie">
            <button class="btn" type="button"></button>
            <a href="<?=base_url()?>/pedidos" class="text-decoration-none t-color-2 t-color-h-2 w-100"><i class="fas fa-coins"></i> Pedidos </a>
        </h2>
    </div>
    <?php } ?>

    <?php if($_SESSION['permit'][6]['w']){ ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-categorie">
            <button class="btn" type="button"></button>
            <a href="<?=base_url()?>/pedidos/pos" class="text-decoration-none t-color-2 t-color-h-2 w-100"><i class="fas fa-cash-register"></i> Punto de venta </a>
        </h2>
    </div>
    <?php } ?>

    <?php if($_SESSION['permit'][8]['r']){ ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-heading6">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse6" aria-expanded="true" aria-controls="flush-collapse6"> 
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-shopping-basket"></i> Compras</a>
        </h2>
        <div id="flush-collapse6" class="accordion-collapse collapse" aria-labelledby="flush-heading6">
            <div class="accordion-body">
                <ul class="list-group">      
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/compras/compras" class="w-100">Nueva compra</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/compras/compras" class="w-100">Compras por crédito</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/compras/compras" class="w-100">Historial de Compras</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/compras/compras" class="w-100">Detalle de Compras</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/proveedores/proveedores" class="w-100">Proveedores</a>
                    </li>  
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php  if($_SESSION['permit'][7]['r']){?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-headingC">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseC" aria-expanded="true" aria-controls="flush-collapseC">
            
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-file-invoice-dollar"></i> Contabilidad</a>
        </h2>
        <div id="flush-collapseC" class="accordion-collapse collapse" aria-labelledby="flush-headingC">
            <div class="accordion-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/contabilidad/categorias" class="w-100">Categorias</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/contabilidad/egreso" class="w-100">Cuenta egreso</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/contabilidad/ingreso" class="w-100">Cuenta ingreso</a>
                    </li>  
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/contabilidad/informe" class="w-100">Informe general</a>
                    </li>    
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <?php 
        if($_SESSION['permit'][5]['r']){
            $emails = "";
            if($notification>0){
                $emails = '<span class="badge badge-sm bg-danger ms-auto">'.$notification.'</span>';
            }else{
                $emails = "";
            }
    ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-heading7">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse7" aria-expanded="true" aria-controls="flush-collapse7"> 
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-tools"></i> Administración</a>
        </h2>
        <div id="flush-collapse7" class="accordion-collapse collapse" aria-labelledby="flush-heading7">
            <div class="accordion-body">
                <ul class="list-group">
                    <?php 
                        if($_SESSION['idUser']==1){
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/empresa" class="w-100">Parámetros de empresa</a>
                    </li>
                    <?php 
                        }
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/administracion/correo" class="w-100">Correo <?=$emails?></a>
                    </li>     
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/administracion/suscriptores" class="w-100">Suscriptores</a>
                    </li> 
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/administracion/envios" class="w-100">Envio</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/paginas" class="w-100">Paginas</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php 
        if($_SESSION['permit'][5]['r']){
    ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-heading8">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse8" aria-expanded="true" aria-controls="flush-collapse8"> 
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-dollar-sign"></i> Descuentos</a>
        </h2>
        <div id="flush-collapse8" class="accordion-collapse collapse" aria-labelledby="flush-heading8">
            <div class="accordion-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/descuentos/cupones" class="w-100">Cupones</a>
                    </li>     
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/descuentos/descuentos" class="w-100">Descuentos</a>
                    </li> 
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php 
        if($_SESSION['permit'][5]['r']){
    ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-categorie">
            <button class="btn" type="button"></button>
            <a href="<?=base_url()?>/banners" class="text-decoration-none t-color-2 t-color-h-2 w-100"><i class="fas fa-tags"></i> Banners </a>
        </h2>
    </div>
    <?php } ?>
    <?php 
        if($_SESSION['permit'][9]['r']){
    ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <?php 
            $notifyReview = "";
            if($reviews>0){
                $notifyReview='<span class="badge badge-sm bg-danger ms-auto">'.$reviews.'</span>';
            }
            ?>
            
        <h2 class="accordion-header" id="flush-heading9">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse9" aria-expanded="true" aria-controls="flush-collapse9"> 
        </button>
        <a href="#" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-comments"></i> Comentarios <?=$notifyReview?></a>
        </h2>
        <div id="flush-collapse9" class="accordion-collapse collapse" aria-labelledby="flush-heading9">
            <div class="accordion-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?=base_url()?>/comentarios/opiniones" class="w-100">Opiniones <?=$notifyReview?></a>
                    </li>     
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php 
        if($_SESSION['permit'][10]['r']){
    ?>
    <div class="accordion-item pt-1 pb-1 mt-2">
        <h2 class="accordion-header" id="flush-categorie">
            <button class="btn" type="button"></button>
            <a href="<?=base_url()?>/articulos" class="text-decoration-none t-color-2 t-color-h-2 w-100"><i class="far fa-newspaper"></i> Blog</a>
        </h2>
    </div>
    <?php } ?>
    <div class="accordion-item mt-3">
        <h2 class="accordion-header" id="flush-categorie">
            <button class="btn" type="button"></button>
            <a href="<?=base_url()?>/usuarios/perfil" class="text-decoration-none t-color-2 t-color-h-2 w-100"><i class="fas fa-id-card-alt"></i> Perfil </a>
        </h2>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-categorie">
            <button class="btn" type="button"></button>
            <a href="<?=base_url()?>/logout" class="text-decoration-none t-color-2 t-color-h-2 w-100"><i class="fas fa-sign-out-alt"></i> Cerrar sesión </a>
        </h2>
    </div>