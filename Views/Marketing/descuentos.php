<?php 
    headerAdmin($data);
    getModal("Marketing/modalDescuento");
    getModal("Paginacion/modalPaginacionCategorias");
    getModal("Paginacion/modalPaginacionSubcategorias");
?>
<div class="row">
    <div class="col-md-4">
        <app-select label="Por página"  @change="search()" v-model="common.intPerPage">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="1000">1000</option>
        </app-select>
    </div>
    <div class="col-md-8">
        <app-input label="Buscar"  type="text" v-model="common.strSearch" @keyup="subcategory.modalType='';category.modalType='';search();"></app-input>
    </div>
</div>
<div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
    <table class="table align-middle table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo descuento</th>
                <th>Descuento</th>
                <th>Rango</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data,index) in common.arrData" :key="index">
                
            </tr>
        </tbody>
    </table>
</div>
<app-pagination :common="common" @search="search" @click="subcategory.modalType='';category.modalType='';search();"></app-pagination>
<?php footerAdmin($data)?>  