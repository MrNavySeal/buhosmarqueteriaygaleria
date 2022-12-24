'use strict';


let search = document.querySelector("#search");
let sort = document.querySelector("#sortBy");
let element = document.querySelector("#listItem");

search.addEventListener('input',function() {
    request(base_url+"/inventario/search/"+search.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

sort.addEventListener("change",function(){
    request(base_url+"/inventario/sort/"+sort.value,"","get").then(function(objData){
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
    request(base_url+"/inventario/getProduct",getData,"post").then(function(objData){});

    let modalItem = document.querySelector("#modalItem");
    let modal = `
    <div class="modal fade" id="modalElement">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Nuevo producto</h5>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtReference" class="form-label">Referencia</label>
                                    <input type="text" class="form-control" id="txtReference" name="txtReference" placeholder="SKU">
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
                                    <label for="categoryList" class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subcategoryList" class="form-label">Subcategoria <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="subcategoryList" name="subcategoryList" required></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtDiscount" class="form-label">Descuento</label>
                                    <input type="number" class="form-control"  max="99" id="txtDiscount" name="txtDiscount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtPrice" class="form-label">Precio <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" min ="1" id="txtPrice" name="txtPrice">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtStock" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                    <input type="number" value="1" min="0" class="form-control" id="txtStock" name="txtStock">
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            <label for="txtShortDescription" class="form-label">Descripción corta</label>
                            <input type="text" class="form-control" id="txtShortDescription" name="txtShortDescription" placeholder="Max 140 characters"></input>
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
    
    let form = document.querySelector("#formItem");
    let formFile = document.querySelector("#formFile");
    let parent = document.querySelector("#upload-multiple");
    let img = document.querySelector("#txtImg");
    let categoryList = document.querySelector("#categoryList");
    let subcategoryList = document.querySelector("#subcategoryList");
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

    request(base_url+"/inventario/getSelectCategories","","get").then(function(objData){
        categoryList.innerHTML = objData.data;
    });

    categoryList.addEventListener("change",function(){
        let formData = new FormData();
        formData.append("idCategory",categoryList.value);

        request(base_url+"/inventario/getSelectSubcategories",formData,"post").then(function(objData){
            document.querySelector("#subcategoryList").innerHTML = objData.data;
        });
    });

    setTinymce("#txtDescription");
    
    let flag = true;
    form.addEventListener("submit",function(e){
        e.preventDefault();
        e.stopPropagation();
        tinymce.triggerSave();
        let strName = document.querySelector("#txtName").value;
        let intDiscount = document.querySelector("#txtDiscount").value;
        let intPrice = document.querySelector("#txtPrice").value;
        let intStatus = document.querySelector("#statusList").value;
        let intStock = document.querySelector("#txtStock").value;
        let strShortDescription = document.querySelector("#txtShortDescription").value;
        let intSubCategory = subcategoryList.value;
        let intCategory = categoryList.value;
        let images = document.querySelectorAll(".upload-image");

        if(strName == "" || intStatus == "" || intCategory == 0 || intSubCategory==0 || intPrice=="" || intStock==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(strShortDescription.length >140){
            Swal.fire("Error","La descripción corta debe tener un máximo de 140 caracteres","error");
            return false;
        }
        if(images.length < 1){
            Swal.fire("Error","Debe subir al menos una imagen","error");
            return false;
        }
        if(intPrice <= 0){
            Swal.fire("Error","El precio no puede ser menor o igual que 0 ","error");
            return false;
        }
        if(intStock <= 0){
            Swal.fire("Error","La cantidad no puede ser menor o igual a 0 ","error");
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

            request(base_url+"/inventario/setProduct",data,"post").then(function(objData){
                modalView.hide();
                form.reset();
                formFile.reset();
                if(objData.status){
                    Swal.fire("Added",objData.msg,"success");
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
    let url = base_url+"/inventario/getProduct";
    let formData = new FormData();
    formData.append("idProduct",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            let images = objData.data.image;
            let html = "";
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
            let modalItem = document.querySelector("#modalItem");
            let modal= `
            <div class="modal fade" id="modalElement">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Datos de producto</h5>
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
                                        <td><strong>Nombre: </strong></td>
                                        <td>${objData.data.name}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Categoria: </strong></td>
                                        <td>${objData.data.category}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Subcategoria: </strong></td>
                                        <td>${objData.data.subcategory}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Precio: </strong></td>
                                        <td>${objData.data.priceFormat}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descuento: </strong></td>
                                        <td>${discount}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cantidad: </strong></td>
                                        <td>${objData.data.stock}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha: </strong></td>
                                        <td>${objData.data.date}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado: </strong></td>
                                        <td>${status}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descripción: </strong></td>
                                        <td>${objData.data.shortdescription}</td>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Actualizar producto</h5>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtReference" class="form-label">Referencia</label>
                                    <input type="text" class="form-control" id="txtReference" name="txtReference" placeholder="SKU">
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
                                    <label for="categoryList" class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subcategoryList" class="form-label">Subcategoria <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="subcategoryList" name="subcategoryList" required></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtDiscount" class="form-label">Descuento</label>
                                    <input type="number" class="form-control"  max="99" id="txtDiscount" name="txtDiscount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtPrice" class="form-label">Precio <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" min ="1" id="txtPrice" name="txtPrice">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtStock" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                    <input type="number" value="1" min="0" class="form-control" id="txtStock" name="txtStock">
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            <label for="txtShortDescription" class="form-label">Descripción corta</label>
                            <input type="text" class="form-control" id="txtShortDescription" name="txtShortDescription" placeholder="Max 140 characters"></input>
                        </div>
                        <div class="mb-3">
                            <label for="txtDescription" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"></textarea>
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
    let categoryList = document.querySelector("#categoryList");
    let subcategoryList = document.querySelector("#subcategoryList");
    let btnAdd = document.querySelector("#btnAdd");
    form.reset();
    formFile.reset();
    let formData = new FormData();
    formData.append("idProduct",id);

    setTinymce("#txtDescription");

    request(base_url+"/inventario/getProduct",formData,"post").then(function(objData){
        let status = document.querySelectorAll("#statusList option");
        let images = objData.data.image;
        document.querySelector("#idProduct").value = objData.data.idproduct;
        document.querySelector("#txtReference").value = objData.data.reference;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#txtDiscount").value = objData.data.discount;
        document.querySelector("#txtPrice").value = objData.data.price;
        document.querySelector("#txtStock").value = objData.data.stock;
        document.querySelector("#txtShortDescription").value=objData.data.shortdescription; 
        document.querySelector("#txtDescription").value=objData.data.description; 

        for (let i = 0; i < status.length; i++) {
            if(status[i].value == objData.data.status){
                status[i].setAttribute("selected",true);
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
        request(base_url+"/inventario/getSelectCategories","","get").then(function(html){
            document.querySelector("#categoryList").innerHTML = html.data;
            let categories = document.querySelectorAll("#categoryList option");
            for (let i = 0; i < categories.length; i++) {
                if(categories[i].value == objData.data.categoryid){
                    categories[i].setAttribute("selected",true);
                    let formData = new FormData();

                    formData.append("idCategory",objData.data.categoryid);
                    request(base_url+"/inventario/getSelectSubcategories",formData,"post").then(function(htmls){
                        document.querySelector("#subcategoryList").innerHTML = htmls.data;
                        let subcategories = document.querySelectorAll("#subcategoryList option");
                        for (let i = 0; i < subcategories.length; i++) {
                            if(subcategories[i].value == objData.data.subcategoryid){
                                subcategories[i].setAttribute("selected",true);
                                break;
                            }
                        }
                    });
                    break;
                }
            }
        });
    });

    modalView.show();
    setImage(img,parent,2);
    delImage(parent,2);

    categoryList.addEventListener("change",function(){
        let formData = new FormData();
        formData.append("idCategory",categoryList.value);

        request(base_url+"/inventario/getSelectSubcategories",formData,"post").then(function(objData){
            document.querySelector("#subcategoryList").innerHTML = objData.data;
        });
    },false);

    let flag = true;
    form.addEventListener("submit",function(e){
        e.preventDefault();
        e.stopPropagation();
        
        let strName = document.querySelector("#txtName").value;
        let intDiscount = document.querySelector("#txtDiscount").value;
        let intPrice = document.querySelector("#txtPrice").value;
        let intStatus = document.querySelector("#statusList").value;
        let intStock = document.querySelector("#txtStock").value;
        let intSubCategory = subcategoryList.value;
        let intCategory = categoryList.value;
        let images = document.querySelectorAll(".upload-image");

        
        if(strName == "" || intStatus == "" || intCategory == 0 || intSubCategory==0 || intPrice=="" || intStock==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(images.length < 1){
            Swal.fire("Error","Debe subir una imagen","error");
            return false;
        }
        if(intPrice <= 0){
            Swal.fire("Error","El precio no puede ser menor o igual que 0","error");
            return false;
        }
        if(intStock <= 0){
            Swal.fire("Error","La cantidad no puede ser menor o igual a 0","error");
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
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;  
        btnAdd.setAttribute("disabled","");

        if(flag === true){
            request(base_url+"/inventario/setProduct",data,"post").then(function(objData){
                form.reset();
                formFile.reset();
                if(objData.status){
                    Swal.fire("Actualizado",objData.msg,"success");
                    modalView.hide();
                    modalItem.innerHTML="";
                    let divImg = document.querySelectorAll(".upload-image");
                    for (let i = 0; i < divImg.length; i++) {
                        divImg[i].remove();
                    }
                    element.innerHTML = objData.data;
                }else{
                    modalView.hide();
                    modalItem.innerHTML="";
                    Swal.fire("Error",objData.msg,"error");
                }
            });
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
            request(base_url+"/inventario/delProduct",formData,"post").then(function(objData){
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
            request(base_url+"/inventario/setImg",formImg,"post").then(function(objData){});
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
            let url = base_url+"/inventario/delImg";
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