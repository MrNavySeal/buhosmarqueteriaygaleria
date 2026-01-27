<?php  headerAdmin($data); getModal("Contabilidad/modalCuentaContable");  ?>
<div class="body flex-grow-1 px-3">
    <app-input label="Buscar" @input="search()" v-model="common.strSearch"></app-input>
    <div class="accordion" id="accordionClase">
      <div class="accordion-item" v-for="(clase,i) in arrAccounts" :key="i">
        <h2 class="accordion-header mb-2">
            <div class="d-flex gap-2">
                <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+clase.code" aria-expanded="true" :aria-controls="'collapseOne'+clase.code">
                    <p class="m-0" :class="clase.status == 2 ? 'text-danger' : ''">{{clase.code}}</p>
                    <p class="m-0" :class="clase.status == 2 ? 'text-danger' : ''">{{clase.name}}</p>
                </button>
                <?php if($_SESSION['permitsModule']['w']){ ?>
                <app-button  icon="new" btn="primary" @click="openModal(clase)"></app-button>
                <?php } ?>
                <?php if($_SESSION['permitsModule']['u']){ ?>
                <app-button  icon="edit" btn="success" @click="openModal(clase,'edit')"></app-button>
                <?php } ?>
            </div>
        </h2>
        <div :id="'collapseOne'+clase.code" class="accordion-collapse collapse" data-bs-parent="#accordionClase">
          <div class="accordion-body">
                <div class="accordion" :id="'accordion'+clase.code">
                    <div class="accordion-item" v-for="(grupo,k) in clase.children" :key="k">
                        <h2 class="accordion-header mb-2">
                            <div class="d-flex gap-2">
                                <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+grupo.code" aria-expanded="true" :aria-controls="'collapseOne'+grupo.code">
                                    <p class="m-0" :class="grupo.status == 2 ? 'text-danger' : ''">{{grupo.code}}</p>
                                    <p class="m-0" :class="grupo.status == 2 ? 'text-danger' : ''">{{grupo.name}}</p>
                                </button>
                                <?php if($_SESSION['permitsModule']['w']){ ?>
                                <app-button  icon="new" btn="primary" @click="openModal(grupo)"></app-button>
                                <?php } ?>
                                <?php if($_SESSION['permitsModule']['u']){ ?>
                                <app-button  icon="edit" btn="success" @click="openModal(grupo,'edit')"></app-button>
                                <?php } ?>
                                <?php if($_SESSION['permitsModule']['d']){ ?>
                                <app-button  v-if="grupo.children.length == 0" icon="delete" btn="danger" @click="del(grupo)"></app-button>
                                <?php } ?>
                            </div>
                        </h2>
                        <div :id="'collapseOne'+grupo.code" class="accordion-collapse collapse show" :data-bs-parent="'#accordion'+clase.code">
                            <div class="accordion-body">
                                <div class="accordion" :id="'accordion'+grupo.code">
                                    <div class="accordion-item" v-for="(cuenta,j) in grupo.children" :key="j">
                                        <h2 class="accordion-header mb-2">
                                            <div class="d-flex gap-2">
                                                <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+cuenta.code" aria-expanded="true" :aria-controls="'collapseOne'+cuenta.code">
                                                    <p class="m-0" :class="cuenta.status == 2 ? 'text-danger' : ''">{{cuenta.code}}</p>
                                                    <p class="m-0" :class="cuenta.status == 2 ? 'text-danger' : ''">{{cuenta.name}}</p>
                                                </button>
                                                <?php if($_SESSION['permitsModule']['w']){ ?>
                                                <app-button  icon="new" btn="primary" @click="openModal(cuenta)"></app-button>
                                                <?php } ?>
                                                <?php if($_SESSION['permitsModule']['u']){ ?>
                                                <app-button  icon="edit" btn="success" @click="openModal(cuenta,'edit')"></app-button>
                                                <?php } ?>
                                                <?php if($_SESSION['permitsModule']['d']){ ?>
                                                <app-button  v-if="cuenta.children.length == 0" icon="delete" btn="danger" @click="del(cuenta)"></app-button>
                                                <?php } ?>
                                            </div>
                                        </h2>
                                        <div :id="'collapseOne'+cuenta.code" class="accordion-collapse collapse show" :data-bs-parent="'#accordion'+grupo.code">
                                            <div class="accordion-body">
                                                <div class="accordion" :id="'accordion'+cuenta.code">
                                                    <div class="accordion-item" v-for="(subcuenta,j) in cuenta.children" :key="j">
                                                        <h2 class="accordion-header mb-2">
                                                            <div class="d-flex gap-2">
                                                                <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+subcuenta.code" aria-expanded="true" :aria-controls="'collapseOne'+subcuenta.code">
                                                                    <p class="m-0" :class="subcuenta.status == 2 ? 'text-danger' : ''">{{subcuenta.code}}</p>
                                                                    <p class="m-0" :class="subcuenta.status == 2 ? 'text-danger' : ''">{{subcuenta.name}}</p>
                                                                </button>
                                                                <?php if($_SESSION['permitsModule']['w']){ ?>
                                                                <app-button  icon="new" btn="primary" @click="openModal(subcuenta)"></app-button>
                                                                <?php } ?>
                                                                <?php if($_SESSION['permitsModule']['u']){ ?>
                                                                <app-button  icon="edit" btn="success" @click="openModal(subcuenta,'edit')"></app-button>
                                                                <?php } ?>
                                                                <?php if($_SESSION['permitsModule']['d']){ ?>
                                                                <app-button  v-if="subcuenta.children.length == 0" icon="delete" btn="danger" @click="del(subcuenta)"></app-button>
                                                                <?php } ?>
                                                            </div>
                                                        </h2>
                                                        <div :id="'collapseOne'+subcuenta.code" class="accordion-collapse collapse show" :data-bs-parent="'#accordion'+cuenta.code">
                                                            <div class="accordion-body">
                                                                <div class="d-flex justify-content-between gap-2 mb-2 align-items-center" v-for="(auxiliar,k) in subcuenta.children" :key="k">
                                                                    <div class="d-flex gap-2">
                                                                        <p class="m-0" :class="auxiliar.status == 2 ? 'text-danger' : ''">{{auxiliar.code}}</p>
                                                                        <p class="m-0" :class="auxiliar.status == 2 ? 'text-danger' : ''">{{auxiliar.name}}</p>
                                                                    </div>
                                                                    <div class="d-flex gap-2">
                                                                        <?php if($_SESSION['permitsModule']['u']){ ?>
                                                                        <app-button  icon="edit" btn="success" @click="openModal(auxiliar,'edit')"></app-button>
                                                                        <?php } ?>
                                                                        <?php if($_SESSION['permitsModule']['d']){ ?>
                                                                        <app-button  icon="delete" btn="danger" @click="del(auxiliar)"></app-button>
                                                                        <?php } ?>
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
    </div>
</div>
<?php footerAdmin($data)?>         