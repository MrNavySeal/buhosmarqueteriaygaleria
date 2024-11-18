'use strict';

const modalPurchase = new bootstrap.Modal(document.querySelector("#modalPurchase"));
const modalFrame = new bootstrap.Modal(document.querySelector("#modalFrame"));
const btnAdd = document.querySelector("#btnAdd");
const btnPurchase = document.querySelector("#btnPurchase");
const btnClean = document.querySelector("#btnClean");
const btnSetPurchase = document.querySelector("#btnSetPurchase");
const searchItems = document.querySelector("#searchItems");
const selectItems = document.querySelector("#selectItems");
const items = document.querySelector("#items");
const formSetOrder = document.querySelector("#formSetOrder");
const tablePurchase = document.querySelector("#tablePurchase");
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const tableProducts = document.querySelector("#tableProducts");
let arrDataMolding = [];
let arrProducts = [];
let arrCustomers = [];
let product;
let arrData = [];

let tableMolding = new DataTable("#tableMolding",{
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/PedidosPos/getMoldingProducts",
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
    getCustomers();
    getProducts();
});

searchHtml.addEventListener("input",function(){getProducts();});
perPage.addEventListener("change",function(){getProducts();});

/*************************functions to get products*******************************/
async function getProducts(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/PedidosPos/getProducts",{method:"POST",body:formData});
    const objData = await response.json();
    const arrHtml = objData.html;
    arrData = objData.data;
    tableProducts.innerHTML =arrHtml.products;
    document.querySelector("#pagination").innerHTML = arrHtml.pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}

/*************************Events*******************************/
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
/*************************functions to add and update products*******************************/
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
        "variant_detail":{}
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
                    let subtotalNormal = arrProducts[i].qty * arrProducts[i].price_sell;
                    let subtotalOffer = arrProducts[i].qty * arrProducts[i].price_offer;
                    totalDiscount = subtotalNormal - subtotalOffer;
                    subtotal = arrProducts[i].qty * price;
                    
                    arrProducts[i].discount = arrProducts[i].price_offer > 0 ? totalDiscount : 0;
                    arrProducts[i].subtotal = subtotal;
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
                let subtotalNormal = arrProducts[i].qty * arrProducts[i].price_sell;
                let subtotalOffer = arrProducts[i].qty * arrProducts[i].price_offer;
                totalDiscount = subtotalNormal - subtotalOffer;
                subtotal = arrProducts[i].qty * price;
                
                arrProducts[i].discount = arrProducts[i].price_offer > 0 ? totalDiscount : 0;
                arrProducts[i].subtotal = subtotal;
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
            <td>${pro.is_stock ? pro.stock : "N/A"}</td>
            <td>${pro.reference}</td>
            <td>
                ${strDescription}
            </td>
            <td><input class="form-control text-center" onchange="updateProduct(this,'qty','${objString}')" value="${pro.qty}" type="number"></td>
            <td><input class="form-control" value="${pro.price_sell}" onchange="updateProduct(this,'price_sell','${objString}')" type="number"></td>
            <td><input class="form-control" value="${pro.price_offer}" onchange="updateProduct(this,'discount','${objString}')" value="" type="number"></td>
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
        subtotal+=p.qty * p.price_sell;
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
        let price = arrProducts[i].price_offer > 0 ? arrProducts[i].price_offer :arrProducts[i].price_sell; 
        let subtotal = price * arrProducts[i].qty;
        let children = rows[i].children;
        children[3].children[0].value = arrProducts[i].qty; //Cantidad
        children[4].children[0].value = arrProducts[i].price_sell; //Precio de venta
        children[5].children[0].value = arrProducts[i].price_offer; //Precio de oferta
        children[6].innerHTML = "$"+formatNum(subtotal,".");//Subtotal
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
