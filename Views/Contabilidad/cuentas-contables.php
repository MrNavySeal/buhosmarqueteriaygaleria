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
    <h2 class="accordion-header">
      <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+clase.code" aria-expanded="true" :aria-controls="'collapseOne'+clase.code">
          <app-button  icon="new" btn="primary" @click="openModal(clase)"></app-button>
          <app-button  icon="edit" btn="success" @click="openModal(clase,'edit')"></app-button>
          <p class="m-0">{{clase.code}}</p>
          <p class="m-0">{{clase.name}}</p>
      </button>
    </h2>
    <div :id="'collapseOne'+clase.code" class="accordion-collapse collapse show" data-bs-parent="#accordionClase">
      <div class="accordion-body">
            <div class="accordion" :id="'accordion'+clase.code">
                <div class="accordion-item" v-for="(grupo,k) in clase.children" :key="k">
                    <h2 class="accordion-header">
                    <button class="accordion-button d-flex gap-2" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapseOne'+grupo.code" aria-expanded="true" :aria-controls="'collapseOne'+grupo.code">
                        <app-button  icon="new" btn="primary" @click="openModal(grupo)"></app-button>
                        <app-button  icon="edit" btn="success" @click="openModal(grupo,'edit')"></app-button>
                        <p class="m-0">{{grupo.code}}</p>
                        <p class="m-0">{{grupo.name}}</p>
                    </button>
                    </h2>
                    <div :id="'collapseOne'+grupo.code" class="accordion-collapse collapse show" :data-bs-parent="'#accordion'+grupo.code">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>
                </div>
      </div>
    </div>
  </div>
</div>
<?php footerAdmin($data)?>         