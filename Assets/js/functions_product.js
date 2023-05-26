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
    let selectProductType = document.querySelector("#selectProductType");
    let btnVariant = document.querySelector("#btnVariant");
    let btnSpc = document.querySelector("#btnSpc");
    let selectFramingMode = document.querySelector("#framingMode");
    if(id == 0){
        request(base_url+"/inventario/getSelectCategories","","get").then(function(objData){
            categoryList.innerHTML = objData.data;
        });
    }
    selectFramingMode.addEventListener("change",function(){
        if(selectFramingMode.value == 1){
            document.querySelector(".framingImage").classList.remove("d-none");
        }else{
            document.querySelector(".framingImage").classList.add("d-none");
        }
    });
    let framingImg = document.querySelector("#txtImgFrame");
    let imgLocation = ".uploadImg img";
    framingImg.addEventListener("change",function(){
        uploadImg(framingImg,imgLocation);
    });
    btnVariant.addEventListener("click",function(){
        /*if(width =="" || height =="" || price ==""){
            Swal.fire("Error","Todos los campos de la variante marcados con (*) son obligatorios","error");
            return false;
        }*/
        addVariant();
    });
    btnSpc.addEventListener("click",function(){
        addSpec(document.querySelector("#selectTypeSpc").value);
    });
    selectProductType.addEventListener("change",function(){
        if(selectProductType.value == 2){
            document.querySelector(".productBasic").classList.add("d-none");
            document.querySelector(".productVariant").classList.remove("d-none");
        }else{
            document.querySelector(".productBasic").classList.remove("d-none");
            document.querySelector(".productVariant").classList.add("d-none");
        }
    });
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
        console.log(getVariatns());
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
        
        if(strName == "" || intStatus == "" || intCategory == 0 || intSubCategory==0){
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
        if(selectProductType.value == 1 && intPrice ==""){
            Swal.fire("Error","Por favor, para producto sin variante, ingrese al menos el precio","error");
            return false;
        }
        if(selectProductType.value == 2){
            console.log(selectProductType.value);
            let flag = true;
            let variants = document.querySelectorAll(".variantItem");
            if(variants.length == 0){
                Swal.fire("Error","Por favor, ingresa al menos una variante","error");
                return false;
            }else if(variants.length > 0){
                for (let i = 0; i < variants.length; i++) {
                    let td = variants[i];
                    if(td.children[0].children[0].value == "" || td.children[1].children[0].value == "" || td.children[3].children[0].value == ""){
                        flag = false;
                        break;
                    }
                }
            }
            if(flag == false){
                Swal.fire("Error","El ancho, alto y precio son obligatorios","error");
                return false
            }
        }
        if(selectFramingMode.value == 1 && document.querySelector("#txtImgFrame").value == "" && id==0){
            Swal.fire("Error","Por favor, para el modo enmarcar, ingrese la foto a enmarcar","error");
            return false;
        }
        let arrImg =[];
        for (let i = 0; i < images.length; i++) {
            arrImg.push(images[i].getAttribute("data-rename"));
        }
        
        
        data.append("variants",JSON.stringify(getVariatns()));
        data.append("images",JSON.stringify(arrImg));
        data.append("specs",JSON.stringify(getSpecs()));
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        
        request(base_url+"/inventario/setProduct",data,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                /*form.reset();
                formFile.reset();
                Swal.fire("Added",objData.msg,"success");
                let divImg = document.querySelectorAll(".upload-image");
                for (let i = 0; i < divImg.length; i++) {
                    divImg[i].remove();
                }*/
                if (id == 0) {
                    window.location.href=base_url+"/inventario/productos";
                } else {
                    window.location.reload();
                }
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    });
});
function getVariatns(){
    let variants = document.querySelectorAll(".variantItem");
    let arrVariants = [];
    for (let i = 0; i < variants.length; i++) {
        let item = variants[i].children;
        let height = item[1].children[0].value;
        let width = item[0].children[0].value;;
        let stock = item[2].children[0].value;;
        let price = item[3].children[0].value;;
        let obj = {
            "width":width,
            "height":height,
            "stock":stock,
            "price":price
        }
        arrVariants.push(obj);

    }
    return arrVariants;
}
function getSpecs(){
    let specs = document.querySelectorAll(".spcItem");
    let arrSpecs = [];
    for (let i = 0; i < specs.length; i++) {
        let item = specs[i];
        let name = item.children[0].children[0].value;
        let value = item.children[1].children[0].value;
        let type = item.children[1].children[0].type;

        if(name !="" && value !=""){
            let obj = {
                "name":name,
                "value":value,
                "type":type
            }
            arrSpecs.push(obj);
        }
    }
    return arrSpecs;
}
function addVariant(){
    let tr = document.createElement("tr");
    tr.classList.add("variantItem");
    let html = `
        <td><input type="number" value="" class="form-control" placeholder="Ancho"></td>
        <td><input type="number" value="" class="form-control" placeholder="Alto"></td>
        <td><input type="number" value="" class="form-control" placeholder="Cantidad"></td>
        <td><input type="number" value="" class="form-control" placeholder="Precio"></td>
        <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
        `;
    tr.innerHTML=html;
    document.querySelector(".variantList").appendChild(tr);
    /*let variants = document.querySelectorAll(".variantItem");
    let flag = true;
    if(variants.length > 0){
        for (let i = 0; i < variants.length; i++) {
            let item = variants[i];
            if(item.getAttribute("attwidth") == width && item.getAttribute("attheight") == height){
                Swal.fire("Error","La variante ya ha sido agregada","error");
                flag = false;
                break;
            }
        }
        if(flag){
            let div = document.createElement("div");
            div.setAttribute("attwidth",width);
            div.setAttribute("attheight",height);
            div.setAttribute("attstock",stock);
            div.setAttribute("attprice",price);
            div.setAttribute("onclick","removeItem(this)");
            div.classList.add("variantItem","btn","btn-success", "text-white", "m-1");
            div.innerHTML = width+"-"+height+"-"+stock+"-"+price;
            document.querySelector(".variantList").appendChild(div);
            document.querySelector("#intVariantHeight").value = "";
            document.querySelector("#intVariantWidth").value = "";
            document.querySelector("#intVariantStock").value = 0;
            document.querySelector("#intVariantPrice").value = "";
        }
    }else{
        let div = document.createElement("div");
        div.setAttribute("attWidth",width);
        div.setAttribute("attHeight",height);
        div.setAttribute("attStock",stock);
        div.setAttribute("attPrice",price);
        div.setAttribute("onclick","removeItem(this)");
        div.classList.add("variantItem","btn","btn-success", "text-white", "m-1");
        div.innerHTML = width+"-"+height+"-"+stock+"-"+price;
        document.querySelector(".variantList").appendChild(div);
        document.querySelector("#intVariantHeight").value = "";
        document.querySelector("#intVariantWidth").value = "";
        document.querySelector("#intVariantStock").value = 0;
        document.querySelector("#intVariantPrice").value = "";
    }*/
    
}
function addSpec(type){
    let tr = document.createElement("tr");
    tr.classList.add("spcItem");
    let html="";
    if(type == 1){
        html = `
        <td><input type="text" value="" class="form-control" placeholder="Nombre dato"></td>
        <td><input type="text" value="" class="form-control" placeholder="Dato"></td>
        <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
        `;
    }else{
        html = `
        <td><input type="text" value="" class="form-control" placeholder="Nombre dato"></td>
        <td><input type="number" value="" class="form-control" placeholder="Dato"></td>
        <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
    `;
    }
    tr.innerHTML=html;
    document.querySelector(".spcList").appendChild(tr);
}
function removeItem(element){
    element.remove();
}
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
