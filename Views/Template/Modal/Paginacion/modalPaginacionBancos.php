<app-modal title="Buscar cuentas bancarias" id="modalPaginacionBancos" v-model="bancos.showModal" size="lg">
    <template #body>
        <div class="row">
            <div class="col-md-2">
                <app-select label="Por página"  @change="search(1,'bancos')" v-model="bancos.intPerPage">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </app-select>
            </div>
            <div class="col-md-10">
                <app-input label="Buscar" @input="search(1,'bancos')" v-model="bancos.strSearch"></app-input>
            </div>
        </div>
        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Banco</th>
                        <th>Tipo</th>
                        <th>Cuenta bancaria</th>
                        <th>Cuenta contable</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data,index) in bancos.arrData" :key="index">
                        <td data-title="ID">{{data.id}}</td>
                        <td data-title="Banco">{{data.nombre}}</td>
                        <td data-title="Tipo">{{data.type}}</td>
                        <td data-title="Cuenta bancaria">{{data.bank_account}}</td>
                        <td data-title="Cuenta contable">{{data.code+"-"+data.account}}</td>
                        <td data-title="Opciones">
                            <div class="d-flex gap-2">
                                <app-button  icon="new" btn="primary" @click="selectItem(data,'bancos')"></app-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <app-pagination :common="bancos" @search="search"></app-pagination>
    </template>
    <template #footer></template>
</app-modal>