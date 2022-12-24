'use strict';


let search = document.querySelector("#search");
let sort = document.querySelector("#sortBy");
let element = document.querySelector("#listItem");

search.addEventListener('input',function() {
    request(base_url+"/marqueteria/searchm/"+search.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

sort.addEventListener("change",function(){
    request(base_url+"/marqueteria/sortm/"+sort.value,"","get").then(function(objData){
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
    }else if(element.name == "btnView"){
        viewItem(id);
    }
});
    
function addItem(){
    let getData = new FormData();
    getData.append("idProduct",0);
    request(base_url+"/marqueteria/getProduct",getData,"post").then(function(objData){});
    
    let modalItem = document.querySelector("#modalItem");
    let modal = `
    <div class="modal fade" id="modalElement">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Nueva moldura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFile" name="formFile">
                        <div class="row scrolly" id="upload-multiple">
                            <div class="col-6 col-lg-3">
                                <div class="mb-3 upload-images">
                                    <label for="txtImg" class="text-primary text-center d-flex justify-content-center align-items-center">
                                        <div>
                                            <i class="far fa-images fs-1"></i>
                                            <p class="m-0">Subir imágen</p>
                                        </div>
                                    </label>
                                    <input class="d-none" type="file" id="txtImg" name="txtImg[]" multiple accept="image/*"> 
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="formItem" name="formItem" class="mb-4">  
                        <input type="hidden" id="idProduct" name="idProduct" value="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtReference" class="form-label">Referencia</label>
                                    <input type="text" class="form-control" id="txtReference" name="txtReference">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="molduraList" class="form-label">Tipo de moldura <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="molduraList" name="molduraList" required>
                                        <option value="1">Moldura en madera</option>
                                        <option value="2">Moldura importada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtWaste" class="form-label">Desperdicio (cm)</label>
                                    <input type="number" class="form-control" id="txtWaste" name="txtWaste">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtPrice" class="form-label">Costo x cm <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" min ="1" id="txtPrice" name="txtPrice">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtDiscount" class="form-label">Descuento</label>
                                    <input type="number" class="form-control"  max="99" id="txtDiscount" name="txtDiscount">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="txtFrame" class="form-label">Imágen de marco <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="txtFrame" name="txtFrame" accept="image/*"> 
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
    let formFile = document.querySelector("#formFile");
    let parent = document.querySelector("#upload-multiple");
    let img = document.querySelector("#txtImg");
    let btnAdd = document.querySelector("#btnAdd");

    form.reset();
    formFile.reset();

    if(document.querySelectorAll(".upload-image")){
        let divImg = document.querySelectorAll(".upload-image");
        for (let i = 0; i < divImg.length; i++) {
            divImg[i].remove();
        }
    }

    setImage(img,parent,1);
    delImage(parent,1);

    let flag = true;
    form.addEventListener("submit",function(e){
        e.preventDefault();
        e.stopPropagation();
        let strName = document.querySelector("#txtReference").value;
        let molduraList = document.querySelector("#molduraList").value;
        let intDiscount = document.querySelector("#txtDiscount").value;
        let intPrice = document.querySelector("#txtPrice").value;
        let intStatus = document.querySelector("#statusList").value;
        let intWaste = document.querySelector("#txtWaste").value;
        let strFrame = document.querySelector("#txtFrame").value; 

        let images = document.querySelectorAll(".upload-image");

        if(strName == "" || intStatus == "" || molduraList == "" ||  intPrice=="" || intWaste=="" || strFrame==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(images.length < 1){
            Swal.fire("Error","Debe subir al menos una imagen","error");
            return false;
        }
        if(intPrice <= 0){
            Swal.fire("Error","El costo no puede ser menor o igual que 0 ","error");
            return false;
        }
        if(intWaste <= 0){
            Swal.fire("Error","El desperdicio no puede ser menor o igual que 0 ","error");
            return false;
        }
        if(intDiscount !=""){
            if(intDiscount < 0){
                Swal.fire("Error","El descuento no puede ser inferior a 0","error");
                document.querySelector("#txtDiscount").value="";
                return false;
            }else if(intDiscount > 90){
                Swal.fire("Error","El descuento no puede ser superior al 90%.","error");
                document.querySelector("#txtDiscount").value="";
                return false;
            }
        }
        
        
        let data = new FormData(form);
        
        if(flag === true){

            btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            btnAdd.setAttribute("disabled","");

            request(base_url+"/marqueteria/setProduct",data,"post").then(function(objData){
                modalView.hide();
                form.reset();
                formFile.reset();
                if(objData.status){
                    Swal.fire("Agregado",objData.msg,"success");
                    modalView.hide();
                    let divImg = document.querySelectorAll(".upload-image");
                    for (let i = 0; i < divImg.length; i++) {
                        divImg[i].remove();
                    }
                    element.innerHTML = objData.data;
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
            modalItem.innerHTML="";
            btnAdd.innerHTML=`<i class="fas fa-plus-circle"></i> Agregar`;
            btnAdd.removeAttribute("disabled");
            flag = false;
        }
    },false);
}
function viewItem(id){
    let url = base_url+"/marqueteria/getProduct";
    let formData = new FormData();
    formData.append("idProduct",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            let images = objData.data.image;
            let html = "";
            let type = "";
            let discount =objData.data.discount;
            let status = objData.data.status;
            for (let i = 0; i < images.length; i++) {
                html+=`
                    <div class="col-6 col-lg-3 upload-image mb-3">
                        <img src="${images[i]['url']}">
                    </div>
                `;
            }
            if(discount>0){
                discount = `<span class="text-success">${discount}% OFF</span>`
            }else{
                discount = `<span class="text-danger">0%</span>`
            }
            if(status==1){
                status='<span class="badge me-1 bg-success">Activo</span>';
            }else{
                status='<span class="badge me-1 bg-danger">Inactivo</span>';
            }
            if(objData.data.type == 1){
                type = 'Moldura en madera'
            }else{
                type ="Moldura importada"
            }
            let modalItem = document.querySelector("#modalItem");
            let modal= `
            <div class="modal fade" id="modalElement">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Datos de moldura</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row scrolly">
                                ${html}
                            </div>
                            <table class="table align-middle text-break">
                                <tbody id="listItem">
                                    <tr>
                                        <td><strong>Referencia:</strong></td>
                                        <td>${objData.data.reference}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo: </strong></td>
                                        <td>${type}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Desperdicio: </strong></td>
                                        <td>${objData.data.waste} cm</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Costo: </strong></td>
                                        <td>${objData.data.priceFormat} x cm</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descuento: </strong></td>
                                        <td>${discount}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado: </strong></td>
                                        <td>${status}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Marco: </strong></td>
                                        <td> <img src="${base_url+"/Assets/images/uploads/"+objData.data.frame}" height="100" width="100"></td>
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
function editItem(id){ 

    let modalItem = document.querySelector("#modalItem");
    let modal = `
    <div class="modal fade" id="modalElement">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Actualizar moldura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFile" name="formFile">
                        <div class="row scrolly" id="upload-multiple">
                            <div class="col-6 col-md-3">
                                <div class="mb-3 upload-images">
                                    <label for="txtImg" class="text-primary text-center d-flex justify-content-center align-items-center">
                                        <div>
                                            <i class="far fa-images fs-1"></i>
                                            <p class="m-0">Subir imágen</p>
                                        </div>
                                    </label>
                                    <input class="d-none" type="file" id="txtImg" name="txtImg[]" multiple accept="image/*"> 
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="formItem" name="formItem" class="mb-4">  
                        <input type="hidden" id="idProduct" name="idProduct" value="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtReference" class="form-label">Referencia</label>
                                    <input type="text" class="form-control" id="txtReference" name="txtReference">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="molduraList" class="form-label">Tipo de moldura <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="molduraList" name="molduraList" required>
                                        <option value="1">Moldura en madera</option>
                                        <option value="2">Moldura importada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtWaste" class="form-label">Desperdicio (cm)</label>
                                    <input type="number" class="form-control" id="txtWaste" name="txtWaste">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtPrice" class="form-label">Costo x cm <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" min ="1" id="txtPrice" name="txtPrice">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtDiscount" class="form-label">Descuento</label>
                                    <input type="number" class="form-control"  max="99" id="txtDiscount" name="txtDiscount">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="txtFrame" class="form-label">Imágen de marco <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="txtFrame" name="txtFrame" accept="image/*"> 
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
    let formFile = document.querySelector("#formFile");
    let parent = document.querySelector("#upload-multiple");
    let img = document.querySelector("#txtImg");
    let btnAdd = document.querySelector("#btnAdd");
    form.reset();
    formFile.reset();
    let formData = new FormData();
    formData.append("idProduct",id);


    request(base_url+"/marqueteria/getProduct",formData,"post").then(function(objData){
        let status = document.querySelectorAll("#statusList option");
        let type = document.querySelectorAll("#molduraList option");
        let images = objData.data.image;
        document.querySelector("#idProduct").value = objData.data.id;
        document.querySelector("#txtReference").value = objData.data.reference;
        document.querySelector("#txtWaste").value = objData.data.waste;
        document.querySelector("#txtDiscount").value = objData.data.discount;
        document.querySelector("#txtPrice").value = objData.data.price;


        for (let i = 0; i < status.length; i++) {
            if(status[i].value == objData.data.status){
                status[i].setAttribute("selected",true);
                break;
            }
        }
        for (let i = 0; i < type.length; i++) {
            if(type[i].value == objData.data.type){
                type[i].setAttribute("selected",true);
                break;
            }
        }
        if(images[0]!=""){
            for (let i = 0; i < images.length; i++) {
                let div = document.createElement("div");
                div.classList.add("col-6","col-lg-3","upload-image","mb-3");
                div.setAttribute("data-name",images[i]['name']);
                div.innerHTML = `
                        <img>
                        <div class="deleteImg" name="delete">x</div>
                `
                div.children[0].setAttribute("src",images[i]['url']);
                parent.appendChild(div);
            }
        }
    });

    modalView.show();
    setImage(img,parent,2);
    delImage(parent,2);

    let flag = true;
    form.addEventListener("submit",function(e){
        e.preventDefault();
        e.stopPropagation();
        let strName = document.querySelector("#txtReference").value;
        let molduraList = document.querySelector("#molduraList").value;
        let intDiscount = document.querySelector("#txtDiscount").value;
        let intPrice = document.querySelector("#txtPrice").value;
        let intStatus = document.querySelector("#statusList").value;
        let intWaste = document.querySelector("#txtWaste").value;

        let images = document.querySelectorAll(".upload-image");

        if(strName == "" || intStatus == "" || molduraList == "" ||  intPrice=="" || intWaste==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(images.length < 1){
            Swal.fire("Error","Debe subir al menos una imagen","error");
            return false;
        }
        if(intPrice <= 0){
            Swal.fire("Error","El costo no puede ser menor o igual que 0 ","error");
            return false;
        }
        if(intWaste <= 0){
            Swal.fire("Error","El desperdicio no puede ser menor o igual que 0 ","error");
            return false;
        }
        if(intDiscount !=""){
            if(intDiscount < 0){
                Swal.fire("Error","El descuento no puede ser inferior a 0","error");
                document.querySelector("#txtDiscount").value="";
                return false;
            }else if(intDiscount > 90){
                Swal.fire("Error","El descuento no puede ser superior al 90%.","error");
                document.querySelector("#txtDiscount").value="";
                return false;
            }
        }
        
        
        let data = new FormData(form);
        
        if(flag === true){

            btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            btnAdd.setAttribute("disabled","");

            request(base_url+"/marqueteria/setProduct",data,"post").then(function(objData){
                modalView.hide();
                form.reset();
                formFile.reset();
                if(objData.status){
                    Swal.fire("Actualizado",objData.msg,"success");
                    modalView.hide();
                    let divImg = document.querySelectorAll(".upload-image");
                    for (let i = 0; i < divImg.length; i++) {
                        divImg[i].remove();
                    }
                    element.innerHTML = objData.data;
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
            modalItem.innerHTML="";
            btnAdd.innerHTML=`Actualizar`;
            btnAdd.removeAttribute("disabled");
            flag = false;
        }
    },false);
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
            let formData = new FormData();
            formData.append("idProduct",id);
            request(base_url+"/marqueteria/delProduct",formData,"post").then(function(objData){
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
function setImage(element,parent,option){
    let formFile = document.querySelector("#formFile");
    element.addEventListener("change",function(e){
        if(element.value!=""){
            let formImg = new FormData(formFile);
            uploadMultipleImg(element,parent);
            
            formImg.append("id","");
            
            if(option == 2){
                let images = document.querySelectorAll(".upload-image").length;
                formImg.append("images",images);
                formImg.append("id",document.querySelector("#idProduct").value);  
            }
            request(base_url+"/marqueteria/setImg",formImg,"post").then(function(objData){});
        }
    });
}
function delImage(parent,option){
    parent.addEventListener("click",function(e){
        if(e.target.className =="deleteImg"){
            let divImg = document.querySelectorAll(".upload-image");
            let deleteItem = e.target.parentElement;
            let nameItem = deleteItem.getAttribute("data-name");
            let imgDel;
            for (let i = 0; i < divImg.length; i++) {
                if(divImg[i].getAttribute("data-name")==nameItem){
                    deleteItem.remove();
                    imgDel = document.querySelectorAll(".upload-image");
                }
            }
            let url = base_url+"/marqueteria/delImg";
            let formDel = new FormData();

            formDel.append("id","");
            if(option == 2){
                formDel.append("id",document.querySelector("#idProduct").value);  
            }
            formDel.append("image",nameItem);
            request(url,formDel,"post").then(function(objData){});
        }
    });
}