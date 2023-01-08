'use strict';

window.addEventListener("load",function(){
    let categoryList = document.querySelector("#categoryList");
    let subcategoryList = document.querySelector("#subcategoryList");
    let form = document.querySelector("#formItem");
    let formFile = document.querySelector("#formFile");
    let parent = document.querySelector("#upload-multiple");
    let img = document.querySelector("#txtImg");
    let btnAdd = document.querySelector("#btnAdd");
    let id = document.querySelector("#idProduct").value;
    if(id == 0){
        request(base_url+"/inventario/getSelectCategories","","get").then(function(objData){
            categoryList.innerHTML = objData.data;
        });
    }
    categoryList.addEventListener("change",function(){
        let formData = new FormData();
        formData.append("idCategory",categoryList.value);
        request(base_url+"/inventario/getSelectSubcategories",formData,"post").then(function(objData){
            document.querySelector("#subcategoryList").innerHTML = objData.data;
        });
    });
    setImage(img,parent,"product");
    delImage(parent);
    setTinymce("#txtDescription");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        tinymce.triggerSave();
        let data = new FormData(form);
        let strName = document.querySelector("#txtName").value;
        let intDiscount = document.querySelector("#txtDiscount").value;
        let intPrice = document.querySelector("#txtPrice").value;
        let intStatus = document.querySelector("#statusList").value;
        let intStock = document.querySelector("#txtStock").value;
        let strShortDescription = document.querySelector("#txtShortDescription").value;
        let intSubCategory = subcategoryList.value;
        let intCategory = categoryList.value;
        let images = document.querySelectorAll(".upload-image");

        if(strName == "" || intStatus == "" || intCategory == 0 || intSubCategory==0 || 
        strShortDescription =="" || intPrice=="" || intStock==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(strShortDescription.length >140){
            Swal.fire("Error","La descripción corta debe tener un máximo de 140 caracteres","error");
            return false;
        }
        if(images.length < 1){
            Swal.fire("Error","Debe subir al menos una imagen","error");
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
        let arrImg =[];
        for (let i = 0; i < images.length; i++) {
            arrImg.push(images[i].getAttribute("data-rename"));
        }
        data.append("images",JSON.stringify(arrImg));
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(base_url+"/inventario/setProduct",data,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-plus-circle"></i> Agregar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                /*form.reset();
                formFile.reset();
                Swal.fire("Added",objData.msg,"success");
                let divImg = document.querySelectorAll(".upload-image");
                for (let i = 0; i < divImg.length; i++) {
                    divImg[i].remove();
                }*/
                window.location.href=base_url+"/inventario/productos";
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
        
    });
});
function setImage(element,parent,pre){
    let formFile = document.querySelector("#formFile");
    if(pre==""){
        Swal.fire("Error","prefijo de imagen","error");
        return false;
    }
    element.addEventListener("change",function(e){
        if(element.value!=""){
            let formImg = new FormData(formFile);
            uploadMultipleImg(element,parent);
            formImg.append("id","");
            formImg.append("pre",pre);
            /*if(option == 2){
                let images = document.querySelectorAll(".upload-image").length;
                formImg.append("images",images);
                formImg.append("id",document.querySelector("#idProduct").value);  
            }*/
            request(base_url+"/UploadImages/uploadMultipleImages",formImg,"post").then(function(objData){
                if(objData.status){
                    let divImg = document.querySelectorAll(".upload-image");
                    let newImg =[];
                    let images = objData.data;
                    for (let i = 0; i < divImg.length; i++) {
                        if(!divImg[i].hasAttribute("data-rename")){
                            newImg.push(divImg[i]);
                        }
                    }
                    if(newImg.length == images.length){
                        for (let i = 0; i < images.length; i++) {
                            newImg[i].setAttribute("data-rename",images[i].rename);
                        }
                    }
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}
function delImage(parent){
    parent.addEventListener("click",function(e){
        if(e.target.className =="deleteImg"){
            let divImg = document.querySelectorAll(".upload-image");
            let deleteItem = e.target.parentElement;
            let nameItem = deleteItem.getAttribute("data-rename");
            let imgDel;
            for (let i = 0; i < divImg.length; i++) {
                if(divImg[i].getAttribute("data-rename")==nameItem){
                    deleteItem.remove();
                    imgDel = document.querySelectorAll(".upload-image");
                }
            }
            let formDel = new FormData();
            formDel.append("image",nameItem);
            request(base_url+"/UploadImages/delImg",formDel,"post").then(function(objData){});
        }
    });
}
