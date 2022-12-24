<?php
    headerPage($data);
    $services = $data['services'];
?>
<div class="container">
    <h1 class="section--title">Servicios</h1>
    <div class="row mt-3">
    <?php for ($i=0; $i < count($services) ; $i++) {?>
        <div class="col-6 col-lg-4 col-md-6 mb-3">
            <div class="card--enmarcar w-100 hover">
                <div class="card--enmarcar-img img--cover">
                    <a href="<?=base_url()."/servicios/servicio/".$services[$i]['route']?>"><img src="<?=media()."/images/uploads/".$services[$i]['picture']?>" alt="<?=$services[$i]['name']?>"></a>
                </div>
                <div class="card--enmarcar-info">
                    <a href="<?=base_url()."/servicios/servicio/".$services[$i]['route']?>">
                        <h2 class="enmarcar--title"><?=$services[$i]['name']?></h2>
                    </a>
                    <a href="<?=base_url()."/servicios/servicio/".$services[$i]['route']?>" class="btn btn-bg-1 text-white">Ver más</a>
                    
                </div>
            </div>
        </div>
        <?php }?>
    </div>
</div>
<?php footerPage($data);?>