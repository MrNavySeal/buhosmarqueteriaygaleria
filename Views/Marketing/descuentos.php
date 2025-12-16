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
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Tipo descuento</th>
                <th>Descuento</th>
                <th>Tiempo límite</th>
                <th>Rango</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data,index) in common.arrData" :key="index">
                <td data-title="ID">{{data.id_discount}}</td>
                <td data-title="Categoría">{{data.categoryid == 0 ? "Todo" : data.category}}</td>
                <td data-title="Subcategoría">{{data.subcategoryid == 0 ? "Todo" : data.subcategory}}</td>
                <td data-title="Tipo descuento">{{data.type == 1 ? "Porcentaje" : "Al por mayor" }}</td>
                <td data-title="Descuento">{{data.type == 1 ? data.discount+"%" : "Al por mayor" }}</td>
                <td data-title="Tiempo límite">{{data.time_limit == 0 ? "Sin límite" : "Con límite" }}</td>
                <td data-title="Rango">{{data.time_limit == 0 ? "N/A" : data.range_time }}</td>
                <td data-title="Estado" class="text-center">
                    <span :class="data.status == '1' ? 'bg-success' : 'bg-danger'" class="badge text-white">
                        {{ data.status == '1' ? "Activo" : "Inactivo" }}
                    </span>
                </td>
                <td data-title="Opciones">
                    <div class="d-flex justify-content-center">
                        <?php if($_SESSION['permitsModule']['u']){ ?>
                        <button class="btn btn-success m-1" type="button" title="Editar"  @click="edit(data.id_discount)" ><i class="fas fa-pencil-alt"></i></button>
                        <?php } ?>
                        <?php if($_SESSION['permitsModule']['d']){ ?>
                        <button class="btn btn-danger m-1" type="button" title="Eliminar" @click="del(data.id_discount)" ><i class="fas fa-trash-alt"></i></button>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<app-pagination :common="common" @search="search" @click="subcategory.modalType='';category.modalType='';search();"></app-pagination>
<?php footerAdmin($data)?>  