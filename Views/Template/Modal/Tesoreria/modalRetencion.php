<app-modal :title="common.title" id="modalTaxHolding" v-model="common.showModal" size="xl">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Nombre" type="text" v-model="strNombre" :errors="errores.nombre" required="true"></app-input>
            </div>
            <div class="col-md-2">
                <app-select label="Clase"  v-model="strClase" :errors="errores.clase">
                    <option value="retencion">Retenciones/gastos</option>
                    <option value="ingreso">Ingreso</option>
                </app-select>
            </div>
            <div class="col-md-2">
                <app-select label="Tipo"  v-model="strTipo" :errors="errores.tipo">
                    <option value="valor">Valor</option>
                    <option value="porcentaje">Porcentaje</option>
                </app-select>
            </div>
            <div class="col-md-2">
                <app-select label="Estado"  v-model="intEstado" :errors="errores.estado">
                    <option value="1">Activo</option>
                    <option value="2">Inactivo</option>
                </app-select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-button-input title="Concepto contable"
                btn="primary" icon="new" 
                v-model="objConcepto.name" 
                disabled
                >
                    <template #left>
                        <app-button icon="search" btn="primary" @click="concepto.showModal = true;search(1,'concepto');"></app-button>
                    </template>
                </app-button-input>
            </div>
            <div class="col-md-6" v-if="strTipo == 'valor'">
                <app-button-input title="Valor"
                btn="primary" icon="new" 
                v-model="objValor.valor_formato"
                @input="formatInputNumber(objValor,'valor_formato','valor')"
                >
                    <template #right>
                        <app-button icon="new" btn="primary" @click="addItem()" title="agregar"></app-button>
                    </template>
                </app-button-input>
            </div>
            <div class="col-md-6" v-else>
                <app-button-input title="Porcentaje"
                btn="primary" icon="new" 
                v-model="intPorcentaje"
                >
                    <template #right>
                        <app-button icon="new" btn="primary" @click="addItem()" title="agregar"></app-button>
                    </template>
                </app-button-input>
            </div>
        </div>
        <ul class="m-0">
            <li class="text-danger" v-for="(data,index) in errores.conceptos">{{data}}<br></li>
        </ul>
        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data,index) in arrDetalle" :key="index">
                        <td data-title="Concepto">{{data.name}}</td>
                        <td data-title="Valor">
                            <app-button-input v-if="strTipo == 'valor'" title="" btn="primary" icon="new"  v-model="data.valor.valor_formato"
                            @input="formatInputNumber(data.valor,'valor_formato','valor')" >
                                <template #right>
                                    <app-button  icon="delete" btn="danger" @click="delItem(data)"></app-button>
                                </template>
                            </app-button-input>
                            <app-button-input v-else title="" btn="primary" icon="new"  v-model="data.porcentaje" >
                                <template #right>
                                    <app-button  icon="delete" btn="danger" @click="delItem(data)"></app-button>
                                </template>
                            </app-button-input>
                        </td>
                    </tr>
                    <tr class="fw-bold">
                        <td class="text-end">Total</td>
                        <td>{{totalValor}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <ul class="m-0">
            <li class="text-danger" v-for="(data,index) in errores.total">{{data}}<br></li>
        </ul>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>