<app-modal title="Buscar categorías" id="modalCategory" v-model="pagination.showModalPaginationCategory" size="lg">
    <template #body>
        <div class="row">
            <div class="col-md-4">
                <app-select label="Por página"  @change="search()" v-model="pagination.intPerPage">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </app-select>
            </div>
            <div class="col-md-8">
                <app-input label="Buscar" @input="search()" v-model="pagination.strSearch"></app-input>
            </div>
        </div>
        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data,index) in pagination.arrData" :key="index">
                        <td data-title="Id">{{data.id}}</td>
                        <td data-title="Portada">
                            <img :src="data.url" :alt="data.name" class="img-thumbnail" style="width: 50px; height: 50px;">
                        </td>
                        <td data-title="Nombre">{{data.name}}</td>
                        <td data-title="Opciones">
                            <div class="d-flex gap-2">
                                <app-button  icon="new" btn="primary" @click="objCategory=data;pagination.showModalPaginationCategory=false"></app-button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <app-pagination :common="pagination" @search="search"></app-pagination>
    </template>
    <template #footer></template>
</app-modal>