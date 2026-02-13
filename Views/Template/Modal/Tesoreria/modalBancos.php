<app-modal :title="common.title" id="modalBancos" v-model="common.showModal" size="lg">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <div class="row">
            <div class="col-md-6">
                <app-button-input title="Tercero"
                btn="primary" icon="new" 
                v-model="objTercero.nombre" 
                disabled
                required="true"
                :errors="errores.tercero"
                >
                    <template #left>
                        <app-button icon="search" btn="primary" @click="terceros.showModal = true;search(1,'terceros');"></app-button>
                    </template>
                </app-button-input>
            </div>
            <div class="col-md-4">
                <app-input label="Cuenta bancaria" type="text" v-model="strCuenta" :errors="errores.cuenta_banco" required="true"></app-input>
            </div>
            <div class="col-md-2">
                <app-select label="Tipo"  v-model="strTipo" :errors="errores.tipo" required="true">
                    <option value="ahorro">Ahorro</option>
                    <option value="corriente">Corriente</option>
                </app-select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <app-button-input title="Cuenta contable"
                btn="primary" icon="new" 
                v-model="objCuenta.name" 
                disabled
                required="true"
                :errors="errores.cuenta"
                >
                    <template #left>
                        <app-button icon="search" btn="primary" @click="cuentas.showModal = true;search(1,'cuentas');"></app-button>
                    </template>
                </app-button-input>
            </div>
            <div class="col-md-6">
                <app-select label="Estado"  v-model="intEstado" :errors="errores.estado">
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