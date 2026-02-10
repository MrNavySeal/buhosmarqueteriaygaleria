<app-modal title="Buscar ingresos y pagos" id="modalIngresoPago" v-model="retenciones.showModal" size="lg">
    <template #body>
        <div class="row">
            <div class="col-md-2">
                <app-select label="Por página"  @change="search(1,'retenciones')" v-model="retenciones.intPerPage">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </app-select>
            </div>
            <div class="col-md-2">
                <app-select label="Clase"  @change="search(1,'retenciones')" v-model="intFiltroTipo" :errors="errores.clase">
                    <option value="retencion">Retenciones/gastos</option>
                    <option value="ingreso">Ingreso</option>
                </app-select>
            </div>
            <div class="col-md-8">
                <app-input label="Buscar" @input="search(1,'retenciones')" v-model="retenciones.strSearch"></app-input>
            </div>
        </div>
        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Clase</th>
                        <th>Tipo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data,index) in retenciones.arrData" :key="index">
                        <td data-title="ID">{{data.id}}</td>
                        <td data-title="Nombre">{{data.name}}</td>
                        <td data-title="Clase">{{data.kind}}</td>
                        <td data-title="Tipo">{{data.type}}</td>
                        <td data-title="Opciones">
                            <div class="d-flex gap-2">
                                <app-button  icon="new" btn="primary" @click="selectItem(data,'retenciones')"></app-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <app-pagination :common="retenciones" @search="search"></app-pagination>
    </template>
    <template #footer></template>
</app-modal>