<app-modal :title="common.title" id="modalModules" v-model="common.showModal" size="xl">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Nombre" :errors="errores.nombre" type="text" v-model="strNombre"   required="true"></app-input>
            </div>
            <div class="col-md-3">
                <app-select label="Tipo"  :errors="errores.tipo" v-model="intTipo" required="true">
                    <option v-for="(data,index) in arrTipos" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
            <div class="col-md-3">
                <app-select label="Estado"  v-model="intEstado">
                    <option value="1">Activo</option>
                    <option value="2">Inactivo</option>
                </app-select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-button-input title="Cuenta contable"
                btn="primary" icon="new" 
                v-model="objCuenta.name" 
                disabled
                >
                    <template #left>
                        <app-button icon="search" btn="primary" @click="cuentas.showModal = true;cuentas.modalType='cuentas';"></app-button>
                    </template>
                </app-button-input>
            </div>
            <div class="col-md-6">
                <app-button-select  label="naturaleza" placeholder="Seleccione" title="Naturaleza" v-model="objCuenta.nature">
                    <template #options>
                        <option value="debito">Débito</option>
                        <option value="credito">Crédito</option>
                    </template>
                    <template #right>
                        <app-button icon="new" btn="primary" @click="addItem()" title="agregar"></app-button>
                    </template>
                </app-button-select>
            </div>
        </div>
        <ul class="m-0">
            <li class="text-danger" v-for="(data,index) in errores.detalle">{{data}}<br></li>
        </ul>
        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Cuenta</th>
                        <th>Nombre</th>
                        <th>Naturaleza</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data,index) in arrDetalle" :key="index">
                        <td data-title="Cuenta">{{data.code}}</td>
                        <td data-title="Nombre">{{data.name}}</td>
                        <td data-title="Naturaleza">{{data.nature}}</td>
                        <td data-title="Opciones">
                            <div class="d-flex justify-content-center gap-2">
                                <app-button  icon="delete" btn="danger" @click="delItem(data)"></app-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </template>
    <template #footer>
        <app-button icon="save" @click="cuentas.modalType='';save();" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>