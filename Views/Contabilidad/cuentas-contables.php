<?php  headerAdmin($data); getModal("Contabilidad/modalCuentaContable");  ?>
<div class="row">
    <div class="col-md-4">
        <app-select label="Por página"  @change="search()" v-model="common.intPerPage">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="1000">1000</option>
        </app-select>
    </div>
    <div class="col-md-8">
        <app-input label="Buscar" @input="search()" v-model="common.strSearch"></app-input>
    </div>
</div>
<div class="accordion" id="accordionClase">
  <div class="accordion-item" v-for="(clase,i) in arrAccounts" :key="i">
    <h2 class="accordion-header mb-2">
        <div class="d-flex gap-2">
            <app-button  icon="new" btn="primary" @click="openModal(clase)"></app-button>
            <app-button  icon="edit" btn="success" @click="openModal(clase,'edit')"></app-button>
            <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+clase.code" aria-expanded="true" :aria-controls="'collapseOne'+clase.code">
                <p class="m-0" :class="clase.status == 2 ? 'text-danger' : ''">{{clase.code}}</p>
                <p class="m-0" :class="clase.status == 2 ? 'text-danger' : ''">{{clase.name}}</p>
            </button>
        </div>
    </h2>
    <div :id="'collapseOne'+clase.code" class="accordion-collapse collapse" data-bs-parent="#accordionClase">
      <div class="accordion-body">
            <div class="accordion" :id="'accordion'+clase.code">
                <div class="accordion-item" v-for="(grupo,k) in clase.children" :key="k">
                    <h2 class="accordion-header mb-2">
                        <div class="d-flex gap-2">
                            <app-button  icon="new" btn="primary" @click="openModal(grupo)"></app-button>
                            <app-button  icon="edit" btn="success" @click="openModal(grupo,'edit')"></app-button>
                            <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+grupo.code" aria-expanded="true" :aria-controls="'collapseOne'+grupo.code">
                                <p class="m-0" :class="grupo.status == 2 ? 'text-danger' : ''">{{grupo.code}}</p>
                                <p class="m-0" :class="grupo.status == 2 ? 'text-danger' : ''">{{grupo.name}}</p>
                            </button>
                        </div>
                    </h2>
                    <div :id="'collapseOne'+grupo.code" class="accordion-collapse collapse show" :data-bs-parent="'#accordion'+grupo.code">
                        <div class="accordion-body">
                            <div class="accordion" :id="'accordion'+grupo.code">
                                <div class="accordion-item" v-for="(cuenta,j) in grupo.children" :key="j">
                                    <h2 class="accordion-header mb-2">
                                        <div class="d-flex gap-2">
                                            <app-button  icon="new" btn="primary" @click="openModal(cuenta)"></app-button>
                                            <app-button  icon="edit" btn="success" @click="openModal(cuenta,'edit')"></app-button>
                                            <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+cuenta.code" aria-expanded="true" :aria-controls="'collapseOne'+cuenta.code">
                                                <p class="m-0" :class="cuenta.status == 2 ? 'text-danger' : ''">{{cuenta.code}}</p>
                                                <p class="m-0" :class="cuenta.status == 2 ? 'text-danger' : ''">{{cuenta.name}}</p>
                                            </button>
                                        </div>
                                    </h2>
                                    <div :id="'collapseOne'+cuenta.code" class="accordion-collapse collapse show" :data-bs-parent="'#accordion'+cuenta.code">
                                        <div class="accordion-body">
                                            <div class="accordion" :id="'accordion'+cuenta.code">
                                                <div class="accordion-item" v-for="(subcuenta,j) in cuenta.children" :key="j">
                                                    <h2 class="accordion-header mb-2">
                                                        <div class="d-flex gap-2">
                                                            <app-button  icon="new" btn="primary" @click="openModal(subcuenta)"></app-button>
                                                            <app-button  icon="edit" btn="success" @click="openModal(subcuenta,'edit')"></app-button>
                                                            <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+subcuenta.code" aria-expanded="true" :aria-controls="'collapseOne'+subcuenta.code">
                                                                <p class="m-0" :class="subcuenta.status == 2 ? 'text-danger' : ''">{{subcuenta.code}}</p>
                                                                <p class="m-0" :class="subcuenta.status == 2 ? 'text-danger' : ''">{{subcuenta.name}}</p>
                                                            </button>
                                                        </div>
                                                    </h2>
                                                    <div :id="'collapseOne'+subcuenta.code" class="accordion-collapse collapse show" :data-bs-parent="'#accordion'+subcuenta.code">
                                                        <div class="accordion-body">
                                                            <div class="d-flex gap-2 mb-2 align-items-center" v-for="(auxiliar,k) in subcuenta.children" :key="k">
                                                                <app-button  icon="edit" btn="success" @click="openModal(auxiliar,'edit')"></app-button>
                                                                <app-button  icon="delete" btn="danger" @click="del(auxiliar)"></app-button>
                                                                <p class="m-0" :class="auxiliar.status == 2 ? 'text-danger' : ''">{{auxiliar.code}}</p>
                                                                <p class="m-0" :class="auxiliar.status == 2 ? 'text-danger' : ''">{{auxiliar.name}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    </div>
  </div>
</div>
<?php footerAdmin($data)?>         