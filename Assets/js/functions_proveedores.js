'use strict';


let search = document.querySelector("#search");
let sort = document.querySelector("#sortBy");
let element = document.querySelector("#listItem");

search.addEventListener('input',function() {
    request(base_url+"/compras/search/"+search.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

sort.addEventListener("change",function(){
    request(base_url+"/compras/sort/"+sort.value,"","get").then(function(objData){
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
                    <h5 class="modal-title" id="staticBackdropLabel">Nuevo proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formItem" name="formItem" class="mb-4">
                        <input type="hidden" id="idSupplier" name="idSupplier">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtNit" class="form-label">NIT </label>
                                    <input type="text" class="form-control" id="txtNit" name="txtNit">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtName" name="txtName" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtEmail" class="form-label">Correo <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="txtEmail" name="txtEmail" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="txtPhone" name="txtPhone" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="txtAddress" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="txtAddress" name="txtAddress">
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
    modalView.show();

    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();

        let strName = document.querySelector("#txtName").value;
        let strEmail = document.querySelector("#txtEmail").value;
        let strPhone = document.querySelector("#txtPhone").value;

        if(strName == "" || strEmail == "" || strPhone == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(!fntEmailValidate(strEmail)){
            Swal.fire("Error","El email es invalido","error");
            return false;
        }
        if(strPhone.length < 10){
            Swal.fire("Error","El número de teléfono debe tener al menos 10 dígitos","error");
            return false;
        }
        
        let url = base_url+"/compras/setSupplier";
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");

        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(url,formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-plus-circle"></i> Agregar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Agregado",objData.msg,"success");
                element.innerHTML = objData.data;
                form.reset();
                modalView.hide();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
function editItem(id){
    let url = base_url+"/compras/getSupplier";
    let formData = new FormData();
    formData.append("idSupplier",id);
    request(url,formData,"post").then(function(objData){
        
        let modalItem = document.querySelector("#modalItem");
        let modal= `
        <div class="modal fade" id="modalElement">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Actualizar proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formItem" name="formItem" class="mb-4">
                            <input type="hidden" id="idSupplier" name="idSupplier" value="${objData.data.idsupplier}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txtNit" class="form-label">NIT </label>
                                        <input type="text" class="form-control" id="txtNit" name="txtNit" value="${objData.data.nit}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="txtName" name="txtName" value="${objData.data.name}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txtEmail" class="form-label">Correo <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="${objData.data.email}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txtPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="txtPhone" name="txtPhone" value="${objData.data.phone}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="txtAddress" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="txtAddress" name="txtAddress" value="${objData.data.address}">
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
        modalView.show();

        let form = document.querySelector("#formItem");
        form.addEventListener("submit",function(e){
            e.preventDefault();

            let strName = document.querySelector("#txtName").value;
            let strEmail = document.querySelector("#txtEmail").value;
            let strPhone = document.querySelector("#txtPhone").value;

            if(strName == "" || strEmail == "" || strPhone == ""){
                Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
                return false;
            }
            if(!fntEmailValidate(strEmail)){
                Swal.fire("Error","El email es invalido","error");
                return false;
            }
            if(strPhone.length < 10){
                Swal.fire("Error","El número de teléfono debe tener al menos 10 dígitos","error");
                return false;
            }
            
            let url = base_url+"/compras/setSupplier";
            let formData = new FormData(form);
            let btnAdd = document.querySelector("#btnAdd");

            btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            btnAdd.setAttribute("disabled","");

            request(url,formData,"post").then(function(objData){
                btnAdd.innerHTML=`Actualizar`;
                btnAdd.removeAttribute("disabled");
                if(objData.status){
                    Swal.fire("Actualizado",objData.msg,"success");
                    element.innerHTML = objData.data;
                    form.reset();
                    modalView.hide();
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
            let url = base_url+"/compras/delSupplier"
            let formData = new FormData();
            formData.append("idSupplier",id);
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
