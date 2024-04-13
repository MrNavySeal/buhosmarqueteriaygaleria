'use strict';

const selectTypeSpc = document.querySelector("#selectTypeSpc");
const tableSpecs = document.querySelector("#tableSpecs");
const tableVariants = document.querySelector("#tableVariants");
const productVariant = document.querySelector("#productVariant");
const categoryList = document.querySelector("#categoryList");
const subcategoryList = document.querySelector("#subcategoryList");
const statusList = document.querySelector("#statusList");
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
const variantOptions = document.querySelector("#variantOptions");
const selectVariantOption = document.querySelector("#selectVariantOption");
const tableVariantsCombination = document.querySelector("#tableVariantsCombination");
const divTableVariant = document.querySelector("#divTableVariant");
const checkStockVariants = document.querySelector("#checkStockVariants");
const selectImport = document.querySelector("#selectImport");
const txtPurchase = document.querySelector("#txtPurchase");
const txtPrice = document.querySelector("#txtPrice");
const txtPriceOffer = document.querySelector("#txtPriceOffer");
//let id = document.querySelector("#idProduct").value;
let arrSpecs = [];
let arrCategories = [];
let arrSpecsAdded = [];
let arrVariantsAdded = [];
let arrMeasures = [];
let arrVariants = [];
let arrVariantsToMix = [];
let arrCombinations = [];
let imgLocation = ".uploadImg img";

/*************************Initial data*******************************/
if(document.querySelector("#id").value !=""){
    let id = document.querySelector("#id").value
    request(base_url+"/Productos/getProduct/"+id,"","get").then(function(objData){
        console.log(objData);
        const productData = objData.product;
        const initialData = objData.initial;
        let arrSubcategories = initialData.subcategories;
        arrSpecs = initialData.specs;
        arrCategories = initialData.categories;
        arrMeasures = initialData.measures;
        arrVariants = initialData.variants;
        showOptions(arrSpecs,"specs");
        showOptions(arrCategories,"category");
        showOptions(arrSubcategories,"subcategory");
        showOptions(arrMeasures,"measure");
        showOptions(arrVariants,"variants");
        document.querySelector("#txtName").value = productData.name;
        document.querySelector("#txtReference").value = productData.reference;
        document.querySelector("#txtShortDescription").value = productData.shortdescription;
        document.querySelector("#txtDescription").value = productData.description;
        checkProduct.checked = productData.is_product;
        checkIngredient.checked = productData.is_ingredient,
        checkRecipe.checked = productData.is_combo;
        checkInventory.checked = productData.is_stock,
        productVariant.checked = productData.product_type;
        txtPriceOffer.value = productData.discount;
        txtPrice.value = productData.price;
        txtPurchase.value = productData.price_purchase;
        categoryList.value =productData.idcategory;
        subcategoryList.value = productData.idsubcategory;
        selectMeasure.value = productData.measure;
        selectImport.value = productData.import;
        checkStockVariants.checked = productData.is_stock;
        selectFramingMode.value = productData.framing_mode;
        statusList.value = productData.status;
        if(selectFramingMode.value == 1){
            document.querySelector(".framingImage").classList.remove("d-none");
            document.querySelector(".uploadImg img").setAttribute("src",productData.framing_img);
        }else{
            document.querySelector(".framingImage").classList.add("d-none");
        }
        if(productVariant.checked){
            let combinations = productData.options;
            let html ="";
            let index =0;
            let disabled = productData.is_stock ? "" : "disabled";
            arrVariantsToMix = productData.variation.variation;
            variantOptions.classList.remove("d-none");
            tableVariantsCombination.classList.remove("d-none");
            combinations.forEach(c =>{
                let checked = c.status ? "checked" : "";
                html+=`
                <tr class="text-nowrap" data-name="${c.name}">
                    <td>${c.name}</td>
                    <td><input type="number" value="${c.price_purchase}" class="form-control pricePurchaseVariant"></td>
                    <td><input type="number" value="${c.price_sell}" class="form-control priceSellVariant"></td>
                    <td><input type="number" value="${c.price_offer}" class="form-control priceOfferVariant"></td>
                    <td class="d-flex">
                        <input type="number" value="${c.stock}" class="form-control stockVariant" ${disabled}>
                        <input type="number" value="${c.min_stock}" class="form-control minStockVariant" ${disabled}>
                    </td>
                    <td><input type="text" value="${c.sku}" class="form-control skuVariant"></td>
                    <td class="text-end">
                        <div class="form-check form-switch me-4">
                            <input class="form-check-input checkStatusVariant" type="checkbox" role="switch" ${checked}>
                        </div>
                    </td>
                </tr>
                `;
                index++;
            });
            arrVariantsToMix.forEach(v => {
                addVariant(v.id,v.options);
            });
            document.querySelector("#tableCombinations").innerHTML = html;
            //showVariants(getCombinationsVariant(arrVariantsToMix));
        }else if(checkInventory.checked){
            document.querySelector("#setStocks").classList.remove("d-none");
            document.querySelector("#txtStock").value = productData.stock;
            document.querySelector("#txtMinStock").value = productData.min_stock;
        }
        if(productData.specs.length>0){
            for (let i = 0; i < productData.specs.length; i++) {
                addSpec(productData.specs[i].id,productData.specs[i].value);
            }
        }
        
    });
}else{
    request(base_url+"/Productos/getData","","get").then(function(objData){
        arrSpecs = objData.specs;
        arrCategories = objData.categories;
        arrMeasures = objData.measures;
        arrVariants = objData.variants;
        showOptions(arrSpecs,"specs");
        showOptions(arrCategories,"category");
        showOptions(arrMeasures,"measure");
        showOptions(arrVariants,"variants");
    });
    
}

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
    checkStockVariants.checked =checkInventory.checked;
    if(checkInventory.checked && !productVariant.checked){
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
    if(productVariant.checked){
        variantOptions.classList.remove("d-none");
        document.querySelector("#setStocks").classList.add("d-none");
    }else{
        checkStockVariants.checked = false;
        checkInventory.checked = false;
        variantOptions.classList.add("d-none");
    }
})
framingImg.addEventListener("change",function(){
    uploadImg(framingImg,imgLocation);
});
checkStockVariants.addEventListener("change",function(){
    checkInventory.checked = checkStockVariants.checked;
    if(document.querySelector(".minStockVariant")){
        const arrMinStock = document.querySelectorAll(".minStockVariant");
        const arrStock = document.querySelectorAll(".stockVariant");
        for (let i = 0; i < arrMinStock.length; i++) {
            if(checkStockVariants.checked){
                arrStock[i].removeAttribute("disabled");
                arrMinStock[i].removeAttribute("disabled");
            }else{
                arrStock[i].setAttribute("disabled","");
                arrMinStock[i].setAttribute("disabled","");
            }
        }
    }
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
    const combinations = getInfoCombinationVariants();
    if(strName == ""){
        Swal.fire("Error","El nombre no puede estar vacio","error");
        return false;
    }
    if(categoryList.value == "selected"){
        Swal.fire("Error","Debe seleccionar una categoría","error");
        return false;
    }
    if(subcategoryList.value == "selected" || subcategoryList.value == ""){
        Swal.fire("Error","Debe seleccionar una subcategoría","error");
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
    if(!productVariant.checked){
        if(intPrice < 0 || intPrice ==""){
            Swal.fire("Error","El precio de venta no puede ser inferior a 0","error"); 
            return false;
        }
        if(!checkRecipe.checked){
            if(intPurchase =="" || intPurchase < 0){
                 Swal.fire("Error","El precio de compra no puede estar vacio","error"); 
                 return false;
            }
         }
        if(intDiscount !=""){
            if(intDiscount < 0){
                Swal.fire("Error","El precio de oferta no puede ser inferior a 0","error"); 
                document.querySelector("#txtDiscount").value="";
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
    }
    if(productVariant.checked && combinations.length == 0){
        Swal.fire("Error","Si el producto tiene variantes, agregue al menos una.","error"); 
        return false;
    }
    if(arrProductsType.length == 0){
        Swal.fire("Error","Debe seleccionar el tipo de artículo","error"); 
        return false;
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
            "import":selectImport.value,
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
            "reference":strReference,
        },
        "combinations": combinations,
        "variants":arrVariantsToMix,
        "is_stock":checkStockVariants.checked
    }
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
                    window.location.reload();
                },3500);
            } else {
                Swal.fire("Guardado",objData.msg,"success");
                setTimeout(function(){
                    window.location.reload();
                },3500);
            }
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function showOptions(arrData,type){
    let html =`<option value="selected" disabled selected>Seleccione</option>`;
    for (let i = 0; i < arrData.length; i++) {
        html+=`<option value="${arrData[i].id}">${arrData[i].name}</option>`;
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
    if(type=="subcategory")subcategoryList.innerHTML = html;
    if(type=="measure"){
        selectMeasure.innerHTML = html;
        selectMeasure.value = 1;
    }
    if(type=="variants"){
        selectVariantOption.innerHTML = html;
    }
}
function getSpecs(){
    let specs = document.querySelectorAll(".spcItem");
    let arrSpecs = [];
    for (let i = 0; i < specs.length; i++) {
        let item = specs[i];
        let id = item.getAttribute("data-id");
        let value = item.children[1].children[0].value;
        arrSpecs.push({id:id,value:value});
    }
    return arrSpecs;
}
function getInfoCombinationVariants(){
    let newArr = [];
    let obj = {};
    let flag = false;
    if(productVariant.checked){
        arrCombinations = getCombinationsVariant(arrVariantsToMix);
        const arrPurchase = document.querySelectorAll(".pricePurchaseVariant");
        const arrSell = document.querySelectorAll(".priceSellVariant");
        const arrOffer = document.querySelectorAll(".priceOfferVariant");
        const arrStock = document.querySelectorAll(".stockVariant");
        const arrMinStock = document.querySelectorAll(".minStockVariant");
        const arrSkuVariant = document.querySelectorAll(".skuVariant");
        const arrStatusVariant = document.querySelectorAll(".checkStatusVariant");
        
        for (let i = 0; i < arrCombinations.length; i++) {
            if(arrSell[i].value ==""){
                Swal.fire("Error","El precio de venta de la variante es obligatorio","error");
                flag = true;
                break;
            }
            if(checkStockVariants.checked){
                if(arrStock[i].value =="" || arrMinStock[i].value ==""){
                    Swal.fire("Error","El stock de la variante es obligatorio","error");
                    flag = true;
                    break;
                }
            }
            obj = {
                name:arrCombinations[i].join("-"),
                price_purchase:arrPurchase[i].value,
                price_sell:arrSell[i].value,
                price_offer:arrOffer[i].value,
                stock:arrStock[i].value,
                min_stock:arrMinStock[i].value,
                sku:arrSkuVariant[i].value,
                status:arrStatusVariant[i].checked,
            }
            
            newArr.push(obj);
        }
    }
    arrCombinations = newArr;
    if(flag){
        arrCombinations = [];
    }
    return arrCombinations;
}
function showVariants(combinations){
    let table = document.querySelector("#tableCombinations");
    let html="";
    let currentMix = [];
    let arrComb = [];
    /*combinations.forEach(c => arrComb.push(c.join("/")));
    const currentComb= Array.from(table.children);
    if(currentComb.length>0){
        currentComb.forEach(c => currentMix.push(c.getAttribute("data-name")));
        html = table.innerHTML;
    }*/
    tableVariantsCombination.classList.add("d-none");
    
    if(combinations.length > 0){
        tableVariantsCombination.classList.remove("d-none");
        for (let i = 0; i < combinations.length; i++) {
            let name = combinations[i].join("/");
            html+=`
                <tr class="text-nowrap" data-name="${name}">
                    <td>${name}</td>
                    <td><input type="number" value="" class="form-control pricePurchaseVariant"></td>
                    <td><input type="number" value="" class="form-control priceSellVariant"></td>
                    <td><input type="number" value="" class="form-control priceOfferVariant"></td>
                    <td class="d-flex">
                        <input type="number" value="" class="form-control stockVariant" disabled>
                        <input type="number" value="" class="form-control minStockVariant" disabled>
                    </td>
                    <td><input type="text" class="form-control skuVariant"></td>
                    <td class="text-end">
                        <div class="form-check form-switch me-4">
                            <input class="form-check-input checkStatusVariant" type="checkbox" role="switch" checked>
                        </div>
                    </td>
                </tr>
                `;
        }
    }
    table.innerHTML = html;
}
function addOptionsVariant(){
    const table = document.querySelector("#tableCombinations");
    const currentComb= Array.from(table.children);
    
    let arrOptionsToMix = [];
    let arrMix = [];
    let currentMix = [];
    if(currentComb.length>0){
        currentComb.forEach(c => currentMix.push(c.getAttribute("data-name")));
    }
    const parents = document.querySelectorAll(".variantItem");
    for (let i = 0; i < parents.length; i++) {
        const idVariant = parents[i].getAttribute("data-id");
        const children = parents[i].children[1].children[0].children;
        for (let j = 0; j < children.length; j++) {
            const optionEl = children[j].children[0];
            if(optionEl.checked){
                arrOptionsToMix.push(optionEl.getAttribute("data-name"));
            }
        }
        if(arrOptionsToMix.length > 0){
            arrMix.push({id:idVariant,options:arrOptionsToMix});
        }
        arrOptionsToMix = [];
    }
    arrVariantsToMix = arrMix;
    let combination = getCombinationsVariant(arrVariantsToMix);
    showVariants(combination);
}
function getCombinationsVariant(variants){
    let result = [];
    if(variants.length>0){  
        function addOption( oldMix, newOptions){
            let newMix = [];
            oldMix.forEach(ol=>{
                newOptions.forEach(ne =>{
                    newMix.push([...ol,ne]);
                })
            })
            return newMix;
        }
        result = variants[0].options.map(option => [option]);
        for (let i = 1; i < variants.length; i++) {
            result = addOption(result,variants[i].options);
        }
    }
    return result;
}
function addVariant(id="",options=[]){
    let variantVal = id!= "" ? id : parseInt(selectVariantOption.value);
    if(!isNaN(variantVal)){
        let obj = arrVariants.filter(el=>el.id == variantVal)[0];
        let objOptions = obj.options;
        let html="";
        let index = 0;
        if(arrVariantsAdded.length > 0){
            let flag = false;
            arrVariantsAdded.forEach(el=>{if(el.id == variantVal)flag=true;});
            if(flag)return false;
        }
        objOptions.forEach(op=>{
            let checked = "";
            if(options.length > 0){
                checked = options.includes(op.name) ? "checked" : "";
            }
            html+=`
            <div class="form-check form-switch m-2">
                <input class="form-check-input" type="checkbox" role="switch"  data-id="${op.id_options}" data-name="${op.name}"
                onchange="addOptionsVariant()" id="flexSwitchCheckDefault${op.id_options}" ${checked}>
                <label class="ms-2 form-check-label" for="flexSwitchCheckDefault${op.id_options}">${op.name}</label>
            </div>
            `;
            index++;
        })
        let tr = document.createElement("tr");
        tr.classList.add("variantItem");
        tr.setAttribute("data-id",obj.id);
        tr.innerHTML = `
            <td>${obj.name}</td>
            <td ><div class="d-flex flex-wrap justify-between">${html}</div></td>
            <td class="text-end"><button type="button" class="btn btn-danger text-white" onclick="removeVariant(this,${obj.id})"><i class="fas fa-trash"></i></button></td>
        `;
        arrVariantsAdded.unshift(obj);
        tableVariants.appendChild(tr);
        if(arrVariantsAdded.length > 0){
            divTableVariant.classList.remove("d-none");
        }
    }
}
function addSpec(id="",value=""){
    let index = id!= "" ? id : parseInt(selectTypeSpc.value);
    if(!isNaN(index)){
        let objSpec =  arrSpecs.filter(el=>el.id == index)[0];
        if(arrSpecsAdded.length > 0){
            let flag = false;
            arrSpecsAdded.forEach(el=>{if(el.id == objSpec.id)flag=true;});
            if(flag)return false;
        }
        let tr = document.createElement("tr");
        tr.classList.add("spcItem");
        tr.setAttribute("data-id",objSpec.id);
        tr.innerHTML = `
            <td>${objSpec.name}</td>
            <td><input type="text" value="${value}" class="form-control" placeholder="Valor de la característica"></td>
            <td class="text-end"><button type="button" class="btn btn-danger text-white" onclick="removeItem(this,'spec')"><i class="fas fa-trash"></i></button></td>
        `;
        arrSpecsAdded.unshift(objSpec);
        tableSpecs.appendChild(tr);
    }
}
function removeVariant(item,id){
    const element = item.parentElement.parentElement;
    for (let i = 0; i < arrVariantsAdded.length; i++) {
        if(arrVariantsAdded[i].id == id){
            arrVariantsAdded.splice(i,1);
            break;
        }
    }
    for (let i = 0; i < arrVariantsToMix.length; i++) {
        if(arrVariantsToMix[i].id == id){
            arrVariantsToMix.splice(i,1);
            break;
        }
    }
    if(arrVariantsAdded.length == 0){
        divTableVariant.classList.add("d-none");
        checkStockVariants.checked = "";
        document.querySelector("#tableCombinations").innerHTML ="";
    }
    element.remove();
    addOptionsVariant();
}
function removeItem(item,type){
    const element = item.parentElement.parentElement;
    const id = element.getAttribute("data-id");
    for (let i = 0; i < arrSpecsAdded.length; i++) {
        if(arrSpecsAdded[i].id == id){
            arrSpecsAdded.splice(i,1);
            break;
        }
    }
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
