<app-modal :title="common.modulesTitle" id="modalModules" v-model="common.showModalModule" size="lg">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Código</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(parent,k) in objAccount.parents" :key="k">
                    <td>{{parent.type}}</td>
                    <td>{{parent.code}}</td>
                    <td>{{parent.name}}</td>
                </tr>
                <tr>
                    <td>{{objAccount.type}}</td>
                    <td> 
                        <div class="d-flex align-items-center gap-2 w-100">
                            <p class="text-end m-0">{{objAccount.parent_code}}</p>
                            <app-input type="text" class="mb-0 w-100" v-model="objAccount.code" :errors="errors.code"></app-input>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex w-100">
                            <app-input type="text" class="mb-0 w-100" v-model="objAccount.name" :errors="errors.name"></app-input>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <app-select label="Estado"  v-model="common.intStatus">
                <option value="1">Activo</option>
                <option value="2">Inactivo</option>
            </app-select>
        </div>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>