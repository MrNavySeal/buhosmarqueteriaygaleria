'use strict';
const moneyReceived = document.querySelector("#moneyReceived");
const btnAddPos = document.querySelector("#btnAddPos");
let searchCustomers = document.querySelector("#searchCustomers");
const cupon = document.querySelector("#discount");
let formPOS = document.querySelector("#formSetOrder");
cupon.addEventListener("input",function(){
    if(cupon.value <= 0){
        cupon.value = 0;
    }else if(cupon.value >= 100){
        cupon.value = 90;
    }
    let total = moneyReceived.value;
    total = parseInt(total-(total*(cupon.value*0.01)));
    
    document.querySelector("#saleValue").innerHTML = "Valor de venta: "+MS+total;
    
});
moneyReceived.addEventListener("input",function(){
    let total = document.querySelector("#total").getAttribute("data-value");
    let result = 0;
    if(cupon.value > 0){
        total  = parseInt(total-(total*(cupon.value*0.01)));
        document.querySelector("#saleValue").innerHTML = "Valor de venta: "+MS+total;
    }
    result = moneyReceived.value - total;
    if(result < 0){
        result = 0;
    }
    document.querySelector("#moneyBack").innerHTML = "Dinero a devolver: "+MS+result;
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
if(document.querySelector("#pedidos")){
    let search = document.querySelector("#search");
    let sort = document.querySelector("#sortBy");
    let element = document.querySelector("#listItem");
    
    search.addEventListener('input',function() {
        request(base_url+"/pedidos/search/"+search.value,"","get").then(function(objData){
            if(objData.status){
                element.innerHTML = objData.data;
            }else{
                element.innerHTML = objData.msg;
            }
        });
    });
    
    sort.addEventListener("change",function(){
        request(base_url+"/pedidos/sort/"+sort.value,"","get").then(function(objData){
            if(objData.status){
                element.innerHTML = objData.data;
            }else{
                element.innerHTML = objData.msg;
            }
        });
    });
    element.addEventListener("click",function(e) {
        let element = e.target;
        let id = element.getAttribute("data-id");
        if(element.name == "btnDelete"){
            deleteItem(id);
        }
    });
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
                let url = base_url+"/pedidos/delOrder"
                let formData = new FormData();
                formData.append("idOrder",id);
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
}
function openModalOrder(idOrder=null){
    let modal = new bootstrap.Modal(document.querySelector("#modalPos"));
    if(idOrder!=null){
        let formData = new FormData();
        formData.append("id",idOrder);
        request(base_url+"/pedidos/getOrder",formData,"post").then(function(objData){
            let div = `
            <button class="p-2 btn w-100 text-start border border-primary" data-id="${objData.data.idorder}" onclick="delCustom(this)">
                <p class="m-0 fw-bold">${objData.data.name}</p>
                <p class="m-0">CC/NIT: <span>${objData.data.identification}</span></p>
                <p class="m-0">Correo: <span>${objData.data.email}</span></p>
                <p class="m-0">Teléfono: <span>${objData.data.phone}</span></p>
            </button>
            `;
            document.querySelector("#selectedCustomer").innerHTML = div;
            document.querySelector("#idOrder").value = idOrder;
            document.querySelector("#idCustomer").value=objData.data.personid;
            let arrDate = new String(objData.data.date).split("/");
            document.querySelector("#txtDate").valueAsDate = new Date(arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0]);
            document.querySelector("#txtNotePos").value = objData.data.note;
            document.querySelector("#paymentList").innerHTML = objData.data.payments;
            document.querySelector("#statusList").innerHTML = objData.data.options;
            document.querySelector("#statusOrder").innerHTML = objData.data.statusorder;
            document.querySelector("#moneyReceived").value = objData.data.amount;
            document.querySelector("#moneyReceived").setAttribute("disabled","disabled");
            document.querySelector("#discount").setAttribute("disabled","disabled");
            document.querySelector(".modal-title").innerHTML = "Actualizar pedido";
            searchCustomers.parentElement.classList.add("d-none");
        });
    }else{
        moneyReceived.value = document.querySelector("#total").getAttribute("data-value");
        let total = moneyReceived.value;
        document.querySelector(".modal-title").innerHTML = "Punto de venta";
        document.querySelector("#moneyReceived").value = total;
        document.querySelector("#saleValue").innerHTML = "Valor de venta: "+MS+total;
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
        document.querySelector("#discount").removeAttribute("disabled");
        searchCustomers.parentElement.classList.remove("d-none");
    }
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