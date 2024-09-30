<?php headerAdmin($data)?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="row">
        <div class="col-md-12 mb-1">
            <button class="btn btn-sm btn-success"><i class='fas fa-file-excel'></i> Excel</button>
            <button class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> PDF</button>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="txtInitialDate" class="form-label">Fecha inicial</label>
                        <input type="date" class="form-control" id="txtInitialDate" name="txtInitialDate">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="txtFinalDate" class="form-label">Fecha final</label>
                        <input type="date" class="form-control" id="txtFinalDate" name="txtFinalDate">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="txtSearch" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="txtSearch" name="txtSearch">
            </div>
        </div>
    </div>
    <div class="table-responsive" style="max-height:60vh">
        <table class="table table-bordered align-middle" >
            <tbody id="tableData"></tbody>
        </table>
    </div>
</div>
<?php footerAdmin($data)?> 