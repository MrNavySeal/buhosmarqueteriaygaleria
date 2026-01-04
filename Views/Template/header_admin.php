<?php 
    //sessionCookie();
    $notification = emailNotification();
    $companyData = getCompanyInfo();
    $comments = commentNotification();
    $reviews = $comments['total'];
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
        <title><?=$data['page_title'].""?></title>
        <link rel ="shortcut icon" href="<?=media()."/images/uploads/".$companyData['logo']?>" sizes="114x114" type="image/png">
        
        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="<?=media()?>/DashboardTemplate/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="<?=media()?>/DashboardTemplate/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

        <!-- Customized Bootstrap Stylesheet -->
        <link href="<?=media()?>/DashboardTemplate/css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="<?=media()."/DashboardTemplate/css/style.css?v=".rand()?>" rel="stylesheet">
        
        <!------------------------------Frameworks--------------------------------->
        <link rel="stylesheet" href="<?=media();?>/frameworks/bootstrap/bootstrap.min.css">
        <!------------------------------Plugins--------------------------------->

        <script src="<?= media();?>/plugins/tinymce/tinymce.min.js"></script>
        <link href="<?= media();?>/plugins/datepicker/jquery-ui.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?=media();?>/plugins/datatables/datatables.min.css">
        <!------------------------------My styles--------------------------------->
        <link rel="stylesheet" href="<?=media()."/css/style.css?v=".rand()?>">
        <link rel="stylesheet" href="<?=media()."/css/marco.css?v=".rand()?>">
    </head>
    <body>
        <div class="position-relative bg-white d-flex p-0" id="app">
             <!-- Spinner Start -->
            <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <?php require('Views/Template/nav_admin_aside.php');?>
            <!-- Content Start -->
            <div class="content">
            <?php require('Views/Template/nav_header_admin.php');?>
            <div class="p-2">
                <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                    <div class="d-flex w-100 mb-2 justify-content-between align-items-center">
                        <h2 class="text-center"><?=$data['page_title']?></h2>
                        <?php getComponent("buttonsAdmin",$data);?>
                    </div>