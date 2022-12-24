<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title><?=$data['page_title']?></title>
        <script src="<?= media();?>/js/plugins/tinymce/tinymce.min.js"></script>
        <!-- Font Awesome 5-->
        <link href="<?=media()?>/css/icons/font-awesome.min.css">
        <!-- AdminKit CSS file -->
        <link href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/css/style.css" rel="stylesheet">
        <!-- My Styles -->
        <link rel="stylesheet" href="<?=media()?>/css/style.css">
</head>
<body>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="clearfix">
                        <h1 class="float-start display-3 me-4">404</h1>
                        <h4 class="pt-3">¡Uy! Te has perdido.</h4>
                        <p class="text-medium-emphasis">No se ha encontrado la página que busca.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= media(); ?>/js/bootstrap/popper.min.js?n=1"></script>
    <script src="<?= media(); ?>/js/bootstrap/bootstrap.min.js?n=1"></script>
    <script src="<?= media();?>/js/icons/fontawesome.js"></script>
    <script src="<?= media();?>/js/plugins/sweetalert.js"></script>
    
    <!-- AdminKit JS file -->
    <script src="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/simplebar/js/simplebar.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/prism.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/plugins/autoloader/prism-autoloader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/plugins/unescaped-markup/prism-unescaped-markup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/plugins/normalize-whitespace/prism-normalize-whitespace.js"></script>

    <script src="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/chart.js/js/chart.min.js"></script>
    <script src="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
    <script src="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/utils/js/coreui-utils.js"></script>
    <script src="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/js/main.js"></script>
</body>
</html>
