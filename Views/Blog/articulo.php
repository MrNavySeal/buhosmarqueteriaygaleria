<?php
    headerPage($data);
    $company = getCompanyInfo();
    $articulo = $data['article'];
    $img= $articulo['picture'] != "" ? media()."/images/uploads/".$articulo['picture']:"";
    $urlShare = base_url()."/blog/articulo/".$articulo['route'];
    $posts = $data['posts'];
?>
<div class="container">

<main class="content-site mb-5">
    <h1 class="section--title"><?=$articulo['name']?></h1>
    <?php if($img!=""){?>
    <div class="w-100 mb-3">
        <img src="<?=$img?>" class="img-fluid" alt="<?=$articulo['name']?>">
    </div>
    <?php }?>
    <div class="d-flex justify-content-between mb-4">
        <div class="text-start">Publicado el <?=$articulo['date']?></div>
        <div class="text-end">Última actualización <?=$articulo['dateupdated']?></div>
    </div>
    <p class="mt-4">Compartir en:</p>
    <div class="d-flex align-items-center">
        <ul class="social social--dark mb-3">
            <li title="Compartir en facebook"><a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?=$urlShare?>&amp;t=<?=$articulo['name']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
            <li title="Compartir en twitter"><a href="#" onclick="window.open('https://twitter.com/intent/tweet?text=<?=$articulo['name']?>&amp;url=<?=$urlShare?>&amp;hashtags=<?=$company['name']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
            <li title="Compartir en linkedin"><a href="#" onclick="window.open('http://www.linkedin.com/shareArticle?url=<?=$urlShare?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a></li>
            <li title="Compartir en whatsapp"><a href="#" onclick="window.open('https://api.whatsapp.com/send?text=<?=$urlShare?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-whatsapp" aria-hidden="true"></i></a></li>
        </ul>
    </div>
    <div class="content-description"><?=$articulo['description']?></div>
    <p class="mt-4">Compartir en:</p>
    <div class="d-flex align-items-center">
        <ul class="social social--dark mb-3">
            <li title="Compartir en facebook"><a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?=$urlShare?>&amp;t=<?=$articulo['name']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
            <li title="Compartir en twitter"><a href="#" onclick="window.open('https://twitter.com/intent/tweet?text=<?=$articulo['name']?>&amp;url=<?=$urlShare?>&amp;hashtags=<?=$company['name']?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
            <li title="Compartir en linkedin"><a href="#" onclick="window.open('http://www.linkedin.com/shareArticle?url=<?=$urlShare?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a></li>
            <li title="Compartir en whatsapp"><a href="#" onclick="window.open('https://api.whatsapp.com/send?text=<?=$urlShare?>','share','toolbar=0,status=0,width=650,height=450')"><i class="fab fa-whatsapp" aria-hidden="true"></i></a></li>
        </ul>
    </div>
</main>
<?php if(!empty($posts)){?>
    <section class="mb-5">
        <h2 class="section--title">También te puede interesar</h2>
        <div class="row">
            <?php for ($i=0; $i < count($posts) ; $i++) {
                $img=media()."/images/uploads/category.jpg";
                if($posts[$i]['picture'] !=""){
                    $img=media()."/images/uploads/".$posts[$i]['picture']; 
                }
            ?>
            <div class="col-md-4 mb-3">
                <div class="card" style="width: 100%; height:100%;">
                    <img src="<?=$img?>" alt="<?=$posts[$i]['name']?>">
                    <div class="card-body">
                    <h5 class="card-title"><a class="t-color-2 link-hover-none" href="<?=base_url()."/blog/articulo/".$posts[$i]['route']?>"><?=$posts[$i]['name']?></a></h5>
                        <p class="card-text"><?=$posts[$i]['shortdescription']?></p>
                        <a href="<?=base_url()."/blog/articulo/".$posts[$i]['route']?>" class="btn btn-bg-2">Ver más</a>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
    </section>
<?php }?>
</div>
<?php
    footerPage($data);
?>