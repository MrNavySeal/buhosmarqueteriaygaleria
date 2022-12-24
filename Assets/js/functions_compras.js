const listProducts = document.querySelector("#buyProducts");
const btnAdd = document.querySelector("#btnAddProduct");
const btnPurchase = document.querySelector("#btnPurchase");
const total = document.querySelector("#total");
const selectSupplier = document.querySelector("#selectSupplier");
const search = document.querySelector("#search");
const element = document.querySelector("#listItem");

element.addEventListener("click",function(e) {
    let element = e.target;
    let id = element.getAttribute("data-id");
    if(element.name == "btnDelete"){
        deleteItem(id);
    }
});
search.addEventListener('input',function() {
    request(base_url+"/compras/searchPurchase/"+search.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});
selectSupplier.addEventListener("change",function(){
    document.querySelector("#txtProduct").value ="";
    document.querySelector("#intQty").value ="";
    document.querySelector("#intPrice").value="";
    total.innerHTML = "$0";
    listProducts.innerHTML="";
});
btnAdd.addEventListener("click",function(){
    let idSupplier = selectSupplier.value;
    let strName = document.querySelector("#txtProduct").value;
    let intQty = document.querySelector("#intQty").value;
    let intPrice = document.querySelector("#intPrice").value;
    
    if(idSupplier == 0 || strName =="" || intQty=="" || intPrice ==""){
        Swal.fire("Error","Por favor, todos los campos con (*) son obligatorios","error");
        return false;
    }
    let totalProduct = parseInt(intPrice)*parseInt(intQty);
    let div = document.createElement("div");
    div.classList.add("position-relative","product-item");
    div.setAttribute("data-name",strName);
    div.setAttribute("data-qty",intQty);
    div.setAttribute("data-price",intPrice);
    div.innerHTML=`
        <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
        <div class="p-1">
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <div class="text-start">
                        <div style="height:25px" class="overflow-hidden"><p class="m-0" >${strName}</p></div>
                        <p class="m-0 productData">
                            <span class="qtyProduct">${intQty}</span> x $${formatNum(intPrice,".")}
                        </p>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-1">
                <input type="hidden" class="productTotal" value ="${totalProduct}">
                <p class="m-0 mt-1 fw-bold text-end" >$${formatNum(totalProduct,".")}</p>
            </div>
        </div>`;
    listProducts.appendChild(div);
    let products = document.querySelectorAll(".productTotal");
    let totalValue = 0;
    for (let i = 0; i < products.length; i++) {
        totalValue+=parseInt(products[i].value);
    }
    total.innerHTML = "$"+formatNum(totalValue,".");
},false);

btnPurchase.addEventListener("click",function(){
    let products = document.querySelectorAll(".product-item");
    let arrProducts = [];
    let totalValue = 0;
    let strDate = document.querySelector("#txtDate").value;
    if(products.length == 0){
        Swal.fire("Error","No hay productos para procesar la compra.","error");
        return false;
    }
    if(strDate ==""){
        Swal.fire("Error","Por favor, ingresa la fecha de compra.","error");
        return false;
    }
    for (let i = 0; i < products.length; i++) {
        arr = {
            "name":products[i].getAttribute("data-name"),
            "qty":products[i].getAttribute("data-qty"),
            "price":products[i].getAttribute("data-price")
        };
        totalValue += parseInt(document.querySelectorAll(".productTotal")[i].value);
        arrProducts.push(arr);
    }
    let formData = new FormData();
    formData.append("date",strDate);
    formData.append("idSupplier",selectSupplier.value);
    formData.append("arrProducts",JSON.stringify(arrProducts));
    formData.append("total",totalValue);
    request(base_url+"/compras/setPurchase",formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#txtProduct").value ="";
            document.querySelector("#intQty").value ="";
            document.querySelector("#intPrice").value="";
            document.querySelector("#txtDate").value="";
            selectSupplier.value = 0;
            total.innerHTML = "$0";
            listProducts.innerHTML="";
            element.innerHTML = objData.data;
            Swal.fire("Agregado",objData.msg,"success");
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
},false);

function delProduct(element){
    element.parentElement.remove();
    let products = document.querySelectorAll(".productTotal");
    let totalValue = 0;
    for (let i = 0; i < products.length; i++) {
        totalValue+=parseInt(products[i].value);
    }
    total.innerHTML = "$"+formatNum(totalValue,".");
}
function deleteItem(id){
    Swal.fire({
        title:"¿Estás seguro de eliminarlo?",
        text:"Se eliminará para siempre...",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Sí, eliminar",
        cancelButtonText:"No, cancelar"
    }).then(function(result){
        if(result.isConfirmed){
            let url = base_url+"/compras/delPurchase"
            let formData = new FormData();
            formData.append("idPurchase",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    element.innerHTML = objData.data;
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}