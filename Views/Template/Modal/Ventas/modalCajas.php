<app-modal :title="common.title" id="modalCajas" v-model="common.showModal" size="xl">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <ul class="nav nav-pills mb-5" id="product-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Datos básicos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab" aria-controls="social" aria-selected="false">Medios de pago</button>
            </li>
        </ul>
        <div class="tab-content mb-3" id="myTabContent">
            <div class="tab-pane show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <div class="row">
                    <div class="col-md-5">
                        <app-input label="Nombre" type="text" v-model="strNombre" required="true" :errors="errores.nombre"></app-input>
                    </div>
                    <div class="col-md-4">
                        <app-button-input title="Sucursal"
                        btn="primary" icon="new" 
                        v-model="objSucursal.name" 
                        disabled
                        required="true"
                        :errors="errores.ingreso"
                        >
                            <template #left>
                                <app-button icon="search" btn="primary" @click="sucursales.showModal = true;search(1,'sucursales');"></app-button>
                            </template>
                        </app-button-input>
                    </div>
                    <div class="col-md-3">
                        <app-select label="Estado"  v-model="intEstado">
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </app-select>
                    </div>
                </div>
                <app-button-input title="Cajeros"
                    btn="primary" icon="new" 
                    v-model="objCajero.name" 
                    disabled
                    >
                        <template #left>
                            <app-button icon="search" btn="primary" @click="sucursales.showModal = true;search(1,'sucursales');"></app-button>
                        </template>
                        <template #right>
                            <app-button icon="new" btn="primary" @click="addItem()" title="agregar"></app-button>
                        </template>
                </app-button-input>
                <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
                    <table class="table align-middle table-hover">
                        <tbody>
                            <tr v-for="(data,index) in arrDetalle" :key="index">
                                <td data-title="Cajeros">
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
            <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                <div class="row" v-for="(metodo,index) in arrMetodos">
                    <div class="col-sm-4 d-flex align-items-center">
                        <app-input :label="metodo.nombre" :title="metodo.nombre" type="switch" v-model="metodo.checked"></app-input>
                    </div>
                    <div class="col-sm-8 d-flex align-items-center" v-if="metodo.checked">
                        <app-multiselect :tag="metodo.nombre+index" class="w-100" title="" format="-" :values="['id']"  :showup="['name']" 
                            :options="metodo.formas"  v-model="metodo.seleccionados">
                            <template v-slot="{options}">
                                <app-multiselect-option v-for="(data,j) in options"  :key="j"  :checked="data.is_checked" :tag="metodo.nombre+j" :data="data">
                                    {{data.name}}
                                </app-multiselect-option>
                            </template>
                        </app-multiselect>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>