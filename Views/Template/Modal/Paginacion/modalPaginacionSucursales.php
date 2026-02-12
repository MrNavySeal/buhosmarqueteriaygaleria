<app-modal title="Buscar sucursales" id="modalPaginacionSucursales" v-model="sucursales.showModal" size="lg">
    <template #body>
        <div class="row">
            <div class="col-md-2">
                <app-select label="Por página"  @change="search(1,'sucursales')" v-model="sucursales.intPerPage">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </app-select>
            </div>
            <div class="col-md-10">
                <app-input label="Buscar" @input="search(1,'sucursales')" v-model="sucursales.strSearch"></app-input>
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
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data,index) in sucursales.arrData" :key="index">
                        <td data-title="ID">{{data.id}}</td>
                        <td data-title="Nombre">{{data.name}}</td>
                        <td data-title="País">{{data.country}}</td>
                        <td data-title="Departamento">{{data.state}}</td>
                        <td data-title="Ciudad">{{data.city}}</td>
                        <td data-title="Telefono">{{data.phone}}</td>
                        <td data-title="Dirección">{{data.address}}</td>
                        <td data-title="Opciones">
                            <div class="d-flex gap-2">
                                <app-button  icon="new" btn="primary" @click="selectItem(data,'sucursales')"></app-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <app-pagination :common="sucursales" @search="search"></app-pagination>
    </template>
    <template #footer></template>
</app-modal>