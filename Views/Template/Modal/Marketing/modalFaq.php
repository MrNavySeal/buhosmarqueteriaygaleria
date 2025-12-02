<app-modal :title="common.modulesTitle" id="modalFaq" v-model="common.showModalModule">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <app-input label="Pregunta" type="text" :errors="common.errors.pregunta" v-model="strPregunta" required="true"></app-input>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Respuesta</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" v-model="strRespuesta" required rows="3"></textarea>
            <ul class="m-0">
                <li class="text-danger" v-for="(data,index) in common.errors.respuesta">{{data}}<br></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-select label="Sección" :errors="common.errors.seccion" required="true" v-model="intSeccion">
                    <option :value="data.id" v-for="(data,index) in arrSecciones" :key="index">{{data.name}}</option>
                </app-select>
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