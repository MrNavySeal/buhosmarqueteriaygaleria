'use strict';

const selectTypeSpc = document.querySelector("#selectTypeSpc");
const tableSpecs = document.querySelector("#tableSpecs");
const productVariant = document.querySelector("#productVariant");
const categoryList = document.querySelector("#categoryList");
const subcategoryList = document.querySelector("#subcategoryList");
const form = document.querySelector("#formItem");
const formFile = document.querySelector("#formFile");
const parent = document.querySelector("#upload-multiple");
const img = document.querySelector("#txtImg");
const btnAdd = document.querySelector("#btnAdd");
const selectProductType = document.querySelector("#selectProductType");
const btnSpc = document.querySelector("#btnSpc");
const selectFramingMode = document.querySelector("#framingMode");
const framingImg = document.querySelector("#txtImgFrame");
const checkProduct = document.querySelector("#checkProduct");
const checkIngredient = document.querySelector("#checkIngredient");
const checkRecipe = document.querySelector("#checkRecipe");
const checkInventory = document.querySelector("#checkInventory");
const selectMeasure = document.querySelector("#selectMeasure");
//let id = document.querySelector("#idProduct").value;
let arrSpecs = [];
let arrCategories = [];
let arrSpecsAdded = [];
let arrMeasures = [];
let imgLocation = ".uploadImg img";

/*************************Initial data*******************************/
request(base_url+"/Productos/getData","","get").then(function(objData){
    arrSpecs = objData.specs;
    arrCategories = objData.categories;
    arrMeasures = objData.measures;
    showOptions(arrSpecs,"specs");
    showOptions(arrCategories,"category");
    showOptions(arrMeasures,"measure");
});

/*************************Events*******************************/
checkRecipe.addEventListener("change",function(){
    checkIngredient.checked = false;
    checkProduct.checked = false;
    if(checkRecipe.checked){
        document.querySelector("#divPurchase").classList.add("d-none");
    }else{
        document.querySelector("#divPurchase").classList.remove("d-none");
    }
});
checkInventory.addEventListener("change",function(){
    if(checkInventory.checked){
        document.querySelector("#setStocks").classList.remove("d-none");
    }else{
        document.querySelector("#setStocks").classList.add("d-none");
    }
});
checkIngredient.addEventListener("change",function(){checkRecipe.checked = false;});
checkProduct.addEventListener("change",function(){checkRecipe.checked = false;});

selectFramingMode.addEventListener("change",function(){
    if(selectFramingMode.value == 1){
        document.querySelector(".framingImage").classList.remove("d-none");
    }else{
        document.querySelector(".framingImage").classList.add("d-none");
    }
});
productVariant.addEventListener("change",function(){
})
framingImg.addEventListener("change",function(){
    uploadImg(framingImg,imgLocation);
});

setImage(img,parent,"product");
delImage(parent);
setTinymce("#txtDescription");
/*************************Functions*******************************/
function save(){
    tinymce.triggerSave();
    const formData = new FormData(form);
    const strName = document.querySelector("#txtName").value;
    const strReference = document.querySelector("#txtReference").value;
    const intDiscount = document.querySelector("#txtPriceOffer").value;
    const intPrice = document.querySelector("#txtPrice").value;
    const intPurchase = document.querySelector("#txtPurchase").value;
    const intStatus = document.querySelector("#statusList").value;
    const intStock = document.querySelector("#txtStock").value;
    const intMinStock = document.querySelector("#txtMinStock").value;
    const strShortDescription = document.querySelector("#txtShortDescription").value;
    const strDescription = document.querySelector("#txtDescription").value;
    const images = document.querySelectorAll(".upload-image");
    const arrProductsType = Array.from(document.querySelectorAll(".product_type")).filter(el=>el.checked);
    const intId = document.querySelector("#id").value;
    const intImport = document.querySelector("#selectImport").value;

    if(strName == "" || intPrice==""){
        Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
        return false;
    }
    if(intPrice < 0){
        Swal.fire("Error","El precio de venta no puede ser inferior a 0","error"); 
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
            Swal.fire("Error","El precio de oferta no puede ser inferior a 0","error"); 
            document.querySelector("#txtDiscount").value="";
            return false;
        }
    }
    if(arrProductsType.length == 0){
        Swal.fire("Error","Debe seleccionar el tipo de artículo","error"); 
        return false;
    }
    
    if(!checkRecipe.checked){
       if(intPurchase ==""){
            Swal.fire("Error","El precio de compra no puede estar vacio","error"); 
            return false;
       }else if(intPurchase < 0){
            Swal.fire("Error","El precio de compra no puede ser inferior a 0","error"); 
            return false;
       }
    }
    if(checkInventory.checked){
        if(intMinStock == "" || intStock == "" ){
            Swal.fire("Error","El stock no puede estar vacio","error"); 
            return false;
        }else if(intMinStock < 0 || intMinStock < 0){
            Swal.fire("Error","El stock no puede ser negativo","error"); 
            return false;
        }
    }
    if(selectFramingMode.value == 1 && document.querySelector("#txtImgFrame").value == "" && id==0){
        Swal.fire("Error","Por favor, para el modo enmarcar, ingrese la foto a enmarcar","error");
        return false;
    }

    const arrData = {
        "general":{
            "images":getImages(images),
            "specs":getSpecs(),
            "status":intStatus,
            "id":intId,
            "subcategory":subcategoryList.value,
            "category":categoryList.value,
            "framing_mode":selectFramingMode.value,
            "measure":selectMeasure.value,
            "import":intImport,
            "is_product":checkProduct.checked,
            "is_ingredient":checkIngredient.checked,
            "is_combo":checkRecipe.checked,
            "is_stock":checkInventory.checked,
            "price_purchase":intPurchase,
            "price_sell":intPrice,
            "price_offer":intDiscount,
            "product_type":productVariant.checked,
            "stock":intStock,
            "min_stock":intMinStock,
            "short_description":strShortDescription,
            "description":strDescription,
            "name":strName,
            "reference":strReference
        }
    }

    //data.append("variants",JSON.stringify(getVariatns()));
    formData.append("data",JSON.stringify(arrData));
    btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAdd.setAttribute("disabled","");
    
    request(base_url+"/productos/setProduct",formData,"post").then(function(objData){
        btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
        btnAdd.removeAttribute("disabled");
        if(objData.status){
            if (intId == 0) {
                Swal.fire("Guardado",objData.msg,"success");
                setTimeout(function(){
                    window.location.href=base_url+"/productos";
                },3500);
            } else {
                Swal.fire("Guardado",objData.msg,"success");
            }
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function showOptions(arrData,type){
    let html ="<option selected>Seleccione</option>";
    for (let i = 0; i < arrData.length; i++) {
        if(type=="specs"){
            html+=`<option value="${i}">${arrData[i].name}</option>`;
        }else{
            html+=`<option value="${arrData[i].id}">${arrData[i].name}</option>`;
        }
    }
    if(type=="specs"){
        if(arrData.length > 0){
            document.querySelector("#activeSpecs").classList.remove("d-none");
            document.querySelector("#addSpecs").classList.add("d-none");
            selectTypeSpc.innerHTML = html;
        }else{
            document.querySelector("#activeSpecs").classList.add("d-none");
            document.querySelector("#addSpecs").classList.remove("d-none");
        }
        
    }
    if(type=="category"){
        if(arrData.length > 0){
            document.querySelector("#showCategories").classList.remove("d-none");
            document.querySelector("#toAddCategories").classList.add("d-none");
            categoryList.innerHTML = html;
        }else{
            document.querySelector("#showCategories").classList.add("d-none");
            document.querySelector("#toAddCategories").classList.remove("d-none");
        }
    }
    if(type=="measure"){
        selectMeasure.innerHTML = html;
        selectMeasure.value = 1;
    }
}
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
    let arrSpecs = arrSpecsAdded;
    for (let i = 0; i < specs.length; i++) {
        let item = specs[i];
        arrSpecs[i]['value'] = item.children[1].children[0].value;
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
function addSpec(){
    let index = parseInt(selectTypeSpc.value);
    if(!isNaN(index)){
        let objSpec = arrSpecs[index];
        if(arrSpecsAdded.length > 0){
            let flag = false;
            arrSpecsAdded.forEach(el=>{if(el.id_specification == objSpec.id_specification)flag=true;});
            if(flag)return false;
        }
        let tr = document.createElement("tr");
        tr.classList.add("spcItem");
        tr.setAttribute("data-id",arrSpecsAdded.length);
        tr.innerHTML = `
            <td>${objSpec.name}</td>
            <td><input type="text" value="" class="form-control" placeholder="Valor de la característica"></td>
            <td class="text-end"><button type="button" class="btn btn-danger text-white" onclick="removeSpec(this)"><i class="fas fa-trash"></i></button></td>
        `;
        arrSpecsAdded.unshift(objSpec);
        tableSpecs.appendChild(tr);
    }
}
function removeSpec(item){
    const element = item.parentElement.parentElement;
    const id = element.getAttribute("data-id");
    arrSpecsAdded.splice(id,1);
    element.remove();
    const elements = document.querySelectorAll(".spcItem");
    if(elements.length>0){
        let i = 0;
        elements.forEach(el => {el.setAttribute("data-id",i);i++});
    }
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
            uploadMultipleImg(element,parent,['upload-image','ms-3']);
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
function getImages(images){
    let arrImg =[];
    for (let i = 0; i < images.length; i++) {
        arrImg.push(images[i].getAttribute("data-rename"));
    }
    return arrImg;
}
function changeCategory(){
    let formData = new FormData();
    formData.append("idCategory",categoryList.value);
    request(base_url+"/ProductosCategorias/getSelectSubcategories",formData,"post").then(function(objData){
        document.querySelector("#subcategoryList").innerHTML = objData.data;
    });
}
