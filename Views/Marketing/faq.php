<?php 
    headerAdmin($data);
    getModal("Marketing/modalFaq");
?>

<div class="mt-3">
    <div class="row">
        <div class="col-md-2">
            <div class="mb-3">
                <label for="intPorPagina" class="form-label">Por página</label>
                <select class="form-control" aria-label="Default select example" id="intPorPagina" v-model="common.intPerPage" @change="search(1,'faq')">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </select>
            </div>
        </div>
        <div class="col-md-10">
            <div class="mb-3">
                <label for="strBuscar" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="strBuscar" v-model="common.strSearch" @keyup="search(1,'faq')">
            </div>
        </div> 
    </div>
    <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
        <table class="table align-middle table-hover">
            <thead>
                <tr class="text-center">
                    <th>ID</th>
                    <th>Pregunta</th>
                    <th>Respuesta</th>
                    <th>Sección</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data,index) in common.arrData" :key="index">
                    <td data-title="ID" class="text-center">{{data.id}}</td>
                    <td data-title="Pregunta">{{data.question}}</td>
                    <td data-title="Respuesta">{{data.answer}}</td>
                    <td data-title="Sección">{{data.section}}</td>
                    <td data-title="Estado" class="text-center">
                        <span :class="data.status == '1' ? 'bg-success' : 'bg-danger'" class="badge text-white">
                            {{ data.status == '1' ? "Activo" : "Inactivo" }}
                        </span>
                    </td>
                    <td data-title="Opciones">
                        <div class="d-flex justify-content-center">
                            <?php if($_SESSION['permitsModule']['u']){ ?>
                            <button class="btn btn-success m-1" type="button" title="Editar"  @click="edit(data.id,'faq')" ><i class="fas fa-pencil-alt"></i></button>
                            <?php } ?>
                            <?php if($_SESSION['permitsModule']['d']){ ?>
                            <button class="btn btn-danger m-1" type="button" title="Eliminar" @click="del(data.id,'faq')" ><i class="fas fa-trash-alt"></i></button>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<app-pagination :common="common" @search="search"></app-pagination>

<?php footerAdmin($data)?>