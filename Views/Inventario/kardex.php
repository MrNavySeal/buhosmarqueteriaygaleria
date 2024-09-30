<?php headerAdmin($data)?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="table-responsive">
        <table class="table table-bordered align-middle" >
            <tbody id="tableData"></tbody>
        </table>
    </div>
</div>
<?php footerAdmin($data)?> 