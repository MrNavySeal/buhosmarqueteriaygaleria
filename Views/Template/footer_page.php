<?php 
$discount = statusCoupon();
$company = getCompanyInfo();
$social = getSocialMedia();

$links ="";
for ($i=0; $i < count($social) ; $i++) { 
    if($social[$i]['link']!=""){
        if($social[$i]['name']=="whatsapp"){
            $links.='<li><a href="https://wa.me/'.$social[$i]['link'].'" target="_blank"><i class="fab fa-'.$social[$i]['name'].'"></i></a></li>';
        }else{
            $links.='<li><a href="'.$social[$i]['link'].'" target="_blank"><i class="fab fa-'.$social[$i]['name'].'"></i></a></li>';
        }
    }
}

?>

<footer>
    <div class="row m-0 mt-3">
        <div class="col-lg-4 p-5">
            <div class="logo">
                <img src="<?=media()."/images/uploads/".$company['logo']?>" alt="<?=$company['name']?>">
            </div>
            <p><?=$company['description']?></p>
            <p class="fw-bold fs-4">Síguenos</p>
            <ul class="social social--dark">
                <?=$links?>
            </ul>
        </div>
        <div class="col-lg-8 p-0">
            <div class="footer--info">
                <div class="row mb-5">
                    <div class="col-md-4">
                        <div class="footer--contact">
                            <i class="fas fa-map-marker-alt"></i>
                            <p><?=$company['addressfull']?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="footer--contact">
                            <i class="fas fa-phone"></i>
                            <p><?=$company['phonecode']." ".$company['phone']?><br> Llámanos</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="footer--contact">
                            <i class="fas fa-envelope"></i>
                            <p><?=$company['email']?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="footer--data">
                            <div class="footer--title">
                                <h3>Información
                                    <span class="title--decoration">
                                        <span></span><span></span><span></span><span></span><span></span>
                                    </span>
                                </h3>
                            </div>
                            <ul>
                                <li><a href="<?=base_url()?>/enmarcar">Enmarcar aquí</a></li>
                                <li><a href="<?=base_url()?>/tienda">Tienda</a></li>
                                <li><a href="<?=base_url()?>/nosotros">¿Quienes somos?</a></li>
                                <li><a href="<?=base_url()?>/servicios">Servicios</a></li>
                                <li><a href="<?=base_url()?>/contacto">Contacto</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="footer--data">
                            <div class="footer--title">
                                <h3>Métodos de pago
                                    <span class="title--decoration">
                                        <span></span><span></span><span></span><span></span><span></span>
                                    </span>
                                </h3>
                            </div>
                            <ul>
                                <li>Todas las tarjetas débito y crédito</li>
                                <li>Transferencia bancaria</li>
                                <li>Pago en efectivo</li>
                                <li>Mercadopago</li>
                            </ul>
                        </div>
                    </div>
                    <?php if(!empty($discount)){ ?>
                    <div class="col-md-5">
                        <div class="footer--data">
                            <div class="footer--title">
                                <h3>Suscríbete
                                    <span class="title--decoration">
                                        <span></span><span></span><span></span><span></span><span></span>
                                    </span>
                                </h3>
                            </div>
                            <p>Suscríbete a nuestro boletín y recibe un cupón de descuento de <?=$discount['discount']?>% <br><br>Reciba información actualizada sobre novedades, ofertas especiales y nuestras promociones</p>
                            <div class="alert alert-danger d-none" id="alertSuscribe" role="alert"></div>
                            <form action="" class="footer--subscribe" id="formSuscriber">
                                <input type="email" id="txtEmailSuscribe" name="txtEmailSuscribe" placeholder="Tu correo">
                                <button type="submit" class="btn" id="btnSuscribe"><i class="fas fa-paper-plane"></i></button>
                            </form>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-0">
        <div class="col-md-12 p-0">
            <div class="footer--bar">
                <p>Copyright © 2022 <?=$company['name']?></p>
                <ul>
                    <li><a href="<?=base_url()?>">Inicio</a></li>
                    <li><a href="<?=base_url()?>/politicas/terminos">Términos y condiciones</a></li>
                    <li><a href="<?=base_url()?>/politicas/privacidad">Políticas de privacidad</a></li>
                    <li><a href="<?=base_url()?>/contacto">Contacto</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
    
    <!------------------------------Frameworks--------------------------------->
    <script src="<?= media(); ?>/frameworks/bootstrap/popper.min.js?n=1"></script>
    <script src="<?= media(); ?>/frameworks/bootstrap/bootstrap.min.js?n=1"></script>
    <!------------------------------Plugins--------------------------------->
    <script src="<?= media();?>/plugins/fontawesome/fontawesome.js"></script>
    <script src="<?= media();?>/plugins/sweetalert/sweetalert.js"></script>
    <!------------------------------My functions--------------------------------->
    <script>
        const base_url = "<?= base_url(); ?>";
        const MS = "<?=$company['currency']['symbol'];?>";
        const MD = "<?=$company['currency']['code']?>";
        const COMPANY = "<?=$company['name']?>";
        const SHAREDHASH ="<?=strtolower(str_replace(" ","",$company['name']))?>";
    </script>
    
    <script src="<?=media();?>/js/functions.js"></script>
    <script src="<?=media();?>/template/Assets/js/functions_general.js?v=<?=rand()?>"></script>
    <?php if(isset($data['app'])){?>
    <script src="<?=media();?>/template/Assets/js/<?=$data['app']."?v=".rand()?>"></script>
    <?php }?>
    
    
</body>
</html>