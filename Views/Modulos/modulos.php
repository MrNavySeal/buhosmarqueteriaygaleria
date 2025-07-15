<?php 
    headerAdmin($data); 
?>
<modal-module @saved="search"></modal-module>
<div class="row">
    <div class="col-md-4">
        <app-select label="Seleccione"  @change="search()" v-model="common.intPerPage">
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
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data,index) in common.arrData" :key="index">
                <td>{{data.id}}</td>
                <td>{{data.name}}</td>
                <td>
                    <div class="d-flex gap-2">
                        <app-button  icon="edit" btn="success" @click="common.showModalModule=true;common.modulesTitle='Editar modulo'"></app-button>
                        <app-button  icon="delete" btn="danger"></app-button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<app-pagination :common="common" @search="search"></app-pagination>
<?php footerAdmin($data); ?>