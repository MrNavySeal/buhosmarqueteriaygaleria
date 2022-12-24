<?php 
    //sessionCookie();
    $notification = emailNotification();
    $companyData = getCompanyInfo();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>
        <title><?=$data['page_title']." | Panel"?></title>
        <link rel ="shortcut icon" href="<?=media()."/images/uploads/".$companyData['logo']?>" sizes="114x114" type="image/png">
        
        
        <!------------------------------Admin template--------------------------------->
        <link href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/css/style.css" rel="stylesheet">
        <!------------------------------Frameworks--------------------------------->
        <!------------------------------Plugins--------------------------------->
        <link href="<?=media()?>/plugins/fontawesome/font-awesome.min.css">
        <script src="<?= media();?>/plugins/tinymce/tinymce.min.js"></script>
        <link href="<?= media();?>/plugins/datepicker/jquery-ui.min.css" rel="stylesheet">
        <!------------------------------My styles--------------------------------->
        <link rel="stylesheet" href="<?=media()?>/css/style.css">
        <link rel="stylesheet" href="<?=media()?>/css/marco.css">
    </head>
    <body>
        <?php require_once("nav_admin.php");?>
        <!--wrapper-->
        <div class="wrapper d-flex flex-column min-vh-100 bg-light">
            <header class="header header-sticky mb-4">
                <div class="container-fluid">
                    <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                        <svg class="icon icon-lg">
                            <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
                        </svg>
                    </button>
                    <a class="header-brand d-md-none" href="#">
                        <i class="" style="width: 118px;height: 46px;"></i>
                        <div class="fw-bold" width="118" height="46" alt="MediaStore Logo">
                            <img src="<?=media()."/images/uploads/".$companyData['logo']?>" alt="MediaStore Logo" width="50" height="46">
                        </div>
                    </a>
                    <?php 
                        if($_SESSION['permit'][5]['r']){
                    ?>
                    <ul class="header-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?=base_url()?>/administracion/correo">
                                <?php
                                    $notify ="";
                                    if($notification>0){
                                        $notify='<span class="position-absolute top-0 end-0 translate-middle p-1 ms-2 mt-1 bg-danger border border-light rounded-circle"><span class="visually-hidden">New alerts</span></span>';
                                    }
                                ?>
                                <svg class="icon icon-lg">
                                <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
                                </svg>
                                <?=$notify?>
                            </a>
                        </li>
                    </ul>
                    <?php }?>
                    <ul class="header-nav ms-3">
                        <li class="nav-item dropdown">
                            <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-md">
                                    <img class="avatar-img" src="<?=media()?>/images/uploads/<?=$_SESSION['userData']['image']?>" alt="<?=$_SESSION['userData']['email']?>">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end pt-0">
                                <a class="dropdown-item" href="<?=base_url()?>/usuarios/perfil">
                                    <svg class="icon me-2">
                                        <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                                    </svg> Perfil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=base_url()?>/logout">
                                    <svg class="icon me-2">
                                        <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                                    </svg> Cerrar sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="header-divider"></div>
                <?php
                    if($_SESSION['permitsModule']['w']){

                    
                ?>
                <div class="container-fluid">
                    <button class="btn btn-primary d-none" type="button" id="btnNew">Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></button>
                </div>
                <?php
                    }
                ?>
            </header>
