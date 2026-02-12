<app-modal :title="common.title" id="modalFormaPago" v-model="common.showModal" size="xl">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Nombre" type="text" v-model="strNombre" :errors="errores.nombre" required="true"></app-input>
            </div>
            <div class="col-md-3">
                <app-select label="Método de pago"  v-model="strTipo" :errors="errores.tipo" required="true">
                    <option v-for="(data,index) in arrTipos" :key="index" :value="data.id">{{data.nombre}}</option>
                </app-select>
            </div>
            <div class="col-md-3">
                <app-select label="Relación"  v-model="strRelacion" :errors="errores.relacion" required="true">
                    <option v-for="(data,index) in arrRelaciones" :key="index" :value="data.id">{{data.nombre}}</option>
                </app-select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-button-input title="Cuenta contable"
                btn="primary" icon="new" 
                v-model="objCuenta.name" 
                disabled
                required="true"
                :errors="errores.ingreso"
                >
                    <template #left>
                        <app-button icon="search" btn="primary" @click="cuentas.showModal = true;search(1,'cuentas');"></app-button>
                    </template>
                </app-button-input>
            </div>
            <div class="col-md-6">
                <app-select label="Estado"  v-model="intEstado" :errors="errores.estado">
                    <option value="1">Activo</option>
                    <option value="2">Inactivo</option>
                </app-select>
            </div>
        </div>
        <div v-if="strTipo==5">
            <div class="row">
                <div class="col-md-12">
                    <app-button-input title="Descuentos"
                    btn="primary" icon="new" 
                    v-model="objRetencion.name" 
                    disabled
                    >
                        <template #left>
                            <app-button icon="search" btn="primary" @click="retenciones.showModal = true;intTipoRetencion='retencion';search(1,'retenciones');"></app-button>
                        </template>
                        <template #right>
                            <app-button icon="new" btn="primary" @click="addItem()" title="agregar"></app-button>
                        </template>
                    </app-button-input>
                </div>
            </div>
            <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
                <table class="table align-middle table-hover">
                    <tbody>
                        <tr v-for="(data,index) in arrDetalle" :key="index">
                            <td data-title="Descuentos">
                                <app-button-input title="" btn="primary" icon="new"  disabled="disabled" v-model="data.name">
                                    <template #right>
                                        <app-button  icon="delete" btn="danger" @click="delItem(data)"></app-button>
                                    </template>
                                </app-button-input>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>