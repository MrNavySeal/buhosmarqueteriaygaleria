<app-modal title="Buscar cuentas contables" id="modalCatalogoPuc" v-model="cuentas.showModal" size="lg">
    <template #body>
        <app-input label="Buscar" @input="search(1,'cuentas')" v-model="cuentas.strSearch"></app-input>
        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
            <div class="accordion" id="accordionClase">
                <div class="accordion-item" v-for="(clase,i) in arrCuentas" :key="i">
                    <h2 class="accordion-header mb-2">
                        <div class="d-flex gap-2">
                            <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+clase.code" aria-expanded="true" :aria-controls="'collapseOne'+clase.code">
                                <p class="m-0" :class="clase.status == 2 ? 'text-danger' : ''">{{clase.code}}</p>
                                <p class="m-0" :class="clase.status == 2 ? 'text-danger' : ''">{{clase.name}}</p>
                            </button>
                            <app-button  icon="new" btn="primary" @click="selectItem(clase,'cuentas')"></app-button>
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
                                            <app-button  icon="new" btn="primary" @click="selectItem(grupo,'cuentas')"></app-button>
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
                                                            <app-button  icon="new" btn="primary" @click="selectItem(cuenta,'cuentas')"></app-button>
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
                                                                            <app-button  icon="new" btn="primary" @click="selectItem(subcuenta,'cuentas')"></app-button>
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
                                                                                    <app-button  icon="new" btn="primary" @click="selectItem(auxiliar,'cuentas')"></app-button>
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
    </template>
    <template #footer></template>
</app-modal>