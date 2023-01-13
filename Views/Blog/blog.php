<?php
    headerPage($data);
    $posts = $data['posts'];
    //dep($data['tipos']);exit;
?>
<main class="container">
        <?php if(!empty($posts)){?>
        <h1 class="section--title">El Blog</h1>
        <p class="text-center fs-4 mb-5 text-secondary">Navega por las publicaciones más recientes</p>
        <div class="row">
            <?php for ($i=0; $i < count($posts) ; $i++) {
                $img=media()."/images/uploads/category.jpg";
                if($posts[$i]['picture'] !=""){
                    $img=media()."/images/uploads/".$posts[$i]['picture']; 
                }
            ?>
            <div class="col-md-4 mb-3">
                <div class="card " style="width: 100%; height:100%;">
                    <img src="<?=$img?>" class="card-img-top" alt="<?=$posts[$i]['name']?>">
                    <div class="card-body">
                        <h5 class="card-title"><a class="t-color-2 link-hover-none" href="<?=base_url()."/blog/articulo/".$posts[$i]['route']?>"><?=$posts[$i]['name']?></a></h5>
                        <p class="card-text"><?=$posts[$i]['shortdescription']?></p>
                        <a href="<?=base_url()."/blog/articulo/".$posts[$i]['route']?>" class="btn btn-bg-2">Ver más</a>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
        <?php }else{?>
            <h1 class="section--title">Oops, aún no hemos publicado articulos :(</h1>
        <?php }?>
    </main>
<?php
    footerPage($data);
?>