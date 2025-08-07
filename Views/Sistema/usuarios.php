<?php 
    headerAdmin($data);
    getModal("Sistema/modalUsuarios"); 
    getModal("Sistema/modalPermisos"); 
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
                <th>Portada</th>
                <th>Nombre</th>
                <th>CC/NIT</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>País</th>
                <th>Departamento</th>
                <th>Ciudad</th>
                <th>Dirección</th>
                <th>Rol</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data,index) in common.arrData" :key="index">
                <td data-title="ID" class="text-center">{{data.id}}</td>
                <td data-title="Portada">
                    <img :src="data.url" :alt="data.name" class="img-thumbnail" style="width: 50px; height: 50px;">
                </td>
                <td data-title="Nombre">{{data.nombre}}</td>
                <td data-title="CC/NIT">{{data.documento}}</td>
                <td data-title="Correo">{{data.email}}</td>
                <td data-title="Teléfono" class="text-nowrap">{{data.telefono}}</td>
                <td data-title="País">{{data.pais}}</td>
                <td data-title="Departamento">{{data.departamento}}</td>
                <td data-title="Ciudad">{{data.ciudad}}</td>
                <td data-title="Dirección">{{data.direccion}}</td>
                <td data-title="Rol">{{data.role}}</td>
                <td data-title="Fecha">{{data.fecha}}</td>
                <td data-title="Estado" class="text-center">
                    <span :class="data.status == '1' ? 'bg-success' : 'bg-danger'" class="badge text-white">
                        {{ data.status == '1' ? "Activo" : "Inactivo" }}
                    </span>
                </td>
                <td data-title="Opciones">
                    <div class="d-flex gap-2">
                        <?php if($_SESSION['permitsModule']['u']){ ?>
                        <app-button  icon="key" btn="secondary" @click="permissions(data)"></app-button>
                        <?php } ?>
                        <?php if($_SESSION['permitsModule']['u']){ ?>
                        <app-button  icon="edit" btn="success" @click="edit(data)"></app-button>
                        <?php } ?>
                        <?php if($_SESSION['permitsModule']['d']){ ?>
                        <app-button  icon="delete" btn="danger" @click="del(data)"></app-button>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<app-pagination :common="common" @search="search"></app-pagination>
<?php footerAdmin($data); ?>