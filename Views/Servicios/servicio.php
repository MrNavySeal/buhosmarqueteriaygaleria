<?php
    headerPage($data);
    $social = getSocialMedia();
    $service = $data['service'];
?>
<main>
    <div class="service">
        <img src="<?=media()."/images/uploads/".$service['picture']?>" alt="<?=$service['name']?>">
        <h1 class="service--title"><?=$service['name']?></h1>
    </div>
    <?=$service['description']?>
    <?php
        if($social[3]['link']!=""){
    ?>
    <section class="mt-5 container">    
        <h2 class="section--title">Nuestro instagram</h2>
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6 mb-3">
                <div class="instagram">
                    <a href="<?=$social[3]['link']?>" target="_blank">
                        <div class="instagram-img">
                            <img src="<?=media()?>/images/uploads/instagram1.png" alt="Cuadros y enmarcaciones en linea">
                            <div><i class="fab fa-instagram"></i></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6 mb-3">
                <div class="instagram">
                    <a href="<?=$social[3]['link']?>" target="_blank">
                        <div class="instagram-img">
                            <img src="<?=media()?>/images/uploads/instagram2.png" alt="Cuadros y enmarcaciones en linea">
                            <div><i class="fab fa-instagram"></i></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6 mb-3">
                <div class="instagram">
                    <a href="<?=$social[3]['link']?>" target="_blank">
                        <div class="instagram-img">
                            <img src="<?=media()?>/images/uploads/instagram3.png" alt="Cuadros y enmarcaciones en linea">
                            <div><i class="fab fa-instagram"></i></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6 mb-3">
                <div class="instagram">
                    <a href="<?=$social[3]['link']?>" target="_blank">
                        <div class="instagram-img">
                            <img src="<?=media()?>/images/uploads/instagram4.png" alt="Cuadros y enmarcaciones en linea">
                            <div><i class="fab fa-instagram"></i></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php }?>
</div>
<?php footerPage($data);?>