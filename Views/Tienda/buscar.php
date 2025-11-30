<?php
    headerPage($data);
    $categories = $data['categories'];
    $search = $data['search'];
?>
    <div id="modalItem"></div>
    <div id="modalPoup"></div>
    <input type="hidden" name="" id="routeCategory" value="">
    <input type="hidden" name="" id="routeSubcategory" value="">
    <input type="hidden" name="" id="productSearch" value="<?= $search ?>">
    <main class="addFilter">
        <div class="container mt-5 mb-3">
            <nav class="mt-2 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>/tienda">Tienda</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Buscar</li>
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