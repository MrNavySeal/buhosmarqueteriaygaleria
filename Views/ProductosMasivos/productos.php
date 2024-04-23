<?php headerPage($data)?>
<div id="modalItem"></div>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <div class="d-flex align-items-center">
                    <a href="<?=base_url()?>/productos" class="btn btn-primary me-2"><i class="fas fa-arrow-circle-left"></i></a>
                    <h2 class="text-center m-0"><?=$data['page_title']?></h2>
                </div>
                <ul class="nav nav-pills mb-5" id="product-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Crear</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab" aria-controls="social" aria-selected="false">Editar</button>
                    </li>
                </ul>
                <div class="tab-content mb-3" id="myTabContent">
                    <div class="tab-pane show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                        <form id="formItem" name="formItem" class="mb-4">  
                        </form>
                    </div>
                    <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab"></div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerPage($data)?> 
