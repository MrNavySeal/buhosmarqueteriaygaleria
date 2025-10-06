
const tablePurchase = document.querySelector("#tablePurchase");
const tableProducts = document.querySelector("#tableProducts");
const searchProduct = document.querySelector("#searchProduct");
const modalPurchase = new bootstrap.Modal(document.querySelector("#modalPurchase"));
const btnAdd = document.querySelector("#btnAdd");
const btnPurchase = document.querySelector("#btnPurchase");
const btnClean = document.querySelector("#btnClean");
const btnSetPurchase = document.querySelector("#btnSetPurchase");
const searchItems = document.querySelector("#searchItems");
const selectItems = document.querySelector("#selectItems");
const items = document.querySelector("#items");
const formSetOrder = document.querySelector("#formSetOrder");
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
let arrProducts = [];
let arrSuppliers = [];
let arrData = [];
window.addEventListener("load",function(){
    getProducts();
    getSuppliers();
    document.querySelector("#txtDate").value = new Date().toISOString().split("T")[0];
});

btnPurchase.addEventListener("click",function(){
    getSuppliers();
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
    
    let url = base_url+"/Compras/Compras/setPurchase";
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
            document.querySelector("#txtDate").value = new Date().toISOString().split("T")[0];
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
searchHtml.addEventListener("input",function(){getProducts();});
perPage.addEventListener("change",function(){getProducts();});
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
    request(base_url+"/Compras/Compras/getSuppliers","","get").then(function(res){
        arrSuppliers = res;
    });
}
/*************************functions to get products*******************************/
async function getProducts(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/Compras/Compras/getProducts",{method:"POST",body:formData});
    const objData = await response.json();
    const arrHtml = objData.html;
    arrData = objData.data;
    tableProducts.innerHTML =arrHtml.products;
    document.querySelector("#pagination").innerHTML = arrHtml.pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}
/*************************functions to add and update products*******************************/
function addProduct(id,variantName,productType){
    const product = arrData.filter((e)=>{
        if(productType){
            return e.id == id && variantName == e.variant_name;
        }else{
            return e.id == id;
        }
    })[0];
    let obj = {
        "id":id,
        "is_stock":product.is_stock,
        "stock":product.stock,
        "qty":1,
        "price_purchase":product.price_purchase,
        "price_sell":product.price_sell,
        "price_base":0,
        "discount":0,
        "discount_percent":0,
        "reference":product.reference,
        "product_type":productType,
        "name":product.name,
        "import":product.import,
        "subtotal":0,
        "variant_name":variantName
    };
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
function updateProduct(element,type,id,variantName){
    let discount = 0;
    let discountPercent = 0;
    let subtotal = 0;
    if(type == "discount"){
        let value = parseFloat(element.value);
        discount = value > 0 && value <= 100 ? value/100: 0;
        discountPercent =  value > 0 && value <= 100 ? value : 0;
    }
    let value = parseFloat(element.value);
    for (let i = 0; i < arrProducts.length; i++) {
        let iva = 1+(arrProducts[i].import/100);
        if(arrProducts[i].product_type){
            if(arrProducts[i].id == id && arrProducts[i].variant_name == variantName){
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
        }else if(arrProducts[i].id == id){
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
        children[3].children[0].value = arrProducts[i].qty; //Cantidad
        children[4].children[0].value = arrProducts[i].price_base; //Precio base
        children[6].children[0].value = arrProducts[i].price_purchase; //Precio compra
        children[7].children[0].value = arrProducts[i].price_sell; //Precio de venta
        children[8].children[0].value = arrProducts[i].discount_percent; //Descuento
        children[9].innerHTML = "$"+formatNum(arrProducts[i].subtotal,".");//Subtotal
    }
    currentTotal();
}
function deleteProduct(element,id,variantName){
    const parent = element.parentElement.parentElement;
    let index = 0;
    for (let i = 0; i < arrProducts.length; i++) {
        if(arrProducts[i].product_type){
            if(arrProducts[i].id == id && arrProducts[i].variant_name == variantName){
                index = i;
                break;
             }
        }else if(arrProducts[i].id == id){
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
        tr.innerHTML = `
            <td data-title="Stock">${pro.is_stock ? pro.stock : "N/A"}</td>
            <td data-title="Referencia">${pro.reference}</td>
            <td data-title="Artículo">${pro.name}</td>
            <td data-title="Cantidad"><input class="form-control text-center" onchange="updateProduct(this,'qty','${pro.id}','${pro.variant_name}')" value="${pro.qty}" type="number"></td>
            <td data-title="Valor base"><input class="form-control" value="${pro.price_base}" onchange="updateProduct(this,'price_base','${pro.id}','${pro.variant_name}')" type="number"></td>
            <td data-title="IVA">${pro.import}</td>
            <td data-title="Valor compra"><input class="form-control" value="${pro.price_purchase}" onchange="updateProduct(this,'price_purchase','${pro.id}','${pro.variant_name}')" type="number"></td>
            <td data-title="Valor venta"><input class="form-control" value="${pro.price_sell}" onchange="updateProduct(this,'price_sell','${pro.id}','${pro.variant_name}')" type="number"></td>
            <td data-title="Descuento(%)"><input class="form-control" value="${pro.discount_percent}" onchange="updateProduct(this,'discount','${pro.id}','${pro.variant_name}')" value="" type="number"></td>
            <td data-title="Subtotal" class="text-end">$${formatNum(pro.subtotal,".")}</td>
            <td data-title="Opciones"><button class="btn btn-danger m-1 text-white" onclick="deleteProduct(this,'${pro.id}','${pro.variant_name}')"type="button"><i class="fas fa-trash-alt"></i></button></td>
        `;
        tablePurchase.appendChild(tr);
    });
    currentTotal();
}
function openModal(option){
    modalVariant.show();
}