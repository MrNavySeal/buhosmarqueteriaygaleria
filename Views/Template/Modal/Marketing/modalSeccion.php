<app-modal :title="common.modulesTitle" id="modalSeccion" v-model="common.showModalModule">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <app-input label="Nombre" type="text" :errors="common.errors.name" v-model="strName" required="true"></app-input>
        <app-select label="Estado"  v-model="intStatus">
            <option value="1">Activo</option>
            <option value="2">Inactivo</option>
        </app-select>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>