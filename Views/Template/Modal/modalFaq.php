<app-modal :title="common.modulesTitle" id="modalFaq" v-model="common.showModalModule">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <app-input label="Pregunta" type="text" :errors="common.errors.name" v-model="strPregunta" required="true"></app-input>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Respuesta</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" v-model="strRespuesta" required rows="3"></textarea>
        </div>
        <app-select label="Estado"  v-model="intStatus">
            <option value="1">Activo</option>
            <option value="2">Inactivo</option>
        </app-select>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>