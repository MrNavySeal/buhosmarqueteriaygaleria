<app-modal title="Buscar subcategorías" id="modalSubcategory" v-model="subcategory.showModalPaginationSubcategory" size="lg">
    <template #body>
        <div class="row">
            <div class="col-md-4">
                <app-select label="Por página"  @change="search()" v-model="subcategory.intPerPage">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </app-select>
            </div>
            <div class="col-md-8">
                <app-input label="Buscar" @input="search()" v-model="subcategory.strSearch"></app-input>
            </div>
        </div>
        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data,index) in subcategory.arrData" :key="index">
                        <td data-title="Id">{{data.id}}</td>
                        <td data-title="Nombre">{{data.name}}</td>
                        <td data-title="Opciones">
                            <div class="d-flex gap-2">
                                <app-button  icon="new" btn="primary" @click="selectItem(data,'subcategory')"></app-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <app-pagination :common="subcategory" @search="search"></app-pagination>
    </template>
    <template #footer></template>
</app-modal>