<?php 
    $categories = $data;
?>
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