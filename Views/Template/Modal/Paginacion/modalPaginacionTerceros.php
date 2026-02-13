<app-modal title="Buscar terceros" id="modalPaginacionTerceros" v-model="terceros.showModal" size="lg">
    <template #body>
        <div class="row">
            <div class="col-md-2">
                <app-select label="Por página"  @change="search(1,'terceros')" v-model="terceros.intPerPage">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </app-select>
            </div>
            <div class="col-md-10">
                <app-input label="Buscar" @input="search(1,'terceros')" v-model="terceros.strSearch"></app-input>
            </div>
        </div>
        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>CC/NIT</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data,index) in terceros.arrData" :key="index">
                        <td data-title="ID" class="text-center">{{data.id}}</td>
                        <td data-title="Nombre">{{data.nombre}}</td>
                        <td data-title="CC/NIT">{{data.documento}}</td>
                        <td data-title="Opciones">
                            <div class="d-flex gap-2">
                                <app-button  icon="new" btn="primary" @click="selectItem(data,'terceros')"></app-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <app-pagination :common="terceros" @search="search"></app-pagination>
    </template>
    <template #footer></template>
</app-modal>