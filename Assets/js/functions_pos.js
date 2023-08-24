'use strict';

const moneyReceived = document.querySelector("#moneyReceived");
const btnAddPos = document.querySelector("#btnAddPos");
let searchProducts = document.querySelector("#searchProducts");
let searchCustomers = document.querySelector("#searchCustomers");
const cupon = document.querySelector("#discount");
/*cupon.addEventListener("input",function(){
    if(cupon.value <= 0){
        cupon.value = 0;
    }
});*/
moneyReceived.addEventListener("input",function(){
    let total = document.querySelector("#total").getAttribute("data-value");
    let result = 0;
    
    result = moneyReceived.value - total;
    if(result < 0){
        result = 0;
    }
    document.querySelector("#moneyBack").innerHTML = "Dinero a devolver: $"+formatNum(result,".");
});

let formPOS = document.querySelector("#formSetOrder");
formPOS.addEventListener("submit",function(e){
    
    e.preventDefault();
    let id = document.querySelector("#idCustomer").value;
    let received = moneyReceived.value;
    let strDate = document.querySelector("#txtDate").value;
    let strNote = document.querySelector("#txtNotePos").value;
    if(id <= 0){
        Swal.fire("Error","Por favor, añada un cliente para establecer el pedido","error");
        return false;
    }
    if(received ==""){
        Swal.fire("Error","Los campos con (*) son obligatorios","error");
        return false;
    }
    let formData = new FormData(formPOS);
    /*formData.append("strDate",strDate);
    formData.append("received",received);
    formData.append("strNote",strNote);
    formData.append("txtTransaction",strTransaction);*/
    btnAddPos.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAddPos.setAttribute("disabled","");
    request(base_url+"/pedidos/setOrder",formData,"post").then(function(objData){
        btnAddPos.removeAttribute("disabled");
        btnAddPos.innerHTML="Guardar";
        if(objData.status){
            location.reload();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });

});
searchProducts.addEventListener('input',function() {
    request(base_url+"/pedidos/searchProducts/"+searchProducts.value,"","get").then(function(objData){
        if(objData.status){
            document.querySelector("#listProducts").innerHTML = objData.data;
        }else{
            document.querySelector("#listProducts").innerHTML = objData.data;
        }
    });
});
searchCustomers.addEventListener('input',function() {
    if(searchCustomers.value !=""){
        request(base_url+"/pedidos/searchCustomers/"+searchCustomers.value,"","get").then(function(objData){
            if(objData.status){
                document.querySelector("#customers").innerHTML = objData.data;
            }else{
                document.querySelector("#customers").innerHTML = objData.data;
            }
        });
    }else{
        document.querySelector("#customers").innerHTML = "";
    }
});

function addProduct(type,id=null, element){
    let formData = new FormData();
    const toastLiveExample = document.getElementById('liveToast');
    let topic = 0;
    let intQty = 1;
    if(id!=null){
        topic = 2;
        if(element.previousElementSibling){
            formData.append("variant",element.previousElementSibling.value);
        }
    }else{
        id = 0;
        topic = 3;
        let strService = document.querySelector("#txtService").value;
        let intPrice = document.querySelector("#intPrice").value;
        intQty = document.querySelector("#intQty").value;
        formData.append("txtService",strService);
        formData.append("intPrice",intPrice);

        if(strService=="" || intPrice =="" || intQty ==""){
            Swal.fire("Error","Todos los campos son obligatorios","error");
            return false;
        }
    }
    let idProduct = id;
    formData.append("idProduct",idProduct);
    formData.append("topic",topic);
    formData.append("txtQty",intQty);
    formData.append("productType",type);
    
    element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    element.setAttribute("disabled","");
    request(base_url+"/pedidos/addCart",formData,"post").then(function(objData){
        element.innerHTML=`<i class="fas fa-plus"></i>`;
        element.removeAttribute("disabled");
        document.querySelector(".toast-header img").src=objData.data.image;
        document.querySelector(".toast-header img").alt=objData.data.name;
        document.querySelector("#toastProduct").innerHTML=objData.data.name;
        document.querySelector(".toast-body").innerHTML=objData.msg;
        if(objData.status){
            document.querySelector("#total").innerHTML = objData.total;
            document.querySelector("#posProducts").innerHTML = objData.html;
            document.querySelector("#total").setAttribute("data-value",objData.value);
            statusPOS();
        }

        const toast = new bootstrap.Toast(toastLiveExample);
        toast.show();
    });
}
function delProduct(element){
    let formData = new FormData();
    let topic = element.parentElement.getAttribute("data-topic");
    let id = element.parentElement.getAttribute("data-id");
    let variant = null;
    if(element.parentElement.getAttribute("data-variant")){
        variant = element.parentElement.getAttribute("data-variant");
    }
    formData.append("topic",topic);
    formData.append("id",id);
    formData.append("variant",variant);
    if(topic == 1){
        let photo = element.parentElement.getAttribute("data-f");
        let height = element.parentElement.getAttribute("data-h");
        let width = element.parentElement.getAttribute("data-w");
        let margin = element.parentElement.getAttribute("data-m");
        let marginColor = element.parentElement.getAttribute("data-mc");
        let borderColor = element.parentElement.getAttribute("data-bc");
        let style = element.parentElement.getAttribute("data-s");
        let type = element.parentElement.getAttribute("data-t");
        let reference = element.parentElement.getAttribute("data-r");
        formData.append("height",height);
        formData.append("width",width);
        formData.append("margin",margin);
        formData.append("margincolor",marginColor);
        formData.append("bordercolor",borderColor);
        formData.append("style",style);
        formData.append("type",type);
        formData.append("photo",photo);
        formData.append("reference",reference);
    }else if(topic==3){
        let strService = element.parentElement.getAttribute("data-name");
        let intPrice = element.parentElement.getAttribute("data-price");
        formData.append("txtService",strService);
        formData.append("intPrice",intPrice);
    }
    element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    element.setAttribute("disabled","");
    request(base_url+"/pedidos/delCart",formData,"post").then(function(objData){
        element.innerHTML=`<i class="fas fa-times"></i>`;
        element.removeAttribute("disabled");
        if(objData.status){
            document.querySelector("#total").innerHTML = objData.total;
            document.querySelector("#total").setAttribute("data-value",objData.value);
            document.querySelector("#posProducts").innerHTML = objData.html;
            element.parentElement.remove();
            statusPOS();
        }
    });
}
function productInc(element){
    let formData = new FormData();
    let productTotal = element.parentElement.nextElementSibling;
    let parent = element.parentElement.parentElement.parentElement.parentElement;
    let child = parent.children[1].children[0].children[0].children[1];
    let qty= child.children.length > 2 ? parseInt( child.children[2].children[0].innerHTML) : parseInt( child.children[1].children[0].innerHTML);
    let id = parent.getAttribute("data-id");
    let topic = parent.getAttribute("data-topic");
    let variant = null;
    if(parent.getAttribute("data-variant")){
        variant = parent.getAttribute("data-variant");
    }
    if(topic == 1){
        let height = parent.getAttribute("data-h");
        let width = parent.getAttribute("data-w");
        let margin = parent.getAttribute("data-m");
        let style = parent.getAttribute("data-s");
        let colorMargin = parent.getAttribute("data-mc");
        let colorBorder = parent.getAttribute("data-bc");
        let idType = parent.getAttribute("data-t");
        let reference = parent.getAttribute("data-r");
        formData.append("height",height);
        formData.append("width",width);
        formData.append("margin",margin);
        formData.append("style",style);
        formData.append("colormargin",colorMargin);
        formData.append("colorborder",colorBorder);
        formData.append("idType",idType);
        formData.append("reference",reference);
    }
    
    formData.append("id",id);
    formData.append("topic",topic);
    formData.append("variant",variant);
    formData.append("qty",++qty);

    document.querySelector("#total").innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    productTotal.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/pedidos/updateCart",formData,"post").then(function(objData){
        if(objData.status){
            //qtyProduct.innerHTML = objData.qty;
            productTotal.innerHTML = objData.totalprice;
            document.querySelector("#total").innerHTML = objData.total;
            document.querySelector("#posProducts").innerHTML = objData.html;
            document.querySelector("#total").setAttribute("data-value",objData.value);
        }
    });
}
function productDec(element){
    let formData = new FormData();
    let productTotal = element.parentElement.nextElementSibling;
    let parent = element.parentElement.parentElement.parentElement.parentElement;
    let child = parent.children[1].children[0].children[0].children[1];
    let qty= child.children.length > 2 ? parseInt( child.children[2].children[0].innerHTML) : parseInt( child.children[1].children[0].innerHTML);
    let id = parent.getAttribute("data-id");
    let topic = parent.getAttribute("data-topic");
    let variant = null;
    if(parent.getAttribute("data-variant")){
        variant = parent.getAttribute("data-variant");
    }
    if(topic == 1){
        let height = parent.getAttribute("data-h");
        let width = parent.getAttribute("data-w");
        let margin = parent.getAttribute("data-m");
        let style = parent.getAttribute("data-s");
        let colorMargin = parent.getAttribute("data-mc");
        let colorBorder = parent.getAttribute("data-bc");
        let idType = parent.getAttribute("data-t");
        let reference = parent.getAttribute("data-r");
        formData.append("height",height);
        formData.append("width",width);
        formData.append("margin",margin);
        formData.append("style",style);
        formData.append("colormargin",colorMargin);
        formData.append("colorborder",colorBorder);
        formData.append("idType",idType);
        formData.append("reference",reference);
    }
    if(qty <=1){
        qty = 1;
    }else{
        qty--;
    }
    
    formData.append("id",id);
    formData.append("topic",topic);
    formData.append("variant",variant);
    formData.append("qty",qty);

    document.querySelector("#total").innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    productTotal.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/pedidos/updateCart",formData,"post").then(function(objData){
        if(objData.status){
            //qtyProduct.innerHTML = objData.qty;
            productTotal.innerHTML = objData.totalprice;
            document.querySelector("#total").innerHTML = objData.total;
            document.querySelector("#posProducts").innerHTML = objData.html;
            document.querySelector("#total").setAttribute("data-value",objData.value);
        }
    });
}
function openModalOrder(idOrder=null){
    let modal = new bootstrap.Modal(document.querySelector("#modalPos"));
    moneyReceived.value = document.querySelector("#total").getAttribute("data-value");
    let total = moneyReceived.value;
    document.querySelector(".modal-title").innerHTML = "Punto de venta";
    document.querySelector("#moneyReceived").value = total;
    document.querySelector("#saleValue").innerHTML = "Valor de venta: $"+formatNum(total,".");
    document.querySelector("#moneyBack").innerHTML = "Dinero a devolver: "+MS+0;
    document.querySelector("#selectedCustomer").innerHTML = "";
    document.querySelector("#idOrder").value = "";
    document.querySelector("#txtDate").value = "";
    document.querySelector("#idCustomer").value="";
    document.querySelector("#txtNotePos").value = "";
    document.querySelector("#paymentList").value = 1;
    document.querySelector("#statusList").value = 1;
    document.querySelector("#statusOrder").value = 1;
    document.querySelector("#moneyReceived").removeAttribute("disabled");
    document.querySelector("#totalOrder").innerHTML = 'Dinero recibido <span class="text-danger">*</span>';
    document.querySelector("#discount").removeAttribute("disabled");
    searchCustomers.parentElement.classList.remove("d-none");
    modal.show();
}
function addCustom(element){
    element.setAttribute("onclick","delCustom(this)");
    element.classList.add("border","border-primary");
    document.querySelector("#selectedCustomer").appendChild(element);
    document.querySelector("#customers").innerHTML = "";
    document.querySelector("#idCustomer").value = element.getAttribute("data-id");
    searchCustomers.parentElement.classList.add("d-none");
}
function delCustom(element){
    searchCustomers.parentElement.classList.remove("d-none");
    document.querySelector("#idCustomer").value = 0;
    element.remove();
}
function statusPOS(){
    if(document.querySelector("#posProducts").children.length > 0){
        document.querySelector("#btnPos").classList.remove("d-none");
    }else{
        document.querySelector("#btnPos").classList.add("d-none");
    }
}