<?php
    headerPage($data);
    $categories = $data['categories'];
    $categoryRoute = $data['category_route'];
    $subcategoryRoute = $data['subcategory_route'];

    $categoryName = ucwords(str_replace("-"," ",$categoryRoute));
    $subcategoryName = ucwords(str_replace("-"," ",$subcategoryRoute));
?>
    <div id="modalItem"></div>
    <div id="modalPoup"></div>
    <input type="hidden" name="" id="routeCategory" value="<?= $categoryRoute ?>">
    <input type="hidden" name="" id="routeSubcategory" value="<?= $subcategoryRoute ?>">
    <input type="hidden" name="" id="productSearch" value="">
    <main class="addFilter">
        <div class="m-3">
            <nav class="mt-2 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>/tienda">Tienda</a></li>
                  <?php
                    if($subcategoryRoute!=""){
                  ?>
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()."/tienda/categoria/".$categoryRoute?>"><?=$categoryName?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?=$subcategoryName?></li>
                  <?php }else{?>
                    <li class="breadcrumb-item active" aria-current="page"><?=$categoryName?></li>
                  <?php }?>
                </ol>
            </nav>
            <div class="row">
                <div class="col-3 col-lg-3 col-md-12">
                    <?php getComponent("storeTopicAside",$categories) ?>
                </div>
                <div class="col-12 col-lg-9 col-md-12">
                    <?php  getComponent("storeFilter") ?>
                    <div class="row mt-5" id="productItems"></div>
                    <div class="pagination"></div>
                </div>
            </div>
        </div>
    </main>
<?php
    footerPage($data);
?>