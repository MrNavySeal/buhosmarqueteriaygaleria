'use strict';

const modalVariant = new bootstrap.Modal(document.querySelector("#modalVariant"));
const modalPurchase = new bootstrap.Modal(document.querySelector("#modalPurchase"));
const btnAdd = document.querySelector("#btnAdd");
const btnPurchase = document.querySelector("#btnPurchase");
const btnClean = document.querySelector("#btnClean");
const btnSetPurchase = document.querySelector("#btnSetPurchase");
const searchItems = document.querySelector("#searchItems");
const selectItems = document.querySelector("#selectItems");
const items = document.querySelector("#items");
const formSetOrder = document.querySelector("#formSetOrder");
const tablePurchase = document.querySelector("#tablePurchase");

let arrProducts = [];
let arrCustomers = [];
let product;
let table = new DataTable("#tableData",{
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/PedidosPos/getProducts",
        "dataSrc":""
    },
    "initComplete":function( settings, json){
        //arrProducts = json;
    },
    columns: [
        { 
            data: 'image',
            render: function (data, type, full, meta) {
                return '<img src="'+data+'" class="rounded" height="50" width="50">';
            }
        },
        { data: 'stock' },
        { data: 'name' },
        { data: 'format_price' },
        { data: 'options' },
    ],
    responsive: true,
    order: [[0, 'desc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});


window.addEventListener("load",function(){
    getCustomers();
});
/*************************Events*******************************/
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
    document.querySelector("#discountProducts").innerHTML = "$0";
    document.querySelector("#totalProducts").innerHTML = "$0";
});
searchItems.addEventListener('input',function() {
    items.innerHTML ="";
    let search = searchItems.value.toLowerCase();
    let arrToShow = arrCustomers.filter(
        s =>s.name.toLowerCase().includes(search) 
        || s.identification.toLowerCase().includes(search)
        || s.phone.toLowerCase().includes(search)
    );
    arrToShow.forEach(e => {
        let btn = document.createElement("button");
        btn.classList.add("p-2","btn","w-100","text-start");
        btn.setAttribute("data-id",e.idperson);
        btn.setAttribute("onclick","addItem(this)");
        btn.innerHTML = `
            <p class="m-0 fw-bold">${e.name}</p>
            <p class="m-0">CC/NIT: <span>${e.identification}</span></p>
            <p class="m-0">Correo: <span>${e.email}</span></p>
            <p class="m-0">Teléfono: <span>${e.phone}</span></p>
        `
        items.appendChild(btn);
    });
});
formSetOrder.addEventListener("submit",function(e){
    e.preventDefault();
    if(document.querySelector("#id").value == ""){
        Swal.fire("Error","Debe seleccionar el cliente","error");
        return false;
    }
    if(arrProducts.length == 0){
        Swal.fire("Error","Debe agregar al menos un artículo","error");
        return false;
    }
    let url = base_url+"/PedidosPos/setOrder";
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
            document.querySelector("#discountProducts").innerHTML = "$0";
            document.querySelector("#totalProducts").innerHTML = "$0";
            searchItems.parentElement.classList.remove("d-none");
            document.querySelector("#id").value = 0;
            document.querySelector("#selectedItem").innerHTML="";
            modalPurchase.hide();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
})
/*************************functions to select item from search customers*******************************/
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
function getCustomers(){
    request(base_url+"/PedidosPos/getCustomers","","get").then(function(res){
        arrCustomers = res;
    });
}
/*************************functions to get products*******************************/
function getProduct(element,id){
    const formData = new FormData();
    formData.append("id",id);
    element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;  
    element.setAttribute("disabled","");
    request(base_url+"/PedidosPos/getProduct",formData,"post").then(function(res){
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
function addProduct(product={},topic=2){
    let obj = {
        "id":"",
        "is_stock":false,
        "stock":"",
        "qty":1,
        "price_sell":"",
        "discount":0,
        "discount_percent":0,
        "reference":"",
        "product_type":false,
        "name":"",
        "import":"",
        "subtotal":0,
        "variant_name":"",
        "topic":topic
    };
    if(topic == 2){
        obj.id=product.idproduct
        obj.is_stock = product.is_stock
        obj.stock =product.stock
        obj.qty =1
        obj.price_sell =product.price
        obj.discount =0
        obj.discount_percent =0
        obj.reference =product.reference
        obj.product_type =product.product_type
        obj.name =product.name
        obj.import =product.import
        obj.subtotal =0
        obj.variant_name =""
        obj.topic =topic
        if(product.product_type == 1){
            obj.variant_name = product.variant_name;
        }
    }else if(topic==3){
        let name = document.querySelector("#txtService").value;
        let qty = document.querySelector("#intQty").value;
        let price = document.querySelector("#intPrice").value;
        if(name=="" || qty <= 0 || price <=0){
            Swal.fire("Error","Los campos no pueden quedar vacios","error");
            return false;
        }
        obj.id = 0;
        obj.name = document.querySelector("#txtService").value;
        obj.qty = parseFloat(qty);
        obj.price_sell = parseInt(price);
    }
    if(arrProducts.length > 0){
        let flag = false;
        for (let i = 0; i < arrProducts.length; i++) {
            if(arrProducts[i].topic == 2){
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
            }else if(arrProducts[i].topic == 3 && arrProducts[i].name == obj.name){
                arrProducts[i].qty +=obj.qty;
                arrProducts[i].price_sell = obj.price_sell;
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
    let totalDiscount = 0;
    if(type == "discount"){
        let value = parseFloat(element.value);
        discount = value > 0 && value <= 100 ? value/100: 0;
        discountPercent =  value > 0 && value <= 100 ? value : 0;
    }
    let value = parseFloat(element.value);
    for (let i = 0; i < arrProducts.length; i++) {
        if(arrProducts[i].topic == 2){
            if(arrProducts[i].product_type){
                if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference
                    && arrProducts[i].name == obj.name && arrProducts[i].variant_name == obj.variant_name
                 ){
                    if(type =="qty"){
                        arrProducts[i].qty = value;
                    }else if(type=="price_sell"){
                        arrProducts[i].price_sell = value;
                    }
                    subtotal = arrProducts[i].qty * arrProducts[i].price_sell;
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
                }else if(type=="price_sell"){
                    arrProducts[i].price_sell = value;
                }
                subtotal = arrProducts[i].qty * arrProducts[i].price_sell;
                totalDiscount = Math.round(subtotal*discount);
                subtotal = subtotal-totalDiscount;
                arrProducts[i].subtotal = subtotal;
                arrProducts[i].discount = totalDiscount;
                arrProducts[i].discount_percent = discountPercent;
                break;
            }
        }else if(arrProducts[i].topic == 3 && arrProducts[i].name == obj.name){
            if(type =="qty"){
                arrProducts[i].qty = value;
            }else if(type=="price_sell"){
                arrProducts[i].price_sell = value;
            }
            subtotal = arrProducts[i].qty * arrProducts[i].price_sell;
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
function deleteProduct(element,data){
    let obj = JSON.parse(data);
    let parent = element.parentElement.parentElement;
    let index = 0;
    for (let i = 0; i < arrProducts.length; i++) {
        if(arrProducts[i].topic == 2){
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
        }else if(arrProducts[i].topic == 3 && arrProducts[i].name == obj.name){
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
        pro.subtotal = (pro.qty * pro.price_sell)-pro.discount;
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
            <td><input class="form-control" value="${pro.price_sell}" onchange="updateProduct(this,'price_sell','${objString}')" type="number"></td>
            <td><input class="form-control" value="${pro.discount_percent}" onchange="updateProduct(this,'discount','${objString}')" value="" type="number"></td>
            <td class="text-end">$${formatNum(pro.subtotal,".")}</td>
            <td><button class="btn btn-danger m-1 text-white" onclick="deleteProduct(this,'${objString}')"type="button"><i class="fas fa-trash-alt"></i></button></td>
        `;
        tablePurchase.appendChild(tr);
    });
    currentTotal();
}
function currentTotal(){
    let subtotal = 0;
    let discount = 0;
    let total = 0;

    arrProducts.forEach(p=>{
        subtotal+=p.price_sell*p.qty;
        discount+=p.discount;
    });
    total = subtotal-discount;
    document.querySelector("#subtotalProducts").innerHTML = "$"+formatNum(subtotal,".");
    document.querySelector("#discountProducts").innerHTML = "$"+formatNum(discount,".");
    document.querySelector("#totalProducts").innerHTML = "$"+formatNum(total,".");
    document.querySelector("#totalPurchase").innerHTML = "$"+formatNum(total,".");
    return {"subtotal":subtotal,"total":total,"discount":discount}
}
function currentProducts(){
    let rows = document.querySelectorAll(".productToBuy");
    for (let i = 0; i < arrProducts.length; i++) {
        let children = rows[i].children;
        children[2].children[0].value = arrProducts[i].qty; //Cantidad
        children[3].children[0].value = arrProducts[i].price_sell; //Precio de venta
        children[4].children[0].value = arrProducts[i].discount_percent; //Descuento
        children[5].innerHTML = "$"+formatNum(arrProducts[i].subtotal,".");//Subtotal
    }
    currentTotal();
}
/*************************functions to display product variants*******************************/
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
            let active = j==0? "btn-primary" : "btn-secondary";
            html+=`<button type="button" class="btn ${active} m-1 btnVariant" onclick="selectVariant(this)" data-name="${options[j]}">${options[j]}</button>`;
        }
        div.innerHTML = `
        <p class="t-color-3 m-0">${variants[i].name}</p>
        <div class="flex">${html}</div>
        `;
        modalSelectvariants.appendChild(div);
    }
    modalVariantCost.innerHTML = "Precio: "+option[0].format_price;
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
    product['price'] = selectedOption.price;
    product['stock'] = selectedOption.stock;
    openModal();
} 
/*************************functions to set product variant*******************************/
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
    modalVariantCost.innerHTML = "Precio: "+selectedOption.format_price;
    product['variant_name'] = variant;
    product['price'] = selectedOption.price;
    product['stock'] = selectedOption.stock;
}
function openModal(){
    modalVariant.show();
}
function statusPOS(){
    if(document.querySelector("#posProducts").children.length > 0){
        document.querySelector("#btnPos").classList.remove("d-none");
    }else{
        document.querySelector("#btnPos").classList.add("d-none");
    }
}