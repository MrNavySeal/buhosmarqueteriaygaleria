<?php 
    headerAdmin($data);
    getModal("Modulos/modalOpcion"); 
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
        <app-input label="Buscar" @input="search()" v-model="common.strSearch"></app-input>
    </div>
</div>
<div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
    <table class="table align-middle table-hover">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Ruta</th>
                <th>Sección</th>
                <th>Módulo</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data,index) in common.arrData" :key="index">
                <td data-title="Id">{{data.id}}</td>
                <td data-title="Nombre">{{data.name}}</td>
                <td data-title="Ruta">{{data.route}}</td>
                <td data-title="Sección">{{data.section_id == 0 ? "N/A" : data.section}}</td>
                <td data-title="Módulo">{{data.module}}</td>
                <td>
                    <div class="d-flex gap-2">
                        <app-button  icon="edit" btn="success" @click="edit(data)" ></app-button>
                        <app-button  icon="delete" btn="danger" @click="del(data)"></app-button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<app-pagination :common="common" @search="search"></app-pagination>
<?php footerAdmin($data); ?>