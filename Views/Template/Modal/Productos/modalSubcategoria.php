<app-modal :title="common.subcategoryTitle" id="modalSubcategory" v-model="common.showModalSubcategory">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="row">
            <div class="col-md-12">
                <app-input label="Nombre" type="text" v-model="common.strName" :errors="common.errors.name"></app-input>
            </div>
            <div class="col-md-12">
                <app-button-input :errors="common.errors.category"
                btn="primary" icon="new" 
                :value="objCategory.name" 
                @click="pagination.modalType='category';pagination.showModalPaginationCategory=true;search();"></app-button-input>
            </div>
            <div class="col-md-12">
                <app-select label="Estado"  v-model="intStatus">
                    <option value="1">Activo</option>
                    <option value="2">Inactivo</option>
                </app-select>
            </div>
        </div>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>