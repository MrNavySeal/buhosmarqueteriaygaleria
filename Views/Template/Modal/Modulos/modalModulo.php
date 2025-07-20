<app-modal :title="common.modulesTitle" id="modalModules" v-model="common.showModalModule">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Nombre" type="text" v-model="common.strName" required="true"></app-input>
            </div>
            <div class="col-md-6">
                <app-input label="Icono" type="text" v-model="strIcon"></app-input>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Orden" type="number" v-model="intLevel"></app-input>
            </div>
            <div class="col-md-6">
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