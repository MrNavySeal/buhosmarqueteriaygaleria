<div class="row">
    <div class="me-2 c-p" id="filter"><i class="fas fa-filter"></i>Filtro</div>
    <div class="col-md-6">
        <div class="d-flex align-items-center justify-content-start gap-2">
            <span>Mostrando</span>
            <select class="form-select" aria-label="Default select example" id="selectPerPage">
                <?php getComponent("pageOptions") ?>
            </select>
            <span class="w-100" id="filterResults"> de 0 resultados</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex align-items-center w-100">
            <label for="selectSort" class="form-label m-0 w-50">Ordenar por:</label>
            <select class="form-select" aria-label="Default select example" id="selectSort">
                <option value="1">Los más recientes</option>
                <option value="2">Mayor a menor precio</option>
                <option value="3">Menor a mayor precio</option>
            </select>
        </div>
    </div>
</div>