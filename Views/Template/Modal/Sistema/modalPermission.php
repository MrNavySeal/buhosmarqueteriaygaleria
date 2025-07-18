<app-modal :title="'Permisos '+strRol" id="showPermissionModal" v-model="showPermissionModal" size="xl" scroll="true">
    <input type="hidden" v-model="intIdRol">
    <template #body>
        <div class="d-flex gap-4 ms-3 mb-2">
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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#flush-module-'+module.name" aria-expanded="false" :aria-controls="'flush-module-'+module.name">
                        {{module.name}}
                    </button>
                </h2>
                <div :id="'flush-module-'+module.name" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <div class="d-flex gap-4">
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" @change="setPermission('module',module)" type="checkbox" role="switch" v-model="module.r" :id="'switchCheckModuleR'+module.name">
                                <label class="form-check-label" :for="'switchCheckModuleR'+module.name">Leer </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" @change="setPermission('module',module)" type="checkbox" role="switch" v-model="module.w" :id="'switchCheckModuleW'+module.name">
                                <label class="form-check-label" :for="'switchCheckModuleW'+module.name">Crear </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" @change="setPermission('module',module)" type="checkbox" role="switch" v-model="module.u" :id="'switchCheckModuleU'+module.name">
                                <label class="form-check-label" :for="'switchCheckModuleU'+module.name">Actualizar </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" @change="setPermission('module',module)" type="checkbox" role="switch" v-model="module.d" :id="'switchCheckModuleD'+module.name">
                                <label class="form-check-label" :for="'switchCheckModuleD'+module.name">Eliminar </label>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex gap-4 mt-2" v-for="(option,optionIndex) in module.options" :key="option.id">
                            <span>{{option.name}}</span>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" type="checkbox" role="switch" v-model="option.r" :id="'switchCheckOptionR'+option.name">
                                <label class="form-check-label" :for="'switchCheckOptionR'+option.name">Leer </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" type="checkbox" role="switch" v-model="option.w" :id="'switchCheckOptionW'+option.name">
                                <label class="form-check-label" :for="'switchCheckOptionW'+option.name">Crear </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" type="checkbox" role="switch" v-model="option.u" :id="'switchCheckOptionU'+option.name">
                                <label class="form-check-label" :for="'switchCheckOptionU'+option.name">Actualizar </label>
                            </div>
                            <div class="form-check form-switch text-normal">
                                <input class="form-check-input" type="checkbox" role="switch" v-model="option.d" :id="'switchCheckOptionD'+option.name">
                                <label class="form-check-label" :for="'switchCheckOptionD'+option.name">Eliminar </label>
                            </div>
                        </div>
                        <div class="accordion accordion-flush mt-2" :id="'accordionFlushSection'+section.name" v-for="(section,sectionIndex) in module.sections" :key="section.id">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#flush-section-'+section.name" aria-expanded="false" :aria-controls="'flush-section-'+section.name">
                                    {{section.name}}
                                </button>
                            </h2>
                            <div :id="'flush-section-'+section.name" class="accordion-collapse collapse" :data-bs-parent="'#accordionFlushSection'+section.name">
                                <div class="d-flex gap-4 ms-3 mt-2">
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" @change="setPermission('section',section)" type="checkbox" role="switch" v-model="section.r" :id="'switchCheckSectionR'+section.name">
                                        <label class="form-check-label" :for="'switchCheckSectionR'+section.name">Leer </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" @change="setPermission('section',section)" type="checkbox" role="switch" v-model="section.w" :id="'switchCheckSectionW'+section.name">
                                        <label class="form-check-label" :for="'switchCheckSectionW'+section.name">Crear </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" @change="setPermission('section',section)" type="checkbox" role="switch" v-model="section.u" :id="'switchCheckSectionU'+section.name">
                                        <label class="form-check-label" :for="'switchCheckSectionU'+section.name">Actualizar </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" @change="setPermission('section',section)" type="checkbox" role="switch" v-model="section.d" :id="'switchCheckSectionD'+section.name">
                                        <label class="form-check-label" :for="'switchCheckSectionD'+section.name">Eliminar </label>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex gap-4 ms-5 mt-2" v-for="(option,optionIndex) in section.options" :key="option.id">
                                    <span>{{option.name}}</span>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" type="checkbox" role="switch" v-model="option.r" :id="'switchCheckOptionR'+option.name">
                                        <label class="form-check-label" :for="'switchCheckOptionR'+option.name">Leer </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" type="checkbox" role="switch" v-model="option.w" :id="'switchCheckOptionW'+option.name">
                                        <label class="form-check-label" :for="'switchCheckOptionW'+option.name">Crear </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" type="checkbox" role="switch" v-model="option.u" :id="'switchCheckOptionU'+option.name">
                                        <label class="form-check-label" :for="'switchCheckOptionU'+option.name">Actualizar </label>
                                    </div>
                                    <div class="form-check form-switch text-normal">
                                        <input class="form-check-input" type="checkbox" role="switch" v-model="option.d" :id="'switchCheckOptionD'+option.name">
                                        <label class="form-check-label" :for="'switchCheckOptionD'+option.name">Eliminar </label>
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