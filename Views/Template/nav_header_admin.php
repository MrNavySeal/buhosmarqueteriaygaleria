<!-- Navbar Start -->
<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
    <!--
    <a href="#" class="navbar-brand d-flex d-lg-none me-4">
        <h2 class="text-primary mb-0"><img height="30" width="30" src="<?=media()."/images/uploads/".$companyData['logo']?>" alt=""></h2>
    </a>-->
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>
    <div class="navbar-nav align-items-center ms-auto">
        <a href="<?=base_url()?>" class="nav-link">
            <i class="fas fa-eye"></i>
            <span class="d-none d-lg-inline-flex">Ver tienda</span>
        </a>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2" src="<?=media()."/images/uploads/".$_SESSION['userData']['image']?>" alt="" style="width: 40px; height: 40px;">
                <span class="d-none d-lg-inline-flex"><?=$_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname']?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a href="<?=base_url()?>/usuarios/perfil" class="dropdown-item">Mi perfil</a>
                <a href="<?=base_url()?>/logout" class="dropdown-item">Cerrar sesión</a>
            </div>
        </div>
    </div>
</nav>
<!-- Navbar End -->