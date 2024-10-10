
const tablePurchase = document.querySelector("#tablePurchase");
const tableProducts = document.querySelector("#tableProducts");
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
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
let arrData = [];
window.addEventListener("load",function(){
    getProducts();
});

btnPurchase.addEventListener("click",function(){
    //modalPurchase.show();
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
/*************************functions to get products*******************************/
async function getProducts(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/inventarioAjuste/getProducts",{method:"POST",body:formData});
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
        "stock":product.stock,
        "qty":1,
        "qty_result":product.stock+1,
        "type":1,
        "price_purchase":product.price_purchase,
        "reference":product.reference,
        "name":product.product_name,
        "variant_name":variantName,
        "variant_detail":product.variant_html,
        "product_type":productType,
    };
    if(arrProducts.length > 0){
        let flag = false;
        for (let i = 0; i < arrProducts.length; i++) {
            if(arrProducts[i].product_type){
                if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference
                    && arrProducts[i].name == obj.name && arrProducts[i].variant_name == obj.variant_name
                 ){
                    arrProducts[i].qty +=obj.qty 
                    if(obj.type == 1){
                        arrProducts[i].qty_result = arrProducts[i].stock + arrProducts[i].qty;
                    }else{
                        arrProducts[i].qty_result = arrProducts[i].stock - arrProducts[i].qty;
                    }
                    flag = false;
                    break;
                 }
            }else if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference && arrProducts[i].name == obj.name){
                    arrProducts[i].qty +=obj.qty
                    if(obj.type == 1){
                        arrProducts[i].qty_result = arrProducts[i].stock + arrProducts[i].qty;
                    }else{
                        arrProducts[i].qty_result = arrProducts[i].stock - arrProducts[i].qty;
                    }
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
function updateProduct(element,option,id,variantName){
    for (let i = 0; i < arrProducts.length; i++) {
        if(arrProducts[i].product_type){
            if(arrProducts[i].id == id && arrProducts[i].variant_name == variantName){
                if(option == "type"){
                    arrProducts[i].type = element.value;
                    if(arrProducts[i].type==1){
                        arrProducts[i].qty_result =  arrProducts[i].stock+arrProducts[i].qty ;
                        arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                    }else{
                        arrProducts[i].qty_result =  arrProducts[i].stock-arrProducts[i].qty ;
                        arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                    }
                }else{
                    arrProducts[i].qty = parseInt(element.value);
                    if(arrProducts[i].type==1){
                        arrProducts[i].qty_result =  arrProducts[i].stock+arrProducts[i].qty ;
                        arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                    }else{
                        arrProducts[i].qty_result =  arrProducts[i].stock-arrProducts[i].qty ;
                        arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                    }
                }
                break;
             }
        }else if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference && arrProducts[i].name == obj.name){
            if(option == "type"){
                arrProducts[i].type = element.value;
                if(arrProducts[i].type==1){
                    arrProducts[i].qty_result =  arrProducts[i].stock+arrProducts[i].qty ;
                    arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                }else{
                    arrProducts[i].qty_result =  arrProducts[i].stock-arrProducts[i].qty ;
                    arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                }
            }else{
                arrProducts[i].qty = parseInt(element.value);
                if(arrProducts[i].type==1){
                    arrProducts[i].qty_result =  arrProducts[i].stock+arrProducts[i].qty ;
                    arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                }else{
                    arrProducts[i].qty_result =  arrProducts[i].stock-arrProducts[i].qty ;
                    arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                }
            }
            break;
        }
    }
    currentProducts();
}
function currentTotal(){
    let total = 0;
    arrProducts.forEach(p=>{total+=p.price_purchase*p.qty;});
    document.querySelector("#totalProducts").innerHTML = "$"+formatNum(total,".");
    return total;
}
function currentProducts(){
    let rows = document.querySelectorAll(".productToBuy");
    for (let i = 0; i < arrProducts.length; i++) {
        let children = rows[i].children;
        children[3].children[0].value = arrProducts[i].type;
        children[4].children[0].value = arrProducts[i].qty;
        children[5].innerHTML= arrProducts[i].qty_result;
        children[6].innerHTML = "$"+formatNum(arrProducts[i].subtotal,".");
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
        pro.subtotal = pro.qty * pro.price_purchase;
        let tr = document.createElement("tr");
        tr.classList.add("productToBuy");
        tr.innerHTML = `
            <td>
                <p class="m-0 mb-1">${pro.name}</p>
                <p class="text-secondary m-0 mb-1">${pro.reference}</p>
                ${pro.product_type ? pro.variant_detail : ""}
            </td>
            <td  class="text-center">${pro.stock}</td>
            <td class="text-center">$${formatNum(pro.price_purchase,".")}</td>
            <td style="width: 100%;">
                <select class="form-select" onchange="updateProduct(this,'type','${pro.id}','${pro.variant_name}')" type="number">
                    <option value="1">Adición</option>
                    <option value="2">Reducción</option>
                <select/>
            </td>
            <td><input class="form-control text-center" onchange="updateProduct(this,'qty','${pro.id}','${pro.variant_name}')" value="${pro.qty}" type="number"></td>
            <td class="text-center">${pro.qty_result}</td>
            <td class="text-end">$${formatNum(pro.subtotal,".")}</td>
            <td><button class="btn btn-danger m-1 text-white" onclick="deleteProduct(this,'${pro.id}','${pro.variant_name}')"type="button"><i class="fas fa-trash-alt"></i></button></td>
        `;
        tablePurchase.appendChild(tr);
    });
    currentTotal();
}
function openModal(option){
    modalVariant.show();
}