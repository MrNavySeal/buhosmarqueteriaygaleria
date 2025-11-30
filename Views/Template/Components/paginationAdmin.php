<?php
    $page = $data['page'];
    $startPage = $data['start_page'];
    $limitPages = $data['limit_pages'];
    $totalPages = $data['total_pages'];
    $max = max(1, $page-1);
    $min = min($totalPages, $page+1);
?>
<li class="page-item">
    <button type="button" class="page-link text-secondary" href="#" onclick="getData(1)" aria-label="First">
        <span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span>
    </button>
</li>
<li class="page-item">
    <button type="button" class="page-link text-secondary" href="#" onclick="getData(<?= $max ?>)" aria-label="Previous">
        <span aria-hidden="true"><i class="fas fa-angle-left"></i></span>
    </button>
</li>

<?php for ($i = $startPage; $i < $limitPages; $i++) { ?>
    <li class="page-item">
        <button type="button" class="page-link  <?= ($i == $page ? ' bg-primary text-white' : 'text-secondary') ?>" href="#" onclick="getData(<?= $i ?>)"><?= $i ?></button>
    </li>
<?php }?>

<li class="page-item">
    <button type="button" class="page-link text-secondary" href="#" onclick="getData(<?= $min ?>)" aria-label="Next">
        <span aria-hidden="true"><i class="fas fa-angle-right"></i></span>
    </button>
</li>
<li class="page-item">
    <button type="button" class="page-link text-secondary" href="#" onclick="getData(<?= $totalPages ?>)" aria-label="Last">
        <span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span>
    </button>
</li>