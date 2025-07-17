<app-modal :title="common.modulesTitle" id="modalModules" v-model="common.showModalModule">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Nombre" type="text" v-model="common.strName"></app-input>
            </div>
            <div class="col-md-6">
                <app-input label="Ruta" type="text" v-model="strRoute"></app-input>
            </div>
            <div class="col-md-6">
                <app-select label="Módulo"  v-model="intModule">
                    <option v-for="(data,index) in arrModules" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
            <div class="col-md-6">
                <app-select label="Sección"  v-model="intSection">
                    <option selected value="0">N/A</option>
                    <option v-for="(data,index) in filteredSections" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
        </div>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>