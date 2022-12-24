<?php
    headerPage($data);
    $tipos = $data['tipos'];
    //dep($data['tipos']);exit;
?>
<main class="container">
        <h1 class="section--title">Enmarcaciones modernas sin salir de casa</h1>
        <div class="row">
            <?php
                for ($i=0; $i < count($tipos); $i++) { 
                    $url = base_url()."/enmarcar/personalizar/".$tipos[$i]['route'];
                    $img = media()."/images/uploads/".$tipos[$i]['image'];
            ?>
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="card--enmarcar w-100 hover">
                    <div class="card--enmarcar-img img--contain">
                        <a href="<?=$url?>"><img src="<?=$img?>" alt="Enmarcar <?=$tipos[$i]['name']?>"></a>
                    </div>
                    <div class="card--enmarcar-info">
                        <a href="<?=$url?>">
                            <h2 class="enmarcar--title"><?=$tipos[$i]['name']?></h2>
                            <p><?=$tipos[$i]['description']?></p>
                        </a>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
    </main>
<?php
    footerPage($data);
?>