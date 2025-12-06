<app-modal :title="common.modulesTitle" id="modalProduct" v-model="common.showModalModule" size="xl">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="row">
            <div class="col-md-6">
                <app-select label="Tipo de descuento"  v-model="intType">
                    <option value="1">Porcentaje</option>
                    <option value="2">Al por mayor</option>
                </app-select>
            </div>
            <div class="col-md-6">
                <app-select label="Tiempo límite"  v-model="intLimit">
                    <option value="1">Sin límite</option>
                    <option value="2">Con límite</option>
                </app-select>
            </div>
        </div>
        <div class="row" v-if="intLimit == 2">
            <div class="col-md-6">
                <app-input label="Desde" type="date" :errors="common.errors.discount" v-model="strInitialDate" required="true"></app-input>
            </div>
            <div class="col-md-6">
                <app-input label="Hasta" type="date" :errors="common.errors.discount" v-model="strFinalDate" required="true"></app-input>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-button-input 
                    title="Categorías"
                    btn="primary" icon="new" 
                    v-model="objCategory.name" 
                    required="true"
                    :errors="errors.category"
                    disabled
                    >
                    <template #left>
                        <app-button icon="new" btn="primary" @click="changeCategory('category')"></app-button>
                    </template>
                    <template #right>
                        <app-button icon="delete" btn="danger" @click="delItem('category')"></app-button>
                    </template>
                </app-button-input>
            </div>
            <div class="col-md-6">
                <app-button-input 
                    title="Subcategorias"
                    btn="primary" icon="new" 
                    v-model="objSubcategory.name" 
                    required="true"
                    :errors="errors.subcategory"
                    disabled
                    >
                        <template #left>
                            <app-button icon="new" btn="primary" @click="changeCategory('subcategory')"></app-button>
                        </template>
                        <template #right>
                            <app-button icon="delete" btn="danger" @click="delItem('subcategory')">
                        </template>
                    </app-button>
                </app-button-input>
            </div>
        </div>
        <div v-if="intType == 2">
            <div class="row" v-for="(data,index) in arrWholesalePrices" :key="index">
                <div class="col-md-4">
                    <app-input  title="Cantidad mínima" type="number" v-model="data.min" @change="changeWholeSaleMaxQty()" :disabled = "index > 0"></app-input>
                </div>
                <div class="col-md-4">
                    <app-input  title="Cantidad máxima" type="number" v-model="data.max" @change="changeWholeSaleMaxQty()"></app-input>
                </div>
                <div class="col-md-4">
                    <app-button-input  label="intWholeSalePercent" title="Descuento (%)" btn="primary" @change="changeWholeSalePercent(index)" v-model="data.percent" required="false">
                        <template #right>
                            <app-button icon="delete" btn="danger" @click="delItem('price',index)"></app-button>
                        </template>
                    </app-button-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <app-input  label="intWholeSaleMinQty" title="Cantidad mínima" :disabled="arrWholesalePrices.length > 0" type="number" v-model="intWholeSaleMinQty"></app-input>
                </div>
                <div class="col-md-4">
                    <app-input  label="intWholeSaleMaxQty" title="Cantidad máxima" type="number" v-model="intWholeSaleMaxQty"></app-input>
                </div>
                <div class="col-md-4">
                    <app-button-input  label="intWholeSalePercent" title="Porcentaje" btn="primary" v-model="intWholeSalePercent" required="false">
                        <template #right>
                            <app-button icon="new" btn="primary" title="Agregar" @click="addItem('price')"></app-button>
                        </template>
                    </app-button-input>
                </div>
            </div>
        </div>
        <app-input v-else label="Descuento" type="text" :errors="common.errors.discount" v-model="intDiscount" required="true"></app-input>
        <app-select label="Estado"  v-model="intStatus">
            <option value="1">Activo</option>
            <option value="2">Inactivo</option>
        </app-select>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>