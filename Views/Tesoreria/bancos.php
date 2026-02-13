<?php 
    headerAdmin($data);
    getModal("Tesoreria/modalBancos");
    getModal("Contabilidad/modalCatalogoPuc");
    getModal("Paginacion/modalPaginacionTerceros");
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
                <th>Banco</th>
                <th>Cuenta bancaria</th>
                <th>Tipo</th>
                <th>Cuenta contable</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data,index) in common.arrData" :key="index">
                <td data-title="ID">{{data.id}}</td>
                <td data-title="Banco">{{data.nombre}}</td>
                <td data-title="Cuenta bancaria">{{data.bank_account}}</td>
                <td data-title="Tipo">{{data.type}}</td>
                <td data-title="Cuenta contable">{{data.code+"-"+data.account}}</td>
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