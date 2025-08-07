<?php
    headerPage($data);
    $categories = $data['categories'];
    $productos = $data['products']['productos'];
    $paginas = $data['products']['paginas'];

    $current = isset($_GET['p']) ? intval(strClean($_GET['p'])) : 1 ;
    $urlSort =isset($_GET['s']) ?  "&s=".intval(strClean($_GET['s'])) : "";
    $nextPage = $current+1;
    $prevPage = $current-1;

    if($current >= $paginas){
        $nextPage = $paginas;
    }
    if($prevPage <= 0){
        $prevPage = 1;
    }
?>
    <div id="modalItem"></div>
    <div id="modalPoup"></div>
    <main class="addFilter">
        <div class="container mt-5 mb-3">
            <nav class="mt-2 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tienda</li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-3 col-lg-3 col-md-12">
                    <aside class="p-2 filter-options">
                        <div class="accordion accordion-flush" id="accordionFlushCategories">
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="flush-categories">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseCategories" aria-expanded="false" aria-controls="flush-collapseCategories">
                                  <strong class="fs-5">Categorias</strong>
                                </button>
                              </h2>
                              <div id="flush-collapseCategories" class="accordion-collapse collapse show" aria-labelledby="flush-categories" data-bs-parent="#accordionFlushCategories">
                                <div class="accordion-body">
                                    <div class="accordion accordion-flush" id="accordionFlushCategorie">
                                        <?php
                                            for ($i=0; $i < count($categories) ; $i++) { 
                                                $routeC = base_url()."/tienda/categoria/".$categories[$i]['route'];
                                        ?>
                                        <div class="accordion-item">
                                          <h2 class="accordion-header" id="flush-categorie<?=$i?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseCategorie<?=$i?>" aria-expanded="false" aria-controls="flush-collapseCategorie<?=$i?>">
                                            </button>
                                            <a href="<?=$routeC?>" class="text-decoration-none"><?=$categories[$i]['name']?></a>
                                          </h2>
                                          <div id="flush-collapseCategorie<?=$i?>" class="accordion-collapse collapse show" aria-labelledby="flush-categorie<?=$i?>" data-bs-parent="#accordionFlushCategorie<?=$i?>">
                                            <div class="accordion-body">
                                                <ul class="list-group">
                                                    <?php
                                                        for ($j=0; $j < count($categories[$i]['subcategories']) ; $j++) { 
                                                            $subcategories = $categories[$i]['subcategories'][$j];
                                                            if($subcategories['total'] >0){
                                                            $routeS = base_url()."/tienda/categoria/".$categories[$i]['route']."/".$subcategories['route'];
                                                    ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="<?=$routeS?>"><?=$subcategories['name']?></a>
                                                        <span class="badge bg-color-2 rounded-pill"><?=$subcategories['total']?></span>
                                                    </li>
                                                    <?php } }?>
                                                </ul>
                                            </div>
                                          </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </aside>
                    <div class="filter-options-overlay"></div>
                </div>
                <div class="col-12 col-lg-9 col-md-12">
                    <div class="d-flex align-items-center justify-content-between shop-options">
                        <div class="me-2 c-p" id="filter"><i class="fas fa-filter"></i>Filtro</div>
                        <div class="d-flex align-items-center justify-content-center">
                            <label for="selectSort" class="form-label m-0 me-4 text-end">Ordenar por:</label>
                            <select class="form-select w-50" aria-label="Default select example" id="selectSort">
                                <option value="1" data-url = "<?=base_url()."/tienda?p=".$current?>">Todo</option>
                                <option value="2" data-url = "<?=base_url()."/tienda?p=".$current?>">Mayor a menor precio</option>
                                <option value="3" data-url = "<?=base_url()."/tienda?p=".$current?>">Menor a mayor precio</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5" id="productItems">
                        <?php getComponent("cardProducts",$productos); ?>
                    </div>
                    <div class="pagination">
                        <?php if($current > 1){?>
                        <a href="<?=base_url()."/tienda?p=1".$urlSort?>" class="pagination-btn pagination-start"><i class="fas fa-angle-double-left" aria-hidden="true"></i></a>
                        <a href="<?=base_url()."/tienda?p=".$prevPage.$urlSort?>" class="pagination-btn pagination-prev"><i class="fas fa-angle-left" aria-hidden="true"></i></a>
                        <?php }?>
                        <?php if($current < $paginas){?>
                        <a href="<?=base_url()."/tienda?p=".$nextPage.$urlSort?>" class="pagination-btn pagination-next"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
                        <a href="<?=base_url()."/tienda?p=".$paginas.$urlSort?>" class="pagination-btn pagination-end"><i class="fas fa-angle-double-right" aria-hidden="true"></i></a>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
    footerPage($data);
?>