<app-modal :title="common.modulesTitle" id="modalModules" v-model="common.showModalModule" size="lg">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="mb-3 uploadImg">
            <img :src="strImgUrl">
            <label for="strImagen"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
            <input class="d-none" type="file" id="strImagen" @change="uploadImagen"  accept="image/*"> 
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Nombre" type="text" v-model="strNombre" required="true"></app-input>
            </div>
            <div class="col-md-6">
                <app-input label="Apellido" type="text" v-model="strApellido"></app-input>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Nro documento" type="text" v-model="strDocumento"></app-input>
            </div>
            <div class="col-md-6">
                <app-input label="Correo" type="email" v-model="strCorreo"></app-input>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <app-select label="Países"  v-model="intPais" @change="setFiltro('paises')">
                    <option v-for="(data,index) in arrPaises" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
            <div class="col-md-4">
                <app-select label="Estado/departamento"  v-model="intDepartamento" @change="setFiltro('departamentos')">
                    <option v-for="(data,index) in arrDepartamentos" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
            <div class="col-md-4">
                <app-select label="Ciudad"  v-model="intCiudad">
                    <option v-for="(data,index) in arrCiudades" :value="data.id">{{data.name}}</option>
                </app-select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Teléfono" type="phone" v-model="strTelefono" required="true"></app-input>
            </div>
            <div class="col-md-6">
                <app-input label="Dirección" type="text" v-model="strDireccion"></app-input>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-input label="Contraseña" type="password" v-model="strContrasena"></app-input>
            </div>
            <div class="col-md-6">
                <app-select label="Estado"  v-model="intEstado">
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