<?php
    headerPage($data);
    $tipos = $data['tipos'];
    //dep($data['tipos']);exit;
?>
<main class="container">
    
        <h1 class="section--title">Enmarcaciones modernas sin salir de casa</h1>
        <div class="row">
            <div class="product-slider-cat-2 owl-carousel owl-theme">
                <?php
                    for ($i=0; $i < count($tipos); $i++) { 
                        $url = base_url()."/enmarcar/personalizar/".$tipos[$i]['route'];
                        $img = media()."/images/uploads/".$tipos[$i]['image'];
                ?>
                    <div class="card--product">
                        <a href="<?=$url?>">
                            <img class="img-fluid" src="<?=$img?>" alt="Cuadros decorativos <?=$tipo[$i]['name']?>">
                        </a>
                        <div class="card--product-info mt-3">
                            <h2 class="enmarcar--title"><?=$tipos[$i]['name']?></h2>
                            <p><?=$tipos[$i]['description']?></p>
                        </div>
                        <?php
                            if($tipos[$i]['button']!=""){
                        ?>
                        <div class="card--product-btns">
                            <a href="<?=$url?>" class="btn btn-bg-1 w-100"><?=$tipos[$i]['button']?></a>
                        </div>
                        <?php }?>
                    </div>
                <?php }?>
            </div>
        </div>
    </main>
<?php
    footerPage($data);
?>