<?php $banners = $data;?>
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
            for ($i=0; $i < count($banners); $i++) { 
                $active="";
                $a= $banners[$i]['button'] != "" ? '<button class="m-1 btn btn-bg-1">'.$banners[$i]['button'].'</button>' : "";
                $p = $banners[$i]['description'] !="" ? '<p>'.$banners[$i]['description'].'</p>' : "";
                if($i == 0)$active="active";
                $img = media()."/images/uploads/".$banners[$i]['picture'];
        ?>
        <div class="carousel-item slider_item <?=$active?>">
            <a href="<?=$banners[$i]['link']?>" class="slider_description">
                <h2><?=$banners[$i]['name']?></h2>
                <?=$p?>
                <?=$a?>
            </a>
            <img src="<?=$img?>" class="d-block w-100" alt="<?=$banners[$i]['name']?>">
        </div>
        <?php }?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>