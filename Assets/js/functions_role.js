'use strict';


let search = document.querySelector("#search");
let sort = document.querySelector("#sortBy");
let element = document.querySelector("#listItem");

search.addEventListener('input',function() {
    request(base_url+"/roles/search/"+search.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
})
sort.addEventListener("change",function(){
    request(base_url+"/roles/sort/"+sort.value,"","get").then(function(objData){
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
    }else if(element.name == "btnPermit"){
        permitItem(id);
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
                    <h5 class="modal-title" id="staticBackdropLabel">Nuevo rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formItem" name="formItem" class="mb-4">
                        <input type="hidden" id="idRol" name="idRol" value="">
                        <div class="mb-3">
                            <label for="txtName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="txtName" name="txtName">
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

        if(strName ==""){
            Swal.fire("Error","Los campos no pueden estar vacíos","error");
            return false;
        }
        
        let url = base_url+"/roles/setRole";
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        request(url,formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-plus-circle"></i> Agregar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Agregado",objData.msg,"success");
                modalView.hide();
                element.innerHTML = objData.data;
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
function permitItem(id){
    let url = base_url+"/roles/getPermits";
    let formData = new FormData();
    formData.append("idRol",id);
    request(url,formData,"post").then(function(objData){
        let module = objData.module;
        let permit = objData.permit;
        let htmlModule = "";
        for (let i = 0; i < module.length; i++) {
            htmlModule+=`
            <tr>
                <td class="text-start" >
                    <div>
                        <input type="hidden" value ="${module[i]['idmodule']}">
                        ${module[i]['name']}
                    </div>
                </td>
                <td>
                    <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
                        <input class="form-check-input" type="checkbox" role="switch" id="r${module[i]['idmodule']}">
                    </div>
                </td>
                <td>
                    <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
                        <input class="form-check-input" type="checkbox" role="switch" id="w${module[i]['idmodule']}">
                    </div>
                </td>
                <td>
                    <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
                        <input class="form-check-input" type="checkbox" role="switch" id="u${module[i]['idmodule']}">
                    </div>
                </td>
                <td>
                    <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
                        <input class="form-check-input" type="checkbox" role="switch" id="d${module[i]['idmodule']}">
                    </div>
                </td>
            </tr>
            `;
        }
        let modalItem = document.querySelector("#modalItem");
        let modal= `
        <div class="modal fade" id="modalElement">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Permits</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table text-center align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-start">Modulo</th>
                                        <th>Leer</th>
                                        <th>Crear</th>
                                        <th>Actualizar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="modules">
                                    ${htmlModule}
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btnAdd">Guardar</button>
                            <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        `;
        modalItem.innerHTML = modal;
        let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));
        for (let i = 0; i < module.length; i++) {
            if(permit.length > 0){
                for (let j = 0; j < permit.length; j++) {
                    if(module[i]['idmodule'] == permit[j]['moduleid']){
                        document.querySelector("#r"+(i+1)).checked=permit[j]['r'];
                        document.querySelector("#w"+(i+1)).checked=permit[j]['w'];
                        document.querySelector("#u"+(i+1)).checked=permit[j]['u'];
                        document.querySelector("#d"+(i+1)).checked=permit[j]['d'];
                    }
                }
            }
        }

        modalView.show();
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.addEventListener("click",function(){
            let row = document.querySelectorAll("#modules tr");
            let data = new Array();
            for (let i = 0; i < row.length; i++) {
                let col = row[i].children;
                data[i] = new Array();
                for (let j = 0; j < col.length; j++) {
                    if(j == 0){
                        data[i][j] = (col[j].children[0].children[0].value);
                    }else{
                        data[i][j] = col[j].children[0].children[0].checked;
                    }
                }
            }
            btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;  
            btnAdd.setAttribute("disabled","");

            url = base_url+"/roles/setPermits";
            let permits = new FormData();
            permits.append("permits",JSON.stringify(data));
            permits.append("idRol",id);
            request(url,permits,"post").then(function(objData){
                btnAdd.innerHTML=`Guardar`;
                btnAdd.removeAttribute("disabled");
                if(objData.status){
                    modalView.hide();
                    Swal.fire("Permisos",objData.msg,"success");
                }else{
                    Swal.fire("Permisos",objData.msg,"error");
                }
            });
        })

    });
}
function editItem(id){
    let url = base_url+"/roles/getrole";
    let formData = new FormData();
    
    formData.append("idRol",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            let modalItem = document.querySelector("#modalItem");
            let modal= `
            <div class="modal fade" id="modalElement">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Actualizar rol</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formItem" name="formItem" class="mb-4">
                                <input type="hidden" id="idRol" name="idRol" value="${objData.data.idrole}">
                                <div class="mb-3">
                                    <label for="txtName" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="txtName" name="txtName" value="${objData.data.name}">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-info text-white" id="btnAdd">Actualizar</button>
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

                if(strName ==""){
                    Swal.fire("Error","Los campos no pueden estar vacíos","error");
                    return false;
                }

                let url = base_url+"/roles/setRole";
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
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
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
            let url = base_url+"/roles/delRole"
            let formData = new FormData();
            formData.append("idRol",id);
            request(url,formData,"post").then(function(objData){
                Swal.fire("Eliminado",objData.msg,"success");
                element.innerHTML = objData.data;
            });
        }
    });
}