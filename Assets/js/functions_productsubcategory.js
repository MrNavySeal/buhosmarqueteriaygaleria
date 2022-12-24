'use strict';

let search = document.querySelector("#search");
let sort = document.querySelector("#sortBy");
let element = document.querySelector("#listItem");

search.addEventListener('input',function() {
    request(base_url+"/inventario/searchs/"+search.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

sort.addEventListener("change",function(){
    request(base_url+"/inventario/sorts/"+sort.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        addItem();
    });
}

element.addEventListener("click",function(e) {
    let element = e.target;
    let id = element.getAttribute("data-id");
    if(element.name == "btnDelete"){
        deleteItem(id);
    }else if(element.name == "btnEdit"){
        editItem(id);
    }
});

function addItem(){
    let modalItem = document.querySelector("#modalItem");
    let modal= `
    <div class="modal fade" id="modalElement">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Nueva subcategoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formItem" name="formItem" class="mb-4">
                        <input type="hidden" id="idSubCategory" name="idSubCategory">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtName" name="txtName" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="categoryList" class="form-label">Categoría <span class="text-dange/Category/r">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required></select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fas fa-plus-circle"></i> Agregar</button>
                            <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    `;

    modalItem.innerHTML = modal;
    let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));
    let url = base_url+"/inventario/getSelectCategories"
    request(url,"","get").then(function(objData){
        document.querySelector("#categoryList").innerHTML = objData.data;
    });
    modalView.show();

    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();

        let strName = document.querySelector("#txtName").value;
        let idSubCategory = document.querySelector("#idSubCategory").value;
        let idCategory = document.querySelector("#categoryList").value;

        if(strName == "" || idCategory == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        url = base_url+"/inventario/setSubCategory";
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(url,formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-plus-circle"></i> Agregar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Agregado",objData.msg,"success");
                form.reset();
                element.innerHTML = objData.data;
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
function editItem(id){
    let url = base_url+"/inventario/getSubCategory";
    let formData = new FormData();
    formData.append("idSubCategory",id);
    request(url,formData,"post").then(function(objData){
        let modalItem = document.querySelector("#modalItem");
        let modal= `
        <div class="modal fade" id="modalElement">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Actualizar subcategoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formItem" name="formItem" class="mb-4">
                            <input type="hidden" id="idSubCategory" name="idSubCategory" value="${objData.data.idsubcategory}">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtName" name="txtName" value="${objData.data.name}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="categoryList" class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required></select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="btnAdd">Actualizar</button>
                                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        `;

        modalItem.innerHTML = modal;
        let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));

        url = base_url+"/inventario/getSelectCategories";
        request(url,"","get").then(function(html){
            document.querySelector("#categoryList").innerHTML = html.data;
            let categories = document.querySelectorAll("#categoryList option");
            for (let i = 0; i < categories.length; i++) {
                if(categories[i].value == objData.data.categoryid){
                    categories[i].setAttribute("selected",true);
                }
            }
        });
        modalView.show();

        let form = document.querySelector("#formItem");
        form.addEventListener("submit",function(e){
            e.preventDefault();

            let strName = document.querySelector("#txtName").value;
            let idSubCategory = document.querySelector("#idSubCategory").value;
            let idCategory = document.querySelector("#categoryList").value;

            if(strName == "" || idCategory == ""){
                Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
                return false;
            }
            
            url = base_url+"/inventario/setSubCategory";
            let formData = new FormData(form);
            let btnAdd = document.querySelector("#btnAdd");
            btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            btnAdd.setAttribute("disabled","");

            request(url,formData,"post").then(function(objData){
                btnAdd.innerHTML=`Actualizar`;
                btnAdd.removeAttribute("disabled");
                if(objData.status){
                    Swal.fire("Actualizado",objData.msg,"success");
                    modalView.hide();
                    element.innerHTML = objData.data;
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        })
    });
}
function deleteItem(id){
    Swal.fire({
        title:"¿Estás seguro de eliminarlo?",
        text:"Se eliminará para siempre...",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Sí, eliminar",
        cancelButtonText:"No, cancelar"
    }).then(function(result){
        if(result.isConfirmed){
            let url = base_url+"/inventario/delSubCategory"
            let formData = new FormData();
            formData.append("idSubCategory",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    element.innerHTML = objData.data;
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}
