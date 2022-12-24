<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$data['page_title']?></title>
    <!-- Font Awesome 5-->
    <link href="<?=media()?>/css/icons/font-awesome.min.css">
    <!-- AdminKit CSS file -->
    <link href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/css/style.css" rel="stylesheet">
    <!-- My Styles -->
    <link rel="stylesheet" href="<?=media()?>/css/style.css">
</head>
<body>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center" id=<?=$data['page_name']?>>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card-group d-block d-md-flex row">
                        <div class="card col-md-12 p-4 mb-0">
                            <div class="card-body" id="cardLogin">
                                <h1>Login</h1>
                                <p class="text-medium-emphasis">Login to your account</p>
                                <form id="formLogin">
                                    <div class="input-group mb-3"><span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                                        </svg></span>
                                        <input class="form-control" type="text" placeholder="Your email" id="txtEmail" name="txtEmail">
                                    </div>
                                    <div class="input-group mb-4"><span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                                        </svg></span>
                                        <input class="form-control" type="password" placeholder="Your password" id="txtPassword" name="txtPassword">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 d-flex justify-content-center">
                                            <button class="btn btn-primary px-4 w-100" type="submit" id="btnLogin">Login</button>
                                        </div>
                                        <div class="col-lg-6 col-md-12 text-end d-flex justify-content-center">
                                            <button class="btn btn-link px-0" type="button" id="btnResetPass">Forgot your password?</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body d-none" id="cardReset">
                                <h1>Forgot my password</h1>
                                <p class="text-medium-emphasis">We will send you an email with a link to reset your password</p>
                                <form id="formReset">
                                    <div class="input-group mb-3"><span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                                        </svg></span>
                                        <input class="form-control" type="text" placeholder="Your email" id="txtEmailReset" name="txtEmailReset">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 d-flex justify-content-center">
                                            <button class="btn btn-primary px-4 w-100" type="submit" id="btnReset">Reset my password</button>
                                        </div>
                                        <div class="col-lg-6 col-md-12 text-end d-flex justify-content-center">
                                            <button class="btn btn-link px-0" type="button" id="btnSession">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/prism.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/plugins/autoloader/prism-autoloader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/plugins/unescaped-markup/prism-unescaped-markup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.24.1/plugins/normalize-whitespace/prism-normalize-whitespace.js"></script>
    <!-- Essential javascripts for application to work-->
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

    <!-- My scripts -->
    <script>
        const base_url = "<?= base_url(); ?>";
        const MS = "<?=MS;?>";
        const MD = "<?=MD?>";
    </script>
    
    <script type="text/javascript" src="<?= media(); ?>/js/functions.js"></script>
    <script type="module" src="<?= media(); ?>/js/app.js"></script>
</body>
</html>