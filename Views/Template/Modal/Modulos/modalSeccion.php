<app-modal :title="common.modulesTitle" id="modalModules" v-model="common.showModalModule">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <app-input label="Nombre" type="text" v-model="common.strName"></app-input>
        <app-select label="Módulo"  v-model="intModule">
            <option v-for="(data,index) in arrModules" :value="data.id">{{data.name}}</option>
        </app-select>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>