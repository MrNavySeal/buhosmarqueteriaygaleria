'use strict';

const moneyReceived = document.querySelector("#moneyReceived");
const btnAddPos = document.querySelector("#btnAddPos");
let searchCustomers = document.querySelector("#searchCustomers");
const cupon = document.querySelector("#discount");
let formPOS = document.querySelector("#formSetOrder");
let element = document.querySelector("#listItem");
let modal = document.querySelector("#modalPos") ? new bootstrap.Modal(document.querySelector("#modalPos")) :"";
let table = new DataTable("#tableData",{
    
    //ajax: " "+base_url+"/pedidos/getOrders",
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/pedidos/getOrders",
        "dataSrc":""
    },
    columns: [
        { data: 'idorder' },
        { data: 'idtransaction' },
        { data: 'date' },
        { data: 'name' },
        { data: 'email' },
        { data: 'phone'},
        { data: 'identification' },
        { data: 'amount' },
        { data: 'type' },
        { data: 'status' },
        { data: 'statusorder' },
        { data: 'options' },
    ],
    responsive: true,
    dom: 'Bfrtip',
    buttons: [
        {
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Excel",
            "titleAttr":"Exportar a Excel",
            "className": "btn btn-success"
        }
    ],
    order: [[1, 'asc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10
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
    let accounts = document.querySelectorAll(".itemAccount");
    let arrAccounts = [];

    if(id <= 0){
        Swal.fire("Error","Por favor, añada un cliente para establecer el pedido","error");
        return false;
    }
    if(received ==""){
        Swal.fire("Error","Los campos con (*) son obligatorios","error");
        return false;
    }

    if(accounts.length > 0){
        for (let i = 0; i < accounts.length; i++) {
            let item = accounts[i];
            let debt = item.children[1].children[0].value;
            if(debt > 0 && debt !=""){
                let date = item.children[0].children[0].value;
                let select = item.children[2].children[0];
                let type = select.options[select.selectedIndex].textContent;
                let obj = {
                    "date":date,
                    "debt":debt,
                    "type":type
                }
                arrAccounts.push(obj);
            }
        }
    }
    let formData = new FormData(formPOS);
    formData.append("suscription",JSON.stringify(arrAccounts));
    /*formData.append("strDate",strDate);
    formData.append("received",received);
    formData.append("strNote",strNote);
    formData.append("txtTransaction",strTransaction);*/
    btnAddPos.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAddPos.setAttribute("disabled","");
    //let modal = new bootstrap.Modal(document.querySelector("#modalPos"));
    request(base_url+"/pedidos/setOrder",formData,"post").then(function(objData){
        btnAddPos.removeAttribute("disabled");
        btnAddPos.innerHTML="Guardar";
        modal.hide();
        if(objData.status){
            Swal.fire("",objData.msg,"success");
            table.ajax.reload();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });

});
/*
element.addEventListener("click",function(e) {
    let element = e.target;
    let id = element.getAttribute("data-id");
    if(element.name == "btnDelete"){
        deleteItem(id);
    }
});*/
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
function openModalOrder(idOrder=null){
    
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
        let arrDateBeat = new String(objData.data.date_beat).split("/");
        document.querySelector("#txtDate").valueAsDate = new Date(arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0]);
        document.querySelector("#txtDateBeat").valueAsDate = new Date(arrDateBeat[2]+"-"+arrDateBeat[1]+"-"+arrDateBeat[0]);
        document.querySelector("#txtNotePos").value = objData.data.note;
        document.querySelector("#paymentList").innerHTML = objData.data.payments;
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#statusOrder").innerHTML = objData.data.statusorder;
        document.querySelector("#totalOrder").innerHTML = 'Venta total <span class="text-danger">*</span>';
        document.querySelector("#listSuscription").innerHTML = objData.data.suscription;
        document.querySelector("#totalDebt").innerHTML = objData.data.totalDebt;
        document.querySelector("#moneyReceived").value = objData.data.amount;
        document.querySelector("#moneyReceived").setAttribute("disabled","disabled");
        document.querySelector("#discount").setAttribute("disabled","disabled");
        document.querySelector(".modal-title").innerHTML = "Actualizar pedido";
        document.querySelector("#itemSuscription").classList.remove("d-none");
        searchCustomers.parentElement.classList.add("d-none");
    });
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
function addSuscription(){
    /*let id = document.querySelector("#idOrder").value;
    let formData = new FormData();
    formData.append("id",id);*/
    let subtotal = 0;
    let total =  parseInt(document.querySelector("#totalDebt").children[0].getAttribute("data-total"));
    let fechaActual = new Date();
    let fechaFormateada = fechaActual.toISOString().split('T')[0]
    document.querySelector("#subDebt").parentElement.nextElementSibling.children[0].options[document.querySelector("#subDebt").parentElement.nextElementSibling.children[0].selectedIndex].setAttribute("selected","");
    let debt = document.querySelector("#subDebt").value;
    let date = document.querySelector("#subDate").value == "" ? fechaFormateada : document.querySelector("#subDate").value;
    let options = document.querySelector("#subDebt").parentElement.nextElementSibling.children[0].innerHTML;
    if(debt <= 0 || debt == ""){
        Swal.fire("Error","Por favor, ingrese el valor del anticipo.","error");
        return false;
    }
    let tr = document.createElement("tr");
    tr.classList.add("itemAccount");
    tr.setAttribute("data-total",debt);
    tr.innerHTML = `
    <td><input type="date" class="form-control" value="${date}"></td>
    <td><input type="number" disabled class="form-control" value="${debt}" placeholder="Abono"></td>
    <td><select class="form-control" aria-label="Default select example">${options}</select></td>
    <td><button class="btn btn-danger" type="button" title="Delete" onclick="delSuscription(this.parentElement.parentElement)"><i class="fas fa-trash-alt"></i></button></td>
    `;
    document.querySelector("#listSuscription").appendChild(tr);
    document.querySelector("#subDebt").value = 0;
    document.querySelector("#subDate").value="";
    document.querySelector("#subDebt").parentElement.nextElementSibling.children[0].value=0;

    document.querySelectorAll(".itemAccount").forEach(element => {
        subtotal += parseInt(element.getAttribute("data-total"));
    });
    let totalSus =total-subtotal;
    document.querySelector("#totalDebt").children[0].children[1].innerHTML = "$"+formatNum(totalSus,".");
}
function delSuscription(element){
    element.remove();
    let subtotal = 0;
    let total =  parseInt(document.querySelector("#totalDebt").children[0].getAttribute("data-total"));
    document.querySelectorAll(".itemAccount").forEach(element => {
        subtotal += parseInt(element.getAttribute("data-total"));
    });
    let totalSus = total-subtotal;
    document.querySelector("#totalDebt").children[0].children[1].innerHTML = "$"+formatNum(totalSus,".");
}