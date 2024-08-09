'use strict';


let purchase = {};
let arrAdvance=[];
let modalView = document.querySelector("#modalView") ? new bootstrap.Modal(document.querySelector("#modalView")) :"";
let modalEdit = document.querySelector("#modalEdit") ? new bootstrap.Modal(document.querySelector("#modalEdit")) :"";

let totalPendent = 0;
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
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
        { data: 'type' },
        { data: 'format_amount' },
        { data: 'format_pendent' },
        { data: 'status' },
        { data: 'statusorder' },
        { data: 'options' },
    ],
    responsive: true,
    buttons: [
        {
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Excel",
            "titleAttr":"Exportar a Excel",
            "className": "btn btn-success mt-2"
        }
    ],
    order: [[0, 'desc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});
window.addEventListener("load",function(){
    if(document.querySelector("#modalEdit")){
        const statusOrder = document.querySelector("#statusOrder");
        statusOrder.addEventListener("change",function(){
            if(statusOrder.value == "enviado"){
                document.querySelector("#divSend").classList.remove("d-none");
            }else{
                document.querySelector("#divSend").classList.add("d-none");
            }
        });
    }
});
function viewItem(id){
    document.querySelector("#tablePurchaseDetail").innerHTML ="";
    const arrData = table.rows().data().toArray();
    let index = arrData.findIndex(e=>e.idorder==id);
    let order = arrData[index];
    let detail = order.detail;
    let tableDetail = document.querySelector("#tablePurchaseDetail");
    let subtotal = 0;
    for (let i = 0; i < detail.length; i++) {
        let tr = document.createElement("tr");
        let product = detail[i];
        let flag = product.description.includes("{");
        let subtotalProduct = product.price*product.quantity;
        let productName = "";
        if(flag){
            let description = JSON.parse(product.description);
            if(product.topic == 1){
                let colorFrame =  description.colorframe ? description.colorframe : "";
                let material = description.material ? description.material : "";
                let marginStyle = description.style == "Flotante" || description.style == "Flotante sin marco interno" ? "Fondo" : "Paspartú";
                let borderStyle = description.style == "Flotante" ? "marco interno" : "bocel";
                let glassStyle = description.idType == 4 ? "Bastidor" : "Tipo de vidrio";
                let measureFrame = (description.width+(description.margin*2))+"cm X "+(description.height+(description.margin*2))+"cm"; 
                let img ="";
                if(description.type){
                    if(description.photo !=""){
                        img = `<a href="${base_url+"/Assets/images/uploads/"+description.photo}" target="_blank">Ver imagen</a><br>`;
                    }
                    productName = `
                        ${img}
                        ${description.name}
                        <ul>
                            <li><span class="fw-bold t-color-3">Referencia: </span>${description.reference}</li>
                            <li><span class="fw-bold t-color-3">Color del marco: </span>${colorFrame}</li>
                            <li><span class="fw-bold t-color-3">Material: </span>${material}</li>
                            <li><span class="fw-bold t-color-3">Orientación: </span>${description.orientation}</li>
                            <li><span class="fw-bold t-color-3">Estilo de enmarcación: </span>${description.style}</li>
                            <li><span class="fw-bold t-color-3">${marginStyle}: </span>${description.margin}cm</li>
                            <li><span class="fw-bold t-color-3">Medida imagen: </span>${description.width}cm X ${description.height}cm</li>
                            <li><span class="fw-bold t-color-3">Medida marco: </span>${measureFrame}</li>
                            <li><span class="fw-bold t-color-3">Color del ${marginStyle}: </span>${description.colormargin}</li>
                            <li><span class="fw-bold t-color-3">Color del ${borderStyle}: </span>${description.colorborder}</li>
                            <li><span class="fw-bold t-color-3">${glassStyle}: </span>${description.glass}</li>
                        </ul>
                    `;
                }else{
                    if(description.img !="" && description.img !=null){
                        img = `<a href="${base_url+"/Assets/images/uploads/"+description.img}" target="_blank">Ver imagen</a><br>`;
                    }
                    let html ="";
                    const detail = description.detail;
                    detail.forEach(e => {
                        html+=`<li><span class="fw-bold t-color-3">${e.name}: </span>${e.value}</li>`;
                    });
                    productName = `${img}${description.name}<ul>${html}</ul>`;
                }
            }else{
                let variantDetail = "";
                for (let j = 0; j < description.detail.length; j++) {
                    const e = description.detail[j];
                    variantDetail+=`<li><span class="fw-bold t-color-3">${e.name}: </span>${e.option}</li>`
                }
                productName = `${description.name}<ul>${variantDetail}</ul>`;
            }
        }else{
            productName = product.description;
        }
        subtotal+=subtotalProduct;
        tr.innerHTML=`
            <td>${product.reference}</td>
            <td>${productName}</td>
            <td>$${formatNum(product.price,".")}</td>
            <td class="text-center">${product.quantity}</td>
            <td>$${formatNum(subtotalProduct,".")}</td>
        `;
        tableDetail.appendChild(tr);
    }
    let discount = parseInt(order.coupon);
    let total = (subtotal+order.shipping)-discount;
    document.querySelector("#strMethod").innerHTML = order.type;
    document.querySelector("#strDate").innerHTML = order.date;
    document.querySelector("#strDateBeat").innerHTML = order.date_beat;
    document.querySelector("#strId").innerHTML = order.idorder;
    document.querySelector("#strStatus").innerHTML = order.status;
    document.querySelector("#strStatusOrder").innerHTML = order.statusorder;
    document.querySelector("#strCode").innerHTML = order.idtransaction;
    document.querySelector("#strName").innerHTML = order.name;
    document.querySelector("#strAddress").innerHTML = order.address;
    document.querySelector("#strPhone").innerHTML = order.phone;
    document.querySelector("#strEmail").innerHTML = order.email;
    document.querySelector("#strNit").innerHTML = order.identification;
    document.querySelector("#strNotes").innerHTML = order.note;

    document.querySelector("#subtotal").innerHTML = "$"+formatNum(subtotal,".");
    document.querySelector("#orderDiscount").innerHTML = "$"+formatNum(discount,".");
    document.querySelector("#total").innerHTML = "$"+formatNum(total,".");
    document.querySelector("#shipping").innerHTML = "$"+formatNum(order.shipping,".");

    if(order.statusval =="pendent" || order.type =="credito"){
        viewAdvance(order.idorder);
        document.querySelector("#navAdvance-tab").parentElement.classList.remove("d-none");
    }else{
        document.querySelector("#navAdvance-tab").parentElement.classList.add("d-none");
    }
    openModal("view");
}
function viewAdvance(id){
    arrAdvance = [];
    const arrData = table.rows().data().toArray();
    let index = arrData.findIndex(e=>e.idorder==id);
    let order = arrData[index];
    let totalAdvance = order.total_advance;
    let totalPendent = order.amount;
    arrAdvance = order.detail_advance;
    
    if(arrAdvance.length > 0){
        document.querySelector("#viewTablePurchaseAdvance").innerHTML ="";
        let tableDetail = document.querySelector("#viewTablePurchaseAdvance");
        for (let i = 0; i < arrAdvance.length; i++) {
            let tr = document.createElement("tr");
            tr.innerHTML=`
                <td>${arrAdvance[i].user_name}</td>
                <td>${arrAdvance[i].date}</td>
                <td class="text-center">${arrAdvance[i].type}</td>
                <td>$${formatNum(arrAdvance[i].advance,".")}</td>
            `;
            tableDetail.appendChild(tr);
        }
        totalPendent = totalPendent - totalAdvance;
        document.querySelector("#viewTotalPendent").innerHTML = "$"+formatNum(totalPendent,".");
        document.querySelector("#viewTotalAdvance").innerHTML = "$"+formatNum(totalAdvance,".");
    }
    document.querySelector("#viewStrDateAdvance").innerHTML = order.date;
    document.querySelector("#viewStrIdAdvance").innerHTML = order.idorder;
    document.querySelector("#viewStrTotalAdvance").innerHTML = "$"+formatNum(order.amount,".");
    document.querySelector("#viewTotalPendent").innerHTML = "$"+formatNum(totalPendent,".");
    document.querySelector("#viewTotalAdvance").innerHTML = "$"+formatNum(totalAdvance,".");
}
function editItem(id){
    const arrData = table.rows().data().toArray();
    let index = arrData.findIndex(e=>e.idorder==id);
    let order = arrData[index];
    document.querySelector("#statusOrder").value = order.statusorderval;
    document.querySelector("#idOrder").value = order.idorder;
    document.querySelector("#sendList").value = order.send_by;
    document.querySelector("#txtGuide").value = order.number_guide;
    if(order.statusorderval == "enviado"){
        document.querySelector("#divSend").classList.remove("d-none");
    }else{
        document.querySelector("#divSend").classList.add("d-none");
    }
    openModal("edit");
}
async function updateItem(){
    let statusOrder = document.querySelector("#statusOrder").value;
    let sendList = document.querySelector("#sendList").value;
    let strGuide = document.querySelector("#txtGuide").value;
    let isEmail = document.querySelector("#isEmail").checked;
    let intId = document.querySelector("#idOrder").value;

    const arrData = table.rows().data().toArray();
    let index = arrData.findIndex(e=>e.idorder==intId);
    let order = arrData[index];

    if(statusOrder == "enviado" && (sendList == "" || strGuide =="")){
        Swal.fire("Error","Debe seleccionar la empresa de mensajería y escribir el número de guía","error");
        return false;
    }
    const btnAdd = document.querySelector("#btnAdd");
    const formData = new FormData();
    formData.append("id", intId);
    formData.append("status_order",statusOrder);
    formData.append("guide",strGuide);
    formData.append("send_by",sendList);
    formData.append("is_email",isEmail ? 1 : 0);
    formData.append("email",order.email);
    formData.append("name",order.name);
    btnAdd.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAdd.setAttribute("disabled","");
    const response = await fetch(base_url+"/pedidos/updateOrder",{method:"POST",body:formData});
    const objData = await response.json();
    if(objData.status){
        Swal.fire("Actualizado",objData.msg,"success");
        table.ajax.reload();
        modalEdit.hide();
    }else{
        Swal.fire("Error",objData.msg,"error");
    }
    btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
    btnAdd.removeAttribute("disabled");
}
function deleteItem(id){
    Swal.fire({
        title:"¿Estás segur@ de anular la factura?",
        text:"Tendrás que volverla a hacer...",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Sí, anular",
        cancelButtonText:"No, cancelar"
    }).then(function(result){
        if(result.isConfirmed){
            let url = base_url+"/pedidos/delOrder"
            let formData = new FormData();
            formData.append("id",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Anulado",objData.msg,"success");
                    table.ajax.reload();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}
//Modal
function openModal(type=""){
    if(type=="view"){
        modalView.show();
    }else if(type=="edit"){
        modalEdit.show();
    }
}

