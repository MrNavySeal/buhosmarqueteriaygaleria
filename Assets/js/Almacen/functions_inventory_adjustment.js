
const tablePurchase = document.querySelector("#tablePurchase");
const tableProducts = document.querySelector("#tableProducts");
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const btnSave = document.querySelector("#btnSave");
const btnClean = document.querySelector("#btnClean");
const searchItems = document.querySelector("#searchItems");
const selectItems = document.querySelector("#selectItems");
const items = document.querySelector("#items");
const selectType = document.querySelector("#selectType");
const strDate = document.querySelector("#strDate");


let product;
let arrProducts = [];
let arrData = [];

window.addEventListener("load",function(){
    getProducts();
    strDate.value = new Date().toISOString().split("T")[0]
});

btnSave.addEventListener("click",function(){
    if(arrProducts.length == 0){
        Swal.fire("Atención!","Debe agregar al menos un artículo.","warning");
        return false;
    }

    Swal.fire({
        title:"¿Estás seguro de guardar?",
        text:"",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Sí, guardar",
        cancelButtonText:"No, cancelar"
    }).then(function(result){
        if(result.isConfirmed){
            let url = base_url+"/Almacen/InventarioAjuste/setAdjustment";
            let formData = new FormData();
            formData.append("concept",document.querySelector("#txtNote").value);
            formData.append("products",JSON.stringify(arrProducts));
            formData.append("total",currentTotal());
            formData.append("date",strDate.value);
            formData.append("type",selectType.value);
            
            /* btnSave.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            btnSave.setAttribute("disabled",""); */
            request(url,formData,"post").then(function(objData){
                /* btnSave.innerHTML=`<i class="fas fa-save"></i> Guardar`;
                btnSave.removeAttribute("disabled"); */
                if(objData.status){
                    Swal.fire("Guardado",objData.msg,"success");
                    arrProducts = [];
                    tablePurchase.innerHTML ="";
                    document.querySelector("#txtNote").value = "";
                    document.querySelector("#totalProducts").innerHTML = "$0";
                    getProducts();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
    
    
});

btnClean.addEventListener("click",function(){
    arrProducts = [];
    tablePurchase.innerHTML ="";
    document.querySelector("#totalProducts").innerHTML = "$0";
});

searchHtml.addEventListener("input",function(){getProducts();});
perPage.addEventListener("change",function(){getProducts();});

/*************************functions to get products*******************************/
async function getProducts(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/Almacen/inventarioAjuste/getProducts",{method:"POST",body:formData});
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
        "type":selectType.value,
        "price_purchase":product.price_purchase,
        "reference":product.reference,
        "name":product.product_name,
        "variant_name":variantName,
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

function updateTypeProduct(){
    for (let i = 0; i < arrProducts.length; i++) {
        arrProducts[i].type = selectType.value;
        if(arrProducts[i].type==1){
            arrProducts[i].qty_result =  arrProducts[i].stock+arrProducts[i].qty ;
            arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
        }else{
            arrProducts[i].qty_result =  arrProducts[i].stock-arrProducts[i].qty ;
            arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
        }
    }
    currentProducts();
}

function updateProduct(element,option,id,variantName){
    for (let i = 0; i < arrProducts.length; i++) {
        arrProducts[i].type = selectType.value;
        if(arrProducts[i].product_type){
            if(arrProducts[i].id == id && arrProducts[i].variant_name == variantName){
                if(option=="price"){
                    arrProducts[i].price_purchase = parseFloat(element.value);
                    if(arrProducts[i].type==1){
                        arrProducts[i].qty_result =  arrProducts[i].stock+arrProducts[i].qty ;
                        arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                    }else{
                        arrProducts[i].qty_result =  arrProducts[i].stock-arrProducts[i].qty ;
                        arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                    }
                }else{
                    arrProducts[i].qty = parseFloat(element.value);
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
        }else if(arrProducts[i].id == id){
            if(option=="price"){
                arrProducts[i].price_purchase = parseFloat(element.value);
                if(arrProducts[i].type==1){
                    arrProducts[i].qty_result =  arrProducts[i].stock+arrProducts[i].qty ;
                    arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                }else{
                    arrProducts[i].qty_result =  arrProducts[i].stock-arrProducts[i].qty ;
                    arrProducts[i].subtotal =  arrProducts[i].qty* arrProducts[i].price_purchase;
                }
            }else{
                arrProducts[i].qty = parseFloat(element.value);
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
        children[2].children[0].value = arrProducts[i].price_purchase;
        children[3].children[0].value = arrProducts[i].qty;
        children[4].innerHTML= arrProducts[i].qty_result;
        children[5].innerHTML = "$"+formatNum(arrProducts[i].subtotal,".");
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
            <td data-title="Artículo">
                <p class="m-0 mb-1">${pro.name}</p>
                <p class="text-secondary m-0 mb-1">${pro.product_type ? pro.variant_name : ""}</p>
            </td>
            <td data-title="Actual"  class="text-center">${pro.stock}</td>
            <td data-title="Costo" class="text-center">
                <input class="form-control text-end" onchange="updateProduct(this,'price','${pro.id}','${pro.variant_name}')" value="${pro.price_purchase}" type="number">
            </td>
            <td data-title="Ajuste"><input class="form-control text-center" onchange="updateProduct(this,'qty','${pro.id}','${pro.variant_name}')" value="${pro.qty}" type="number"></td>
            <td data-title="Resultado" class="text-center">${pro.qty_result}</td>
            <td data-title="Valor" class="text-end">$${formatNum(pro.subtotal,".")}</td>
            <td data-title="Opciones"><button class="btn btn-danger m-1 text-white" onclick="deleteProduct(this,'${pro.id}','${pro.variant_name}')"type="button"><i class="fas fa-trash-alt"></i></button></td>
        `;
        tablePurchase.appendChild(tr);
    });
    currentTotal();
}