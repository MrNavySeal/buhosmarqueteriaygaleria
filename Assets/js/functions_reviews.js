'use strict';


let search = document.querySelector("#search");
let sort = document.querySelector("#sortBy");
let element = document.querySelector("#listItem");

sort.addEventListener("change",function(){
    request(base_url+"/comentarios/sort/"+sort.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

element.addEventListener("click",function(e) {
    let element = e.target;
    let id = element.getAttribute("data-id");
    let status= element.getAttribute("data-status");
    if(element.name == "btnDelete"){
        delItem(id,status);
    }else if(element.name == "btnView"){
        viewItem(id);
    }else if(element.name == "btnEdit"){
        setItem(id,status);
    }
});
function viewItem(id){
    let url = base_url+"/comentarios/getReview";
    let formData = new FormData();
    
    formData.append("idReview",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            let modalItem = document.querySelector("#modalItem");
            let modal= `
            <div class="modal fade" id="modalElement">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Opinión</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table align-middle text-break">
                                <tbody id="listItem">
                                    <tr>
                                        <td><strong>Nombre: </strong></td>
                                        <td>${objData.data.firstname+" "+objData.data.lastname}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Calificación: </strong></td>
                                        <td>${objData.data.rate}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Opinión: </strong></td>
                                        <td>${objData.data.description}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            `;
            modalItem.innerHTML = modal;
            let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));
            modalView.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function setItem(id,status){
    let formData = new FormData();
    formData.append("id",id);
    formData.append("status",status);
    request(base_url+"/comentarios/setReview",formData,"post").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function delItem(id,status){
    let formData = new FormData();
    formData.append("id",id);
    formData.append("status",status);
    request(base_url+"/comentarios/setReview",formData,"post").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}