<?php 
    headerAdmin($data);
    getModal("Productos/modalProducto");
    getModal("Productos/modalProductoVer");
    getModal("Paginacion/modalPaginacionCategorias");
    getModal("Paginacion/modalPaginacionSubcategorias");
?>
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
        <app-button-input 
            title="Buscar"
            btn="primary"
            v-model="common.strSearch"
            required="false"
            @input="subcategory.modalType='';category.modalType=''"
            >
            <template #right>
                <button class="btn btn-primary" @click="search()" id="btnGenerate">Generar</button>
            </template>
        </app-button-input>
    </div>
</div>
<div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
    <table class="table align-middle table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Portada</th>
                <th>Nombre</th>
                <th>Referencia</th>
                <th>Categoria</th>
                <th>Subcategoria</th>
                <th class="text-nowrap">Precio de compra</th>
                <th class="text-nowrap">Precio de venta</th>
                <th class="text-nowrap">Precio de oferta</th>
                <th>Stock</th>
                <th>Producto</th>
                <th>Insumo</th>
                <th class="text-nowrap">Servicio/Receta/Combo</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data,index) in common.arrData" :key="index">
                <td data-title="Id">{{data.idproduct}}</td>
                <td data-title="Portada">
                    <img :src="data.image" :alt="data.name" class="img-thumbnail" style="width: 50px; height: 50px;">
                </td>
                <td data-title="Nombre">{{data.name}}</td>
                <td data-title="Referencia">{{data.reference}}</td>
                <td data-title="Categoría">{{data.category}}</td>
                <td data-title="Subcategoría">{{data.subcategory}}</td>
                <td data-title="Precio compra" class="text-center">{{data.product_type == 1 ? "Desde" : ""}} {{data.price_purchase}}</td>
                <td data-title="Precio venta" class="text-end">{{data.product_type == 1 ? "Desde" : ""}} {{data.price_sell}}</td>
                <td data-title="Precio oferta" class="text-end">{{data.product_type == 1 ? "Desde" : ""}} {{data.price_discount}}</td>
                <td data-title="Stock" class="text-center">{{data.is_stock ? data.stock : "N/A"}}</td>
                <td data-title="Producto" class="text-center">{{data.is_product ? "Si" : "No"}}</td>
                <td data-title="Insumo" class="text-center">{{data.is_ingredient ? "Si" : "No"}}</td>
                <td data-title="Servicio/Receta/Combo" class="text-center">{{data.is_combo ? "Si" : "No" }}</td>
                <td data-title="Fecha" class="text-center">{{data.date}}</td>
                <td data-title="Estado" class="text-center">
                    <span :class="data.status == '1' ? 'bg-success' : 'bg-danger'" class="badge text-white">
                        {{ data.status == '1' ? "Activo" : "Inactivo" }}
                    </span>
                </td>
                <td data-title="Opciones">
                    <div class="d-flex gap-2">
                        <app-button  v-if="(data.is_product || data.is_combo) && data.visible_category"  icon="globe" btn="primary" @click="view(data)"></app-button>
                        <?php if($_SESSION['permitsModule']['r']){ ?>
                        <app-button  icon="watch" btn="info" @click="edit(data,'view')"></app-button>
                        <?php } ?>
                        <?php if($_SESSION['permitsModule']['u']){ ?>
                        <app-button  icon="list" btn="warning"></app-button>
                        <app-button  icon="edit" btn="success" @click="edit(data,'edit')"></app-button>
                        <?php } ?>
                        <?php if($_SESSION['permitsModule']['d']){ ?>
                        <app-button  icon="delete" btn="danger" @click="del(data)"></app-button>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<app-pagination :common="common" @search="search"></app-pagination>
<?php footerAdmin($data)?>  