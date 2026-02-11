<app-modal :title="common.title" id="modalSucursales" v-model="common.showModal" size="lg">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <app-input label="Nombre" type="text" v-model="strNombre" required="true" :errors="errores.nombre"></app-input>
        <div class="row">
            <div class="col-md-4">
                <app-select label="Países"  v-model="intPais" @change="setFiltro('paises')" required="true" :errors="errores.pais">
                    <option v-for="(data,index) in arrPaises" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
            <div class="col-md-4">
                <app-select label="Estado/departamento"  v-model="intDepartamento" @change="setFiltro('departamentos')" required="true" :errors="errores.departamento">
                    <option v-for="(data,index) in arrDepartamentos" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
            <div class="col-md-4">
                <app-select label="Ciudad"  v-model="intCiudad" required="true" :errors="errores.ciudad">
                    <option v-for="(data,index) in arrCiudades" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Teléfono" type="phone" v-model="strTelefono" ></app-input>
            </div>
            <div class="col-md-6">
                <app-input label="Dirección" type="text" v-model="strDireccion"></app-input>
            </div>
        </div>
        <app-select label="Estado"  v-model="intEstado">
            <option value="1">Activo</option>
            <option value="2">Inactivo</option>
        </app-select>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>