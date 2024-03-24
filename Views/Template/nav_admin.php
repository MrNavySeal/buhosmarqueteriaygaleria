<?php
    $notification = emailNotification(); 
    $comments = commentNotification();
    $reviews = $comments['total'];
?>
<nav class="mt-5 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?=$data['page_tag']?></li>
    </ol>
</nav>
<div class="mt-4 mb-4 c-p" id="filter"><i class="fas fa-cog"></i> Opciones</div>
<div class="col-3 col-lg-3 col-md-12">
    <aside class="p-2 filter-options">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <?php require('Views/Template/nav_options.php');?>
        </div>
    </aside>
    <div class="filter-options-overlay"></div>
</div>