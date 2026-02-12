<?php 
    headerAdmin($data);
    getModal("Ventas/modalCajas");
    getModal("Paginacion/modalPaginacionSucursales");
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
                <th>ID</th>
                <th>Nombre</th>
                <th>Pais</th>
                <th>Departamento</th>
                <th>Ciudad</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data,index) in common.arrData" :key="index">
                <td data-title="Id">{{data.id}}</td>
                <td data-title="Nombre">{{data.name}}</td>
                <td data-title="País">{{data.country}}</td>
                <td data-title="Departamento">{{data.state}}</td>
                <td data-title="Ciudad">{{data.city}}</td>
                <td data-title="Telefono">{{data.phone}}</td>
                <td data-title="Dirección">{{data.address}}</td>
                <td data-title="Estado" class="text-center">
                    <span :class="data.status == '1' ? 'bg-success' : 'bg-danger'" class="badge text-white">
                        {{ data.status == '1' ? "Activo" : "Inactivo" }}
                    </span>
                </td>
                <td data-title="Opciones">
                    <div class="d-flex gap-2">
                        <?php if($_SESSION['permitsModule']['u']){ ?>
                        <app-button  icon="edit" btn="success" @click="edit(data)"></app-button>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<app-pagination :common="common" @search="search"></app-pagination>
<?php footerAdmin($data)?>         