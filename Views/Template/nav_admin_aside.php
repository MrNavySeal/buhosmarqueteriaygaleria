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
            <?php if($_SESSION['idUser'] == 1){ ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-compass"></i>Módulos</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?=base_url()?>/modulos/" class="dropdown-item">Módulos</a>
                    <a href="<?=base_url()?>/modulos/secciones/" class="dropdown-item">Secciones</a>
                    <a href="<?=base_url()?>/modulos/opciones/" class="dropdown-item">Opciones</a>
                </div>
            </div>
            <?php } ?>
            <?php
                foreach ($_SESSION['navegation'] as $modulo) {
                    if($_SESSION['idUser'] == 1 || $modulo['r']){
                        $html ='<div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">'.$modulo['icon']." ".$modulo['name'].'</a>
                        <div class="dropdown-menu bg-transparent border-0">';
                        foreach ($modulo['sections'] as $section) {
                            if($_SESSION['idUser'] == 1 || $section['r']){
                                $html.='<div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-compass"></i>'.$section['name'].'</a>
                                <div class="dropdown-menu bg-transparent border-0">';
                                foreach ($section['options'] as $option) {
                                    if($_SESSION['idUser'] == 1 || $option['r']){
                                        $route = base_url()."/".$option['route'];
                                        $html.='<a href="'.$route.'" class="dropdown-item">'.$option['name'].'</a>';
                                    }
                                }
                                $html.='</div></div>';
                            }
                        }
                        foreach ($modulo['options'] as $option) {
                            if($_SESSION['idUser'] == 1 || $option['r']){
                                $route = base_url()."/".$option['route'];
                                $html.='<a href="'.$route.'" class="dropdown-item">'.$option['name'].'</a>';
                            }
                        }
                        $html.='</div></div>';
                    }
                    echo $html;
                }
            ?>
            
            <!-- 
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-user-cog"></i>Sistema</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="<?=base_url()?>/sistema/roles/" class="dropdown-item">Roles</a>
                    <a href="<?=base_url()?>/sistema/usuarios/" class="dropdown-item">Usuarios</a>
                </div>
            </div> -->
        </div>
    </nav>
</div>
<!-- Sidebar End -->