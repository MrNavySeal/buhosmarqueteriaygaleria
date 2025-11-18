<app-modal :title="common.modulesTitle" id="showPermissionModal" v-model="showPermissionModal" size="xl" scroll="true">
    <input type="hidden" v-model="intIdRol">
    <template #body>
        <div class="d-flex gap-4 ms-3 mb-2 flex-wrap">
            <div class="form-check form-switch text-normal">
                <input class="form-check-input" @change="setPermission('all')" type="checkbox" role="switch" v-model="checkR" id="switchCheckR">
                <label class="form-check-label" for="switchCheckR">Leer </label>
            </div>
            <div class="form-check form-switch text-normal">
                <input class="form-check-input" @change="setPermission('all')" type="checkbox" role="switch" v-model="checkW" id="switchCheckW">
                <label class="form-check-label" for="switchCheckW">Crear </label>
            </div>
            <div class="form-check form-switch text-normal">
                <input class="form-check-input" @change="setPermission('all')" type="checkbox" role="switch" v-model="checkU" id="switchCheckU">
                <label class="form-check-label" for="switchCheckU">Actualizar </label>
            </div>
            <div class="form-check form-switch text-normal">
                <input class="form-check-input" @change="setPermission('all')" type="checkbox" role="switch" v-model="checkD" id="switchCheckD">
                <label class="form-check-label" for="switchCheckD">Eliminar </label>
            </div>
        </div>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item" v-for="(module,moduleIndex) in arrPermissions" :key="module.id">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#flush-module-'+module.label" aria-expanded="false" :aria-controls="'flush-module-'+module.label">
                        {{module.name}}
                    </button>
                </h2>
                <div :id="'flush-module-'+module.label" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <div class="d-flex gap-4 flex-wrap">
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" @change="setPermission('module',module)" type="checkbox" role="switch" v-model="module.r" :id="'switchCheckModuleR'+module.label">
                                <label class="form-check-label" :for="'switchCheckModuleR'+module.label">Leer </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" @change="setPermission('module',module)" type="checkbox" role="switch" v-model="module.w" :id="'switchCheckModuleW'+module.label">
                                <label class="form-check-label" :for="'switchCheckModuleW'+module.label">Crear </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" @change="setPermission('module',module)" type="checkbox" role="switch" v-model="module.u" :id="'switchCheckModuleU'+module.label">
                                <label class="form-check-label" :for="'switchCheckModuleU'+module.label">Actualizar </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" @change="setPermission('module',module)" type="checkbox" role="switch" v-model="module.d" :id="'switchCheckModuleD'+module.label">
                                <label class="form-check-label" :for="'switchCheckModuleD'+module.label">Eliminar </label>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex gap-4 mt-2 flex-wrap" v-for="(option,optionIndex) in module.options" :key="option.id">
                            <span>{{option.name}}</span>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" type="checkbox" role="switch" v-model="option.r" :id="'switchCheckOptionR'+option.label">
                                <label class="form-check-label" :for="'switchCheckOptionR'+option.label">Leer </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" type="checkbox" role="switch" v-model="option.w" :id="'switchCheckOptionW'+option.label">
                                <label class="form-check-label" :for="'switchCheckOptionW'+option.label">Crear </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" type="checkbox" role="switch" v-model="option.u" :id="'switchCheckOptionU'+option.label">
                                <label class="form-check-label" :for="'switchCheckOptionU'+option.label">Actualizar </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" type="checkbox" role="switch" v-model="option.d" :id="'switchCheckOptionD'+option.label">
                                <label class="form-check-label" :for="'switchCheckOptionD'+option.label">Eliminar </label>
                            </div>
                        </div>
                        <div class="accordion accordion-flush mt-2" :id="'accordionFlushSection'+section.label" v-for="(section,sectionIndex) in module.sections" :key="section.id">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#flush-section-'+section.label" aria-expanded="false" :aria-controls="'flush-section-'+section.label">
                                    {{section.name}}
                                </button>
                            </h2>
                            <div :id="'flush-section-'+section.label" class="accordion-collapse collapse" :data-bs-parent="'#accordionFlushSection'+section.label">
                                <div class="d-flex gap-4 ms-3 mt-2 flex-wrap">
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" @change="setPermission('section',section)" type="checkbox" role="switch" v-model="section.r" :id="'switchCheckSectionR'+section.label">
                                        <label class="form-check-label" :for="'switchCheckSectionR'+section.label">Leer </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" @change="setPermission('section',section)" type="checkbox" role="switch" v-model="section.w" :id="'switchCheckSectionW'+section.label">
                                        <label class="form-check-label" :for="'switchCheckSectionW'+section.label">Crear </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" @change="setPermission('section',section)" type="checkbox" role="switch" v-model="section.u" :id="'switchCheckSectionU'+section.label">
                                        <label class="form-check-label" :for="'switchCheckSectionU'+section.label">Actualizar </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" @change="setPermission('section',section)" type="checkbox" role="switch" v-model="section.d" :id="'switchCheckSectionD'+section.label">
                                        <label class="form-check-label" :for="'switchCheckSectionD'+section.label">Eliminar </label>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex gap-4 ms-5 mt-2 flex-wrap" v-for="(option,optionIndex) in section.options" :key="option.id">
                                    <span>{{option.name}}</span>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" type="checkbox" role="switch" v-model="option.r" :id="'switchCheckOptionR'+option.label">
                                        <label class="form-check-label" :for="'switchCheckOptionR'+option.label">Leer </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" type="checkbox" role="switch" v-model="option.w" :id="'switchCheckOptionW'+option.label">
                                        <label class="form-check-label" :for="'switchCheckOptionW'+option.label">Crear </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" type="checkbox" role="switch" v-model="option.u" :id="'switchCheckOptionU'+option.label">
                                        <label class="form-check-label" :for="'switchCheckOptionU'+option.label">Actualizar </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" type="checkbox" role="switch" v-model="option.d" :id="'switchCheckOptionD'+option.label">
                                        <label class="form-check-label" :for="'switchCheckOptionD'+option.label">Eliminar </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
            <input class="form-check-input" type="checkbox" role="switch">
        </div> -->
    </template>
    <template #footer>
        <app-button icon="save" @click="savePermissions()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>