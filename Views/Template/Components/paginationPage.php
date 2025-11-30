<?php
    $page = $data['page'];
    $startPage = $data['start_page'];
    $limitPages = $data['limit_pages'];
    $totalPages = $data['total_pages'];
    $max = max(1, $page-1);
    $min = min($totalPages, $page+1);
?>

<a href="#" class="pagination-btn pagination-start" onclick="getData(1)"><i class="fas fa-angle-double-left" aria-hidden="true"></i></a>
<a href="#" class="pagination-btn pagination-prev" onclick="getData(<?= $max ?>)"><i class="fas fa-angle-left" aria-hidden="true"></i></a>

<?php for ($i = $startPage; $i < $limitPages; $i++) { ?>
    <a href="#" class="pagination-btn <?= ($i == $page ? ' active' : '') ?>" onclick="getData(<?= $i ?>)"><?= $i ?></a>
<?php }?>

<a href="#" class="pagination-btn pagination-next" onclick="getData(<?= $min ?>)"><i class="fas fa-angle-right" aria-hidden="true"></i></a>
<a href="#" class="pagination-btn pagination-end" onclick="getData(<?= $totalPages ?>)"><i class="fas fa-angle-double-right" aria-hidden="true"></i></a>