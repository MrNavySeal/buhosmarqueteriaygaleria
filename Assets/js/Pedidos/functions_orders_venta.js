'use strict';

const modalPurchase = new bootstrap.Modal(document.querySelector("#modalPurchase"));
const modalFrame = new bootstrap.Modal(document.querySelector("#modalFrame"));
const btnAdd = document.querySelector("#btnAdd");
const btnPurchase = document.querySelector("#btnPurchase");
const btnClean = document.querySelector("#btnClean");
const btnSetPurchase = document.querySelector("#btnSetPurchase");
const btnQuote = document.querySelector("#btnQuote");
const searchItems = document.querySelector("#searchItems");
const selectItems = document.querySelector("#selectItems");
const items = document.querySelector("#items");
const formSetOrder = document.querySelector("#formSetOrder");
const tablePurchase = document.querySelector("#tablePurchase");
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const tableProducts = document.querySelector("#tableProducts");
const paymentList = document.querySelector("#paymentList");

let orderType = 1;
let arrDataMolding = [];
let arrProducts = [];
let arrCustomers = [];
let arrPaymentTypes=[];
let product;
let arrData = [];

let tableMolding = new DataTable("#tableMolding",{
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Pedidos/PedidosPos/getMoldingProducts",
        "dataSrc":""
    },
    "initComplete":function( settings, json){
        //arrProducts = json;
    },
    columns: [
        { data: 'id' },
        { data: 'name' },
        { data: 'options' },
    ],
    responsive: true,
    order: [[0, 'asc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});

window.addEventListener("load",function(){
    getProducts();
    document.querySelector("#txtDate").value = new Date().toISOString().split("T")[0];
});

searchHtml.addEventListener("input",function(){getProducts();});
perPage.addEventListener("change",function(){getProducts();});


async function getProducts(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/Pedidos/PedidosPos/getProducts",{method:"POST",body:formData});
    const objData = await response.json();
    const arrHtml = objData.html;
    arrData = objData.data;
    tableProducts.innerHTML =arrHtml.products;
    document.querySelector("#pagination").innerHTML = arrHtml.pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}

btnPurchase.addEventListener("click",function(){
    getInitialData();
    modalPurchase.show();
    orderType = 1;
    document.querySelector("#modalPurchase .modal-title").innerHTML="Información de pago";
    document.querySelector("#contentPurchase").classList.remove("d-none");
    document.querySelector("#contentQuote").classList.add("d-none");
    document.querySelector(".creditContainer").classList.add("d-none");
});

btnQuote.addEventListener("click",function(){
    modalPurchase.show();
    orderType = 2;
    document.querySelector("#modalPurchase .modal-title").innerHTML="Información de cotización";
    document.querySelector("#contentPurchase").classList.add("d-none");
    document.querySelector("#contentQuote").classList.remove("d-none");
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

    let url = base_url+"/Pedidos/PedidosPos/setOrder";
    let formData = new FormData(formSetOrder);
    let arrCreditItems = [];
    let arrTotal = currentTotal();

    if(paymentList.value == "credito"){
        arrCreditItems = getCreditItems();
        if(!validCreditTotal(arrCreditItems,arrTotal.total)){
            Swal.fire("Error","El total del anticipo no puede ser igual o mayor al total a pagar.","error");
            return false;
        }
    }

    formData.append("products",JSON.stringify(arrProducts));
    formData.append("credit_items",JSON.stringify(arrCreditItems));
    formData.append("order_type",orderType);
    formData.append("total",JSON.stringify(arrTotal));
    btnSetPurchase.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnSetPurchase.setAttribute("disabled","");

    request(url,formData,"post").then(function(objData){

        btnSetPurchase.innerHTML=`<i class="fas fa-save"></i> Guardar`;
        btnSetPurchase.removeAttribute("disabled");

        if(objData.status){
            Swal.fire("Guardado",objData.msg,"success");
            formSetOrder.reset();
            document.querySelector("#txtDate").value = new Date().toISOString().split("T")[0];
            arrProducts = [];
            tablePurchase.innerHTML ="";
            document.querySelector("#subtotalProducts").innerHTML = "$0";
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

paymentList.addEventListener("change",function(){
    const creditContainer =document.querySelector(".creditContainer");
    const creditMethod =document.querySelector(".creditMethod");
    creditContainer.classList.add("d-none");
    if(paymentList.value=="credito"){
        let html="";
        arrPaymentTypes.forEach(e => {
            if(e.name!='mercadopago' && e.name!='credito'){
                html+=`
                <div class="col-md-4">
                    <div class="mt-3 mb-3">
                        <label for="${e.name}" class="form-label">${e.name}</label>
                        <input type="number" value="0" id="${e.name}" class="form-control creditItem">
                    </div>
                </div>
                `;
            }
        });
        creditMethod.innerHTML=html;
        creditContainer.classList.remove("d-none");
    }
});

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

function getInitialData(){
    request(base_url+"/Pedidos/PedidosPos/getInitialData","","get").then(function(objData){
        arrCustomers = objData.customers;
        arrPaymentTypes = objData.payment_types;
        let html="";

        arrPaymentTypes.forEach(e => {
            html+=`<option value="${e.name}">${e.name}</option>`;
        });

        paymentList.innerHTML = html;
    });
}

function addProduct(product={},topic=2,id="",variantName="",productType=""){
    let obj = {
        "id":"",
        "is_stock":false,
        "stock":"",
        "qty":1,
        "price_sell":"",
        "price_offer":"",
        "discount":0,
        "reference":"",
        "product_type":false,
        "name":"",
        "import":"",
        "subtotal":0,
        "variant_name":"",
        "topic":topic,
        "variant_detail":{},
        "wholesale":[]
    };

    if(topic == 2){
        const product = arrData.filter((e)=>{
            if(productType){
                return e.id == id && variantName == e.variant_name;
            }else{
                return e.id == id;
            }
        })[0];
            obj.id = id;
            obj.stock = product.stock;
            obj.is_stock = product.is_stock;
            obj.qty = 1;
            obj.price_sell = product.price_sell;
            obj.price_offer = product.price_offer;
            obj.reference=product.reference;
            obj.name=product.product_name;
            obj.variant_name=variantName;
            obj.product_type =product.product_type;
            obj.variant_detail = product.variation;
            obj.wholesale = product.wholesale;
            obj.img = product.url;
            obj.discount = product.price_offer > 0 ? parseFloat(product.price_sell)-parseFloat(product.price_offer) : 0;

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

    }else if(topic== 1){
        const isPrint = document.querySelector("#isPrint").getAttribute("data-print");
        if(isPrint== 1){
            if(uploadPicture.value == ""){
                Swal.fire("Error","Por favor, sube la imagen a imprimir","error");
                return false;
            }
        }
        obj.price_sell =totalFrame;
        obj.config = arrConfigFrame;
        obj.data = product;
        obj.name = nameTopic;
        obj.img = imageUrl;
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
                        arrProducts[i] = calcWholsale(arrProducts[i]);
                        break;
                     }
                }else if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference && arrProducts[i].name == obj.name){
                    arrProducts[i].qty +=obj.qty
                    flag = false;
                    arrProducts[i] = calcWholsale(arrProducts[i]);
                    break;
                }

            }else if(arrProducts[i].topic == 3 && arrProducts[i].name == obj.name){
                arrProducts[i].qty +=obj.qty;
                arrProducts[i].price_sell = obj.price_sell;
                flag = false;
                break;

            }else if(arrProducts[i].topic == 1 && arrProducts[i].name == obj.name && arrProducts[i].img == obj.img){
                let arrProductData = arrProducts[i].data;
                let arrObjData = obj.data;
                let flagFrame = false;
                
                if(arrProductData.length == arrObjData.length){
                    for (let j = 0; j < arrProductData.length; j++) {
                        if(arrProductData[j].value == arrObjData[j].value){
                            flagFrame = false;
                        }else{
                            flagFrame = true;
                            break;
                        }
                    }
                    if(!flagFrame){
                        arrProducts[i].qty +=obj.qty;
                        flag = false;
                        break;
                    }
                }
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
    let subtotal = 0;
    let value = parseFloat(element.value);
    let totalDiscount = 0;
    let price = type != "qty" ? value : 0;
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
                        price = value;
                    }else if(type =="discount"){
                        arrProducts[i].price_offer = value;
                        price = value > 0 ? arrProducts[i].price_offer : arrProducts[i].price_sell ;
                    }
                    arrProducts[i] = calcWholsale(arrProducts[i]);

                    /* let subtotalNormal = arrProducts[i].qty * arrProducts[i].price_sell;
                    let subtotalOffer = arrProducts[i].qty * arrProducts[i].price_offer;
                    totalDiscount = subtotalNormal - subtotalOffer;
                    subtotal = arrProducts[i].qty * price;
                    
                    arrProducts[i].discount = arrProducts[i].price_offer > 0 ? totalDiscount : 0;
                    arrProducts[i].subtotal = subtotal; */
                    break;
                 }
            }else if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference && arrProducts[i].name == obj.name){
                
                if(type =="qty"){
                    arrProducts[i].qty = value;
                }else if(type=="price_sell"){
                    arrProducts[i].price_sell = value;
                    price = value;
                }else if(type =="discount"){
                    arrProducts[i].price_offer = value;
                    price = value > 0 ? arrProducts[i].price_offer : arrProducts[i].price_sell ;
                }
                arrProducts[i] = calcWholsale(arrProducts[i]);
                
                /* let subtotalNormal = arrProducts[i].qty * arrProducts[i].price_sell;
                let subtotalOffer = arrProducts[i].qty * arrProducts[i].price_offer;
                totalDiscount = subtotalNormal - subtotalOffer;
                subtotal = arrProducts[i].qty * price;
                
                arrProducts[i].discount = arrProducts[i].price_offer > 0 ? totalDiscount : 0;
                arrProducts[i].subtotal = subtotal; */
                break;
            }
        }else if(arrProducts[i].topic == 3 && arrProducts[i].name == obj.name){
            if(type =="qty"){
                arrProducts[i].qty = value;
            }else if(type=="price_sell"){
                arrProducts[i].price_sell = value;
                price = value;
            }else if(type =="discount"){
                arrProducts[i].price_offer = value;
                price = value > 0 ? arrProducts[i].price_offer : arrProducts[i].price_sell ;
            }
            let subtotalNormal = arrProducts[i].qty * arrProducts[i].price_sell;
            let subtotalOffer = arrProducts[i].qty * arrProducts[i].price_offer;
            totalDiscount = subtotalNormal - subtotalOffer;
            subtotal = arrProducts[i].qty * price;
            
            arrProducts[i].discount = arrProducts[i].price_offer > 0 ? totalDiscount : 0;
            arrProducts[i].subtotal = subtotal;
            break;
        }else if(arrProducts[i].topic == 1 && arrProducts[i].name == obj.name && arrProducts[i].img == obj.img){
            let arrProductData = arrProducts[i].data;
            let arrObjData = obj.data;
            let flagFrame = false;
            if(arrProductData.length == arrObjData.length){
                for (let j = 0; j < arrProductData.length; j++) {
                    if(arrProductData[j].value == arrObjData[j].value){
                        flagFrame = false;
                    }else{
                        flagFrame = true;
                        break;
                    }
                }
                if(!flagFrame){
                    if(type =="qty"){
                        arrProducts[i].qty = value;
                    }else if(type=="price_sell"){
                        arrProducts[i].price_sell = value;
                        price = value;
                    }else if(type =="discount"){
                        arrProducts[i].price_offer = value;
                        price = value > 0 ? arrProducts[i].price_offer : arrProducts[i].price_sell ;
                    }
                    let subtotalNormal = arrProducts[i].qty * arrProducts[i].price_sell;
                    let subtotalOffer = arrProducts[i].qty * arrProducts[i].price_offer;
                    totalDiscount = subtotalNormal - subtotalOffer;
                    subtotal = arrProducts[i].qty * price;
                    
                    arrProducts[i].discount = arrProducts[i].price_offer > 0 ? totalDiscount : 0;
                    arrProducts[i].subtotal = subtotal;
                    break;
                }
            }
        }
    }
    currentProducts();
}

function deleteProduct(element,data){
    let obj = JSON.parse(data);
    let parent = element.parentElement.parentElement.parentElement;
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
        }else if(arrProducts[i].topic == 1 && arrProducts[i].name == obj.name && arrProducts[i].img == obj.img){
            let arrProductData = arrProducts[i].data;
            let arrObjData = obj.data;
            let flagFrame = false;
            if(arrProductData.length == arrObjData.length){
                for (let j = 0; j < arrProductData.length; j++) {
                    if(arrProductData[j].value == arrObjData[j].value){
                        flagFrame = false;
                    }else{
                        flagFrame = true;
                        break;
                    }
                }
                if(!flagFrame){
                    index = i;
                    break;
                }
            }
        }
    }
    arrProducts.splice(index,1);
    parent.remove();
    currentProducts();
}

function calcWholsale(element){
    const arrWholesale = element.wholesale;
    const productQty = element.qty;
    const priceSell = element.price_sell;
    let priceOffer = element.price_offer;
    let discount = priceOffer > 0 ? priceSell-priceOffer : 0;

    if(arrWholesale.length > 0){
        let arrDiscount = arrWholesale.filter(function(e){return productQty >= e.min;});
        if(arrDiscount.length > 0){
            discount = arrDiscount[arrDiscount.length-1].percent;
            discount = discount/100;
            discount = discount*priceSell;
            priceOffer = priceSell-discount;
        }else{
            discount = 0;
            priceOffer = 0;
        }
    }
    element.discount = discount;
    element.price_offer = priceOffer;
    return element;
}

function showProducts(){
    tablePurchase.innerHTML ="";
    arrProducts.forEach(pro=>{
        let strDescription = `
            <p class="m-0 mb-1">${pro.name}</p>
            <p class="text-secondary m-0 mb-1">${pro.variant_name}</p>
        `;
        if(pro.topic == 1){
            strDescription = pro.name;
            let data = pro.data;
            data = data.filter(e=>"name" in e);
            data.forEach(e => {
                strDescription+=`<ul>
                    <li><span class="fw-bold">${e.name}: </span>${e.value}</li>
                </ul>`
            });
        }
        let price = pro.price_offer > 0 ? pro.price_offer : pro.price_sell; 
        pro.subtotal = price * pro.qty;
        let tr = document.createElement("tr");
        tr.classList.add("productToBuy");
        let objString = JSON.stringify(pro).replace(/"/g, '&quot;');
        tr.innerHTML = `
            <td data-title="Artículo"> ${strDescription}</td>
            <td data-title="Cantidad" ><input class="form-control text-center w-100" onchange="updateProduct(this,'qty','${objString}')" value="${pro.qty}" type="number"></td>
            <td data-title="Precio"><input class="form-control" value="${pro.price_sell}" onchange="updateProduct(this,'price_sell','${objString}')" type="number"></td>
            <td data-title="Oferta">$${formatNum(pro.price_offer,".")}</td>
            <td data-title="Subtotal" class="text-end">$${formatNum(pro.subtotal,".")}</td>
            <td data-title="Opciones" >
                <div class="d-flex justify-content-center">
                    <button class="btn btn-danger m-1 text-white" onclick="deleteProduct(this,'${objString}')"type="button"><i class="fas fa-trash-alt"></i></button>
                </div>
            </td>
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
        subtotal+=p.qty * p.price_sell;
        discount+=p.discount > 0 ? (p.discount*p.qty) : 0;
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
        let price = arrProducts[i].price_offer > 0 ? arrProducts[i].price_offer :arrProducts[i].price_sell; 
        let subtotal = price * arrProducts[i].qty;
        let children = rows[i].children;

        children[1].children[0].value = arrProducts[i].qty; //Cantidad
        children[2].children[0].value = arrProducts[i].price_sell; //Precio de venta
        children[3].innerHTML = "$"+formatNum(arrProducts[i].price_offer,"."); //Precio de oferta
        children[4].innerHTML = "$"+formatNum(subtotal,"."); //Subtotal
    }
    showProducts();
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

function getCreditItems(){
    const arrCreditItems =[];
    const arrItems = Array.from(document.querySelectorAll(".creditItem"));
    arrItems.forEach(e => { 
        if(e.value > 0){
            arrCreditItems.push( {type:e.id,value:e.value} ) 
        }
    });
    return arrCreditItems;
}

function validCreditTotal(items,total){
    let totalItem = 0;
    items.forEach(e => { totalItem+= parseFloat(e.value); });
    return totalItem < total
}
