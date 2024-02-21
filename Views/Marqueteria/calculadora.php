<?php 
    headerPage($data);
    $tipos = $data['tipos'];
?>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <div class="row mt-4">
                    <?php
                        for ($i=1; $i < count($tipos); $i++) { 
                            $url = base_url()."/marqueteria/personalizar/".$tipos[$i]['route'];
                            //$img = media()."/images/uploads/".$tipos[$i]['image'];
                    ?>
                    <div class="col-lg-6">
                        <div class="card--product">
                            <a href="<?=$url?>" class="t-color-2">
                                <div class="card--product-info mt-3">
                                    <h2 class="enmarcar--title"><?=$tipos[$i]['name']?></h2>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerPage($data)?>               