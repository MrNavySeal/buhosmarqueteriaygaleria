<?php
    $notification = emailNotification(); 
    $comments = commentNotification();
    $reviews = $comments['total'];
?>
<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="#" class="navbar-brand mx-4 mb-3">
            <div class="d-flex flex-column text-center align-items-center">
                <div style="max-width:30%">
                    <img class="img-fluid" src="<?=media()."/images/uploads/".$companyData['logo']?>" alt="">
                </div>
                <h5 class="text-primary text-wrap fs-5 mt-2"><?= $companyData['name']?></h5>
            </div>
            
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="<?=media()."/images/uploads/".$_SESSION['userData']['image']?>" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0"><?=$_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname']?></h6>
                <span><?=$_SESSION['userData']['role_name']?></span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <!-- Dashboard -->
            <?php if($_SESSION['permit'][1]['r']){ ?>
            <a href="<?=base_url()?>/dashboard" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <?php } ?>
            <!-- Usuarios -->
            <?php  if($_SESSION['permit'][2]['r']){?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-users"></i>Usuarios</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?=base_url()?>/usuarios" class="dropdown-item">Usuarios</a>
                    <?php
                        if($_SESSION['idUser'] == 1 && $_SESSION['permit'][2]['r']){
                    ?>
                    <a href="<?=base_url()?>/roles" class="dropdown-item">Roles de usuario</a>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
             <!-- Clientes -->
            <?php  if($_SESSION['permit'][3]['r']){ ?>
            <a href="<?=base_url()?>/clientes" class="nav-item nav-link"><i class="fas fa-user-tag"></i>Clientes</a>
            <?php } ?>
            <!-- Productos -->
            <?php  if($_SESSION['permit'][11]['r']){ ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-shopping-bag"></i>Productos</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?=base_url()?>/ProductosCategorias/categorias" class="dropdown-item">Categorias</a>
                    <a href="<?=base_url()?>/ProductosCategorias/subcategorias" class="dropdown-item">Subcategorias</a>
                    <a href="<?=base_url()?>/ProductosMasivos/productos" class="dropdown-item">Creación/Edición masiva</a>
                    <a href="<?=base_url()?>/productos/productos" class="dropdown-item">Productos</a>
                    <a href="<?=base_url()?>/ProductosOpciones/variantes" class="dropdown-item">Variantes</a>
                    <a href="<?=base_url()?>/ProductosOpciones/unidades" class="dropdown-item">Unidades de medida</a>
                    <a href="<?=base_url()?>/ProductosOpciones/caracteristicas" class="dropdown-item">Características</a>
                </div>
            </div>
            <?php } ?>
            <!-- Marquetería -->
            <?php  if($_SESSION['permit'][12]['r']){ ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-crop-alt"></i>Marquetería</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?=base_url()?>/marqueteria/colores" class="dropdown-item">Colores</a>
                    <?php  if($_SESSION['permit'][12]['w']){ ?>
                    <a href="<?=base_url()?>/marqueteria/categorias" class="dropdown-item">Categorias</a>
                    <a href="<?=base_url()?>/marqueteria/propiedades" class="dropdown-item">Propiedades</a>
                    <a href="<?=base_url()?>/MarqueteriaOpciones/opciones" class="dropdown-item">Opciones de propiedades</a>
                    <a href="<?=base_url()?>/MarqueteriaConfiguracion/configuracion" class="dropdown-item">Configurar categorías</a>
                    <?php } ?>
                    <a href="<?=base_url()?>/MarqueteriaEjemplos/ejemplos" class="dropdown-item">Ejemplos</a>
                </div>
            </div>
            <?php } ?>
            <!-- Inventario -->
            <?php if($_SESSION['permit'][4]['r']){ ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-warehouse"></i>Almacén</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="<?=base_url()?>/inventario" class="dropdown-item">Inventario</a>
                        <a href="<?=base_url()?>/inventario/ajuste" class="dropdown-item">Ajuste de inventario</a>
                        <a href="<?=base_url()?>/inventario/kardex" class="dropdown-item">Kardex</a>
                    </div>
                </div>
            <?php } ?>
            <!-- Pedidos -->
            <?php if($_SESSION['permit'][6]['r']){ ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-receipt"></i>Mis pedidos</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="<?=base_url()?>/pedidos" class="dropdown-item">Pedidos</a>
                        <a href="<?=base_url()?>/pedidos/creditos" class="dropdown-item">Pedidos a crédito</a>
                        <a href="<?=base_url()?>/pedidos/detalle" class="dropdown-item">Detalle pedidos</a>
                        <?php if($_SESSION['permit'][6]['w']){ ?>
                        <a href="<?=base_url()?>/PedidosPos/venta" class="dropdown-item">Punto de venta</a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <!-- Gestión de compras -->
            <?php if($_SESSION['permit'][8]['r']){ ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-coins"></i>Compras</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <?php  if($_SESSION['permit'][8]['w']){ ?>
                        <a href="<?=base_url()?>/compras/compra" class="dropdown-item">Nueva compra</a>
                        <?php } ?>
                        <a href="<?=base_url()?>/compras" class="dropdown-item">Compras</a>
                        <a href="<?=base_url()?>/compras/creditos" class="dropdown-item">Compras por crédito</a>
                        <a href="<?=base_url()?>/compras/detalles" class="dropdown-item">Detalle de Compras</a>
                        <a href="<?=base_url()?>/proveedores/proveedores" class="dropdown-item">Proveedores</a>
                    </div>
                </div>
            <?php } ?>
            <!-- Contabilidad -->
            <?php if($_SESSION['permit'][7]['r']){ ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-file-invoice-dollar"></i>Contabilidad</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="<?=base_url()?>/contabilidad/categorias" class="dropdown-item">Categorias</a>
                        <a href="<?=base_url()?>/contabilidad/egreso" class="dropdown-item">Egresos</a>
                        <a href="<?=base_url()?>/contabilidad/ingreso" class="dropdown-item">Ingresos</a>
                        <a href="<?=base_url()?>/contabilidad/movimientos" class="dropdown-item">Movimientos</a>
                        <a href="<?=base_url()?>/contabilidad/informe" class="dropdown-item">Informe general</a>
                    </div>
                </div>
            <?php } ?>
            <!-- Administración -->
            <?php 
                if($_SESSION['permit'][5]['r']){
                    $emails = "";
                    if($notification>0){
                        $emails = '<span class="badge badge-sm bg-danger ms-auto">'.$notification.'</span>';
                    }else{
                        $emails = "";
                    }
            ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-cog"></i>Configuración</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <?php 
                        if($_SESSION['idUser']==1){
                    ?>
                    <a href="<?=base_url()?>/empresa" class="dropdown-item">Parámetros de empresa</a>
                    <?php } ?>
                    <a href="<?=base_url()?>/administracion/correo" class="dropdown-item">Correo <?=$emails?></a>
                    <a href="<?=base_url()?>/administracion/envios" class="dropdown-item">Envío</a>
                    <a href="<?=base_url()?>/paginas" class="dropdown-item">Páginas</a>
                    <a href="<?=base_url()?>/banners" class="dropdown-item">Banners</a>
                </div>
            </div>
            <!-- Marketing -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-project-diagram"></i>Marketing</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?=base_url()?>/descuentos/cupones" class="dropdown-item">Cupones</a>
                    <a href="<?=base_url()?>/descuentos/descuentos" class="dropdown-item">Descuentos</a>
                    <a href="<?=base_url()?>/administracion/suscriptores" class="dropdown-item">Suscriptores</a>
                </div>
            </div>
            <?php } ?>
            <!-- Comentarios -->
            <?php 
                if($_SESSION['permit'][9]['r']){
            ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-comments"></i>Comentarios</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?=base_url()?>/comentarios/opiniones" class="dropdown-item">Reseñas</a>
                </div>
            </div>
            <?php } ?>
            <!-- Blog -->
            <?php 
                if($_SESSION['permit'][10]['r']){
            ?>
            <a href="<?=base_url()?>/articulos" class="nav-item nav-link"><i class="far fa-newspaper"></i>Blog</a>
            <?php } ?>
        </div>
    </nav>
</div>
<!-- Sidebar End -->