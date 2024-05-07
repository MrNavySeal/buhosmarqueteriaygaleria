
const tablePurchase = document.querySelector("#tablePurchase");
const tableProducts = document.querySelector("#tableProducts");
const searchProduct = document.querySelector("#searchProduct");
const modalVariant = new bootstrap.Modal(document.querySelector("#modalVariant"));
const modalPurchase = new bootstrap.Modal(document.querySelector("#modalPurchase"));
const modalSelectvariants = document.querySelector("#modalSelectvariants");
const modalVariantCost = document.querySelector("#modalVariantCost");
const modalVariantName = document.querySelector("#modalVariantName");
const btnAdd = document.querySelector("#btnAdd");
const btnPurchase = document.querySelector("#btnPurchase");
const btnClean = document.querySelector("#btnClean");
const btnSetPurchase = document.querySelector("#btnSetPurchase");
const searchItems = document.querySelector("#searchItems");
const selectItems = document.querySelector("#selectItems");
const items = document.querySelector("#items");
const formSetOrder = document.querySelector("#formSetOrder");
let product;
let arrProducts = [];
let arrSuppliers = [];
window.addEventListener("load",function(){
    getProducts();
    getSuppliers();
});

btnAdd.addEventListener("click",function(){
    addProduct(product);
    modalVariant.hide();
});
btnPurchase.addEventListener("click",function(){
    modalPurchase.show();
});
btnClean.addEventListener("click",function(){
    arrProducts = [];
    tablePurchase.innerHTML ="";
    document.querySelector("#subtotalProducts").innerHTML = "$0";
    document.querySelector("#ivaProducts").innerHTML = "$0";
    document.querySelector("#discountProducts").innerHTML = "$0";
    document.querySelector("#totalProducts").innerHTML = "$0";
});
formSetOrder.addEventListener("submit",function(e){
    e.preventDefault();

    if(document.querySelector("#id").value == ""){
        Swal.fire("Error","Debe seleccionar el proveedor","error");
        return false;
    }
    
    let url = base_url+"/Compras/setPurchase";
    let formData = new FormData(formSetOrder);
    formData.append("products",JSON.stringify(arrProducts));
    formData.append("total",JSON.stringify(currentTotal()));
    btnSetPurchase.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnSetPurchase.setAttribute("disabled","");
    request(url,formData,"post").then(function(objData){
        btnSetPurchase.innerHTML=`<i class="fas fa-save"></i> Guardar`;
        btnSetPurchase.removeAttribute("disabled");
        if(objData.status){
            Swal.fire("Guardado",objData.msg,"success");
            formSetOrder.reset();
            arrProducts = [];
            tablePurchase.innerHTML ="";
            document.querySelector("#subtotalProducts").innerHTML = "$0";
            document.querySelector("#ivaProducts").innerHTML = "$0";
            document.querySelector("#discountProducts").innerHTML = "$0";
            document.querySelector("#totalProducts").innerHTML = "$0";
            searchItems.parentElement.classList.remove("d-none");
            document.querySelector("#id").value = 0;
            document.querySelector("#selectedItem").innerHTML="";
            modalPurchase.hide();
            getProducts();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
})
searchItems.addEventListener('input',function() {
    items.innerHTML ="";
    let search = searchItems.value.toLowerCase();
    let arrToShow = arrSuppliers.filter(
        s =>s.name.toLowerCase().includes(search) 
        || s.nit.toLowerCase().includes(search)
        || s.phone.toLowerCase().includes(search)
    );
    arrToShow.forEach(e => {
        let btn = document.createElement("button");
        btn.classList.add("p-2","btn","w-100","text-start");
        btn.setAttribute("data-id",e.id_supplier);
        btn.setAttribute("onclick","addItem(this)");
        btn.innerHTML = `
            <p class="m-0 fw-bold">${e.name}</p>
            <p class="m-0">CC/NIT: <span>${e.nit}</span></p>
            <p class="m-0">Correo: <span>${e.email}</span></p>
            <p class="m-0">Teléfono: <span>${e.phone}</span></p>
        `
        items.appendChild(btn);
    });
});
searchProduct.addEventListener("input",function(){
    getProducts(searchProduct.value);
});
/*************************functions to select item from search suppliers*******************************/
function addItem(element){
    element.setAttribute("onclick","delItem(this)");
    element.classList.add("border","border-primary");
    document.querySelector("#selectedItem").appendChild(element);
    document.querySelector("#items").innerHTML = "";
    document.querySelector("#id").value = element.getAttribute("data-id");
    searchItems.parentElement.classList.add("d-none");
}
function delItem(element){
    searchItems.parentElement.classList.remove("d-none");
    document.querySelector("#id").value = 0;
    element.remove();
}
function getSuppliers(){
    request(base_url+"/compras/getSuppliers","","get").then(function(res){
        arrSuppliers = res;
    });
}
/*************************functions to get products*******************************/
function getProducts(search=""){
    const formData = new FormData();
    formData.append("search",search);
    request(base_url+"/compras/getProducts",formData,"post").then(function(res){
        tableProducts.innerHTML = res;
    });
}
function getProduct(element,id){
    const formData = new FormData();
    formData.append("id",id);
    element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;  
    element.setAttribute("disabled","");
    request(base_url+"/compras/getProduct",formData,"post").then(function(res){
        element.innerHTML='<i class="fas fa-plus"></i>';
        element.removeAttribute("disabled","");
        if(res.status){
            product = res.data;
            if(product.product_type){
                displayVariants(product);
            }else{
                addProduct(product)
            }
        }else{
            Swal.fire("Error",res.msg,"error");
        }

    });
}
/*************************functions to add and update products*******************************/
function addProduct(product){
    
    let obj = {
        "id":product.idproduct,
        "is_stock":product.is_stock,
        "stock":product.stock,
        "qty":1,
        "price_purchase":product.price_purchase,
        "price_sell":product.price,
        "price_base":0,
        "discount":0,
        "discount_percent":0,
        "reference":product.reference,
        "product_type":product.product_type,
        "name":product.name,
        "import":product.import,
        "subtotal":0,
        "variant_name":""
    };
    if(product.product_type == 1){
        obj.variant_name = product.variant_name;
    }
    if(arrProducts.length > 0){
        let flag = false;
        for (let i = 0; i < arrProducts.length; i++) {
            if(arrProducts[i].product_type){
                if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference
                    && arrProducts[i].name == obj.name && arrProducts[i].variant_name == obj.variant_name
                 ){
                    arrProducts[i].qty +=obj.qty 
                    flag = false;
                    break;
                 }
            }else if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference && arrProducts[i].name == obj.name){
                    arrProducts[i].qty +=obj.qty
                    flag = false;
                    break;
            }
            flag = true;
        }
        if(flag){
            arrProducts.push(obj);
        }
    }else{
        arrProducts.push(obj);
    }
    showProducts();
} 
function updateProduct(element,type,data){
    let obj = JSON.parse(data);
    let discount = 0;
    let discountPercent = 0;
    let subtotal = 0;
    if(type == "discount"){
        let value = parseInt(element.value);
        discount = value > 0 && value <= 100 ? value/100: 0;
        discountPercent =  value > 0 && value <= 100 ? value : 0;
        console.log(discountPercent);
    }
    let value = parseInt(element.value);
    for (let i = 0; i < arrProducts.length; i++) {
        let iva = 1+(arrProducts[i].import/100);
        if(arrProducts[i].product_type){
            if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference
                && arrProducts[i].name == obj.name && arrProducts[i].variant_name == obj.variant_name
             ){
                if(type =="qty"){
                    arrProducts[i].qty = value;
                }else if(type=="price_purchase"){
                    arrProducts[i].price_base = Math.round(value/iva);
                    arrProducts[i].price_purchase = value;
                }else if(type=="price_base"){
                    arrProducts[i].price_purchase = Math.round(value*iva);
                    arrProducts[i].price_base = value;
                }else if(type=="price_sell"){
                    arrProducts[i].price_sell = value;
                }
                subtotal = arrProducts[i].qty * arrProducts[i].price_purchase;
                totalDiscount = Math.round(subtotal*discount);
                subtotal = subtotal-totalDiscount;
                arrProducts[i].subtotal = subtotal;
                arrProducts[i].discount = totalDiscount;
                arrProducts[i].discount_percent = discountPercent;
                break;
             }
        }else if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference && arrProducts[i].name == obj.name){
            if(type =="qty"){
                arrProducts[i].qty = value;
            }else if(type=="price_purchase"){
                arrProducts[i].price_base = Math.round(value/iva);
                arrProducts[i].price_purchase = value;
            }else if(type=="price_base"){
                arrProducts[i].price_purchase = Math.round(value*iva);
                arrProducts[i].price_base = value;
            }else if(type=="price_sell"){
                arrProducts[i].price_sell = value;
            }
            subtotal = arrProducts[i].qty * arrProducts[i].price_purchase;
            totalDiscount = Math.round(subtotal*discount);
            subtotal = subtotal-totalDiscount;
            arrProducts[i].subtotal = subtotal;
            arrProducts[i].discount = totalDiscount;
            arrProducts[i].discount_percent = discountPercent;
            break;
        }
    }
    currentProducts();
}
function currentTotal(){
    let subtotal = 0;
    let iva = 0;
    let discount = 0;
    let total = 0;

    arrProducts.forEach(p=>{
        subtotal+=p.price_purchase*p.qty;
        discount+=p.discount;
        iva+= p.import > 0 ? (p.price_base*p.qty) : 0;
    });
    total = subtotal-discount;
    document.querySelector("#subtotalProducts").innerHTML = "$"+formatNum(subtotal,".");
    document.querySelector("#ivaProducts").innerHTML = "$"+formatNum(iva,".");
    document.querySelector("#discountProducts").innerHTML = "$"+formatNum(discount,".");
    document.querySelector("#totalProducts").innerHTML = "$"+formatNum(total,".");
    document.querySelector("#totalPurchase").innerHTML = "$"+formatNum(total,".");
    return {"subtotal":subtotal,"total":total,"iva":iva,"discount":discount}
}
function currentProducts(){
    let rows = document.querySelectorAll(".productToBuy");
    for (let i = 0; i < arrProducts.length; i++) {
        let children = rows[i].children;
        children[2].children[0].value = arrProducts[i].qty; //Cantidad
        children[3].children[0].value = arrProducts[i].price_base; //Precio base
        children[5].children[0].value = arrProducts[i].price_purchase; //Precio compra
        children[6].children[0].value = arrProducts[i].price_sell; //Precio de venta
        children[7].children[0].value = arrProducts[i].discount_percent; //Descuento
        children[8].innerHTML = "$"+formatNum(arrProducts[i].subtotal,".");//Subtotal
    }
    currentTotal();
}
function deleteProduct(element,data){
    let obj = JSON.parse(data);
    let parent = element.parentElement.parentElement;
    let index = 0;
    for (let i = 0; i < arrProducts.length; i++) {
        if(arrProducts[i].product_type){
            if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference
                && arrProducts[i].name == obj.name && arrProducts[i].variant_name == obj.variant_name
             ){
                index = i;
                break;
             }
        }else if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference && arrProducts[i].name == obj.name){
            index = i;
            break;
        }
    }
    arrProducts.splice(index,1);
    parent.remove();
    currentProducts();
}
function showProducts(){
    tablePurchase.innerHTML ="";
    arrProducts.forEach(pro=>{
        let iva = 1+(pro.import/100);
        pro.price_base = Math.round(pro.price_purchase/iva);
        pro.subtotal = (pro.qty * pro.price_purchase)-pro.discount;
        let tr = document.createElement("tr");
        tr.classList.add("productToBuy");
        let objString = JSON.stringify(pro).replace(/"/g, '&quot;');
        tr.innerHTML = `
            <td>${pro.is_stock ? pro.stock : "N/A"}</td>
            <td>
                <p class="m-0 mb-1">${pro.name}</p>
                <p class="text-secondary m-0 mb-1">${pro.reference}</p>
                <p class="text-secondary m-0 mb-1">${pro.variant_name}</p>
            </td>
            <td><input class="form-control text-center" onchange="updateProduct(this,'qty','${objString}')" value="${pro.qty}" type="number"></td>
            <td><input class="form-control" value="${pro.price_base}" onchange="updateProduct(this,'price_base','${objString}')" type="number"></td>
            <td>${pro.import}</td>
            <td><input class="form-control" value="${pro.price_purchase}" onchange="updateProduct(this,'price_purchase','${objString}')" type="number"></td>
            <td><input class="form-control" value="${pro.price_sell}" onchange="updateProduct(this,'price_sell','${objString}')" type="number"></td>
            <td><input class="form-control" value="${pro.discount_percent}" onchange="updateProduct(this,'discount','${objString}')" value="" type="number"></td>
            <td class="text-end">$${formatNum(pro.subtotal,".")}</td>
            <td><button class="btn btn-danger m-1 text-white" onclick="deleteProduct(this,'${objString}')"type="button"><i class="fas fa-trash-alt"></i></button></td>
        `;
        tablePurchase.appendChild(tr);
    });
    currentTotal();
}
function displayVariants(data){
    const variants = data.variation.variation;
    const option = data.options;
    modalSelectvariants.innerHTML ="";
    for (let i = 0; i < variants.length; i++) {
        let html="";
        let options = variants[i].options;
        let div = document.createElement("div");
        div.classList.add("mb-3");
        for (let j = 0; j < options.length; j++) {
            $active = j==0? "btn-primary" : "btn-secondary";
            html+=`<button type="button" class="btn ${$active} m-1 btnVariant" onclick="selectVariant(this)" data-name="${options[j]}">${options[j]}</button>`;
        }
        div.innerHTML = `
        <p class="t-color-3 m-0">${variants[i].name}</p>
        <div class="flex">${html}</div>
        `;
        modalSelectvariants.appendChild(div);
    }
    modalVariantCost.innerHTML = "Costo: "+option[0].format_purchase;
    modalVariantName.innerHTML = data.reference!="" ? data.reference+" "+data.name : data.name;
    let selectedVariants = document.querySelectorAll(".btn-primary.btnVariant");
    let arrSelected = [];
    selectedVariants.forEach(element => {
        arrSelected.push(element.getAttribute("data-name"));
    });
    //Agrego al producto la variante escogida por defecto
    let variant = arrSelected.join("-");
    let selectedOption = data.options.filter(op=>op.name == variant)[0];
    product['variant_name'] = variant;
    product['price'] = selectedOption.price_sell;
    product['price_purchase'] = selectedOption.price_purchase;
    product['stock'] = selectedOption.stock;
    openModal("variant");
}
function selectVariant(element){
    let options = product.options;
    let contentVariants = element.parentElement;
    let variants = contentVariants.children;
    for (let i = 0; i < variants.length; i++) {
        variants[i].classList.replace("btn-primary","btn-secondary");
    }
    element.classList.replace("btn-secondary","btn-primary");
    let selectedVariants = document.querySelectorAll(".btn-primary.btnVariant");
    let arrSelected = [];
    selectedVariants.forEach(element => {
        arrSelected.push(element.getAttribute("data-name"));
    });
    //Agrego al producto la variante escogida
    let variant = arrSelected.join("-");
    let selectedOption = options.filter(op=>op.name == variant)[0];
    modalVariantCost.innerHTML = "Costo: "+selectedOption.format_purchase;
    product['variant_name'] = variant;
    product['price'] = selectedOption.price_sell;
    product['price_purchase'] = selectedOption.price_purchase;
    product['stock'] = selectedOption.stock;
}
function openModal(option){
    modalVariant.show();
}