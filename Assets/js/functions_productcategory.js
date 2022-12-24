'use strict';


let search = document.querySelector("#search");
let sort = document.querySelector("#sortBy");
let element = document.querySelector("#listItem");

search.addEventListener('input',function() {
    request(base_url+"/inventario/searchc/"+search.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

sort.addEventListener("change",function(){
    request(base_url+"/inventario/sortc/"+sort.value,"","get").then(function(objData){
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
                    <h5 class="modal-title" id="staticBackdropLabel">Nueva categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formItem" name="formItem" class="mb-4">
                        <input type="hidden" id="idCategory" name="idCategory">
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
                            <label for="txtDescription" class="form-label">Descripción </label>
                            <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"></textarea>
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
        let idCategory = document.querySelector("#idCategory").value;

        if(strName == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        let url = base_url+"/inventario/setCategory";
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
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
function editItem(id){
    let url = base_url+"/inventario/getCategory";
    let formData = new FormData();
    formData.append("idCategory",id);
    request(url,formData,"post").then(function(objData){
        let modalItem = document.querySelector("#modalItem");
        let modal= `
        <div class="modal fade" id="modalElement">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Actualizar categoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formItem" name="formItem" class="mb-4">
                            <input type="hidden" id="idCategory" name="idCategory" value="${objData.data.idcategory}">
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
                                <label for="txtDescription" class="form-label">Descripción </label>
                                <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5">${objData.data.description}</textarea>
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

        let img = document.querySelector("#txtImg");
        let imgLocation = ".uploadImg img";
        img.addEventListener("change",function(){
            uploadImg(img,imgLocation);
        });

        let form = document.querySelector("#formItem");
        form.addEventListener("submit",function(e){
            e.preventDefault();

            let strName = document.querySelector("#txtName").value;
            let idCategory = document.querySelector("#idCategory").value;

            if(strName == ""){
                Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
                return false;
            }
            
            let url = base_url+"/inventario/setCategory";
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
            let url = base_url+"/inventario/delCategory"
            let formData = new FormData();
            formData.append("idCategory",id);
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
