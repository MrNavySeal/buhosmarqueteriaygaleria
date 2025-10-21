'use strict';
let element = document.querySelector("#listItem");
function addItem(){
    let modalItem = document.querySelector("#modalItem");
    let modal= `
    <div class="modal fade" id="modalElement">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Nuevo Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formItem" name="formItem" class="mb-4">
                        <input type="hidden" id="idBanner" name="idBanner">
                        <div class="mb-3 uploadImg">
                            <img src="${base_url}/Assets/images/uploads/category.jpg">
                            <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                            <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                        </div>
                        <div class="mb-3">
                            <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="txtName" name="txtName" required>
                        </div>
                        <div class="mb-3">
                            <label for="txtDescription" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="txtDescription" name="txtDescription" value="">
                        </div>
                        <div class="mb-3">
                            <label for="txtBtn" class="form-label">Texto botón</label>
                            <input type="text" class="form-control" id="txtBtn" name="txtBtn" value="">
                        </div>
                        <div class="mb-3">
                            <label for="txtLink" class="form-label">Enlace <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="txtLink" name="txtLink" value="" required>
                        </div>
                        <div class="mb-3">
                            <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
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

    let img = document.querySelector("#txtImg");
    let imgLocation = ".uploadImg img";
    img.addEventListener("change",function(){
        uploadImg(img,imgLocation);
    });

    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();

        let strName = document.querySelector("#txtName").value;

        if(strName == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        let url = base_url+"/Configuracion/Banners/setBanner";
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
                modalView.hide();
                form.reset();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
function editItem(id){
    let url = base_url+"/Configuracion/Banners/getBanner";
    let formData = new FormData();
    formData.append("idBanner",id);
    request(url,formData,"post").then(function(objData){
        let modalItem = document.querySelector("#modalItem");
        let modal= `
        <div class="modal fade" id="modalElement">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Actualizar Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formItem" name="formItem" class="mb-4">
                            <input type="hidden" id="idBanner" name="idBanner" value="${objData.data.id_banner}">
                            <div class="mb-3 uploadImg">
                                <img src="${objData.data.picture}">
                                <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                                <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                            </div>
                            <div class="mb-3">
                                <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtName" name="txtName" value="${objData.data.name}" required>
                            </div>
                            <div class="mb-3">
                                <label for="txtDescription" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="txtDescription" name="txtDescription" value="${objData.data.description}">
                            </div>
                            <div class="mb-3">
                                <label for="txtBtn" class="form-label">Texto botón</label>
                                <input type="text" class="form-control" id="txtBtn" name="txtBtn" value="${objData.data.button}">
                            </div>
                            <div class="mb-3">
                                <label for="txtLink" class="form-label">Enlace <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtLink" name="txtLink" value="${objData.data.link}" required>
                            </div>
                            <div class="mb-3">
                                <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
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
        let status = document.querySelectorAll("#statusList option");
        for (let i = 0; i < status.length; i++) {
            if(status[i].value == objData.data.status){
                status[i].setAttribute("selected",true);
            }
        }
        modalView.show();

        let img = document.querySelector("#txtImg");
        let imgLocation = ".uploadImg img";
        img.addEventListener("change",function(){
            uploadImg(img,imgLocation);
        });

        let form = document.querySelector("#formItem");
        form.addEventListener("submit",function(e){
            e.preventDefault();

            let strName = document.querySelector("#txtName").value;
            let idCategory = document.querySelector("#idBanner").value;

            if(strName == ""){
                Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
                return false;
            }
            
            let url = base_url+"/Configuracion/Banners/setBanner";
            let formData = new FormData(form);
            let btnAdd = document.querySelector("#btnAdd");

            btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            btnAdd.setAttribute("disabled","");

            request(url,formData,"post").then(function(objData){
                btnAdd.removeAttribute("disabled");
                btnAdd.innerHTML="Actualizar";
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
            let url = base_url+"/Configuracion/Banners/delBanner"
            let formData = new FormData();
            formData.append("idBanner",id);
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
