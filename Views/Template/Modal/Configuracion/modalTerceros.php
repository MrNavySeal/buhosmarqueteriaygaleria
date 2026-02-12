<app-modal :title="common.modulesTitle" id="modalModules" v-model="common.showModalModule" size="xl">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <ul class="nav nav-pills mt-5 mb-5" id="product-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Datos básicos</button>
            </li>
            <!-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab" aria-controls="social" aria-selected="false">Editar</button>
            </li> -->
        </ul>
        <div class="tab-content mb-3" id="myTabContent">
            <div class="tab-pane show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <div class="mb-3 uploadImg">
                    <img :src="strImgUrl">
                    <label for="strImagen"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                    <input class="d-none" type="file" id="strImagen" @change="uploadImagen"  accept="image/*"> 
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                                <app-input label="Fecha" type="date" v-model="strFecha"></app-input>
                            </div>
                            <div class="col-md-4">
                                <app-select label="Tipo de persona"  v-model="intTipoPersona" :errors="errores.tipo_persona" required="true">
                                    <option v-for="(data,index) in arrTipoPersona" :value="data.id">{{data.name}}</option>
                                </app-select>
                            </div>
                            <div class="col-md-4">
                                <app-select label="Régimen"  v-model="intTipoRegimen" :errors="errores.tipo_regimen" required="true">
                                    <option v-for="(data,index) in arrTipoRegimen" :value="data.id">{{data.name}}</option>
                                </app-select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <app-select label="Tipo documento"  v-model="intTipoDocumento" :errors="errores.tipo_documento" required="true">
                                    <option v-for="(data,index) in arrTipoDocumento" :value="data.id">{{data.name}}</option>
                                </app-select>
                            </div>
                            <div class="col-md-6">
                                <app-input label="Documento" type="text" v-model="strDocumento"></app-input>
                            </div>
                            <div class="col-md-2">
                                <app-input label="DV" type="number" v-model="strDv"></app-input>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <app-select label="Países"  v-model="intPais" @change="setFiltro('paises')" :errors="errores.pais" required="true">
                                    <option v-for="(data,index) in arrPaises" :value="data.id">{{data.name}}</option>
                                </app-select>
                            </div>
                            <div class="col-md-4">
                                <app-select label="Departamento"  v-model="intDepartamento" :errors="errores.departamento" @change="setFiltro('departamentos')" required="true">
                                    <option v-for="(data,index) in arrDepartamentos" :value="data.id">{{data.name}}</option>
                                </app-select>
                            </div>
                            <div class="col-md-4">
                                <app-select label="Ciudad"  v-model="intCiudad" :errors="errores.ciudad" required="true">
                                    <option v-for="(data,index) in arrCiudades" :value="data.id">{{data.name}}</option>
                                </app-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row" v-if="intTipoPersona != 1">
                            <div class="col-md-6">
                                <app-input label="Nombre" type="text" v-model="strNombre"  :errors="errores.nombre" required="true"></app-input>
                            </div>
                            <div class="col-md-6">
                                <app-input label="Apellido" type="text" v-model="strApellido"></app-input>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-md-12">
                                <app-input label="Razón social" type="text" v-model="strNombre" :errors="errores.nombre" required="true"></app-input>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <app-input label="Correo" type="email" v-model="strCorreo"></app-input>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <app-input label="Indicativo" type="phone" v-model="intTelefonoCodigo" disabled="disabled"></app-input>
                            </div>
                            <div class="col-md-9">
                                <app-input label="Teléfono" type="phone" v-model="strTelefono"></app-input>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <app-input label="Dirección" type="text" v-model="strDireccion"></app-input>
                    </div>
                </div>
                <div class="row">
                    <h5>Tipo de tercero</h5>
                    <p class="text-secondary">Puede seleccionar uno o varios</p>
                    <div class="col-md-3">
                        <app-input label="checkCliente"  title="Seleccione si es un cliente" type="switch" v-model="intCheckCliente"></app-input>
                    </div>
                    <div class="col-md-3">
                        <app-input label="checkProveedor"  title="Seleccione si es un proveedor" type="switch" v-model="intCheckProveedor"></app-input>
                    </div>
                    <div class="col-md-3">
                        <app-input label="checkUsuario"  title="Seleccione si es un usuario" type="switch" v-model="intCheckUsuario"></app-input>
                    </div>
                    <div class="col-md-3">
                        <app-input label="checkOtro"  title="Seleccione si es otra entidad" type="switch" v-model="intCheckOtro"></app-input>
                    </div>
                </div>
                <ul class="m-0">
                    <li class="text-danger" v-for="(data,index) in errores.tipo_tercero">{{data}}<br></li>
                </ul>
                <app-select label="Estado"  v-model="intEstado">
                    <option value="1">Activo</option>
                    <option value="2">Inactivo</option>
                </app-select>
                <?php if($_SESSION['userData']['roleid'] == 1){ ?>
                <app-input label="Contraseña" type="password" v-model="strContrasena"></app-input>
                <?php } ?>
            </div>
            <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
            </div>
        </div>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>