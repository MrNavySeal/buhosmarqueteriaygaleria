'use strict';


let purchase = {};
let arrAdvance=[];
let arrData = [];
let modalView = document.querySelector("#modalView") ? new bootstrap.Modal(document.querySelector("#modalView")) :"";
let modalEdit = document.querySelector("#modalEdit") ? new bootstrap.Modal(document.querySelector("#modalEdit")) :"";
let tableData = document.querySelector("#tableData");
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");
const selectPago = document.querySelector("#selectPago");
const selectPedido = document.querySelector("#selectPedido");

let totalPendent = 0;
window.addEventListener("load",function(){
    initialDateHtml.value = new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0];
    finallDateHtml.value = new Date().toISOString().split("T")[0]; 
    getData();
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
searchHtml.addEventListener("input",function(){getData();});
perPage.addEventListener("change",function(){getData();});
selectPago.addEventListener("change",function(){getData();});
selectPedido.addEventListener("change",function(){getData();});
initialDateHtml.addEventListener("input",function(){getData();});
finallDateHtml.addEventListener("input",function(){getData();});
async function getData(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    formData.append("initial_date",initialDateHtml.value);
    formData.append("final_date",finallDateHtml.value);
    formData.append("status_payment",selectPago.value);
    formData.append("status_order",selectPedido.value);
    const response = await fetch(base_url+"/Pedidos/Pedidos/getOrders",{method:"POST",body:formData});
    const objData = await response.json();
    tableData.innerHTML =objData.html;
    arrData = objData.full_data;
    document.querySelector("#tableFooter").innerHTML = objData.html_total;
    document.querySelector("#pagination").innerHTML = objData.html_pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}
function viewItem(id){
    document.querySelector("#tablePurchaseDetail").innerHTML ="";
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
    let index = arrData.findIndex(e=>e.idorder==id);
    let order = arrData[index];
    document.querySelector("#statusOrder").value = order.statusorder;
    document.querySelector("#idOrder").value = order.idorder;
    document.querySelector("#sendList").value = order.send_by;
    document.querySelector("#txtGuide").value = order.number_guide;
    if(order.statusorder == "enviado"){
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
    const response = await fetch(base_url+"/Pedidos/Pedidos/updateOrder",{method:"POST",body:formData});
    const objData = await response.json();
    if(objData.status){
        Swal.fire("Actualizado",objData.msg,"success");
        getData();
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
            let url = base_url+"/Pedidos/Pedidos/delOrder"
            let formData = new FormData();
            formData.append("id",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Anulado",objData.msg,"success");
                    getData();
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

function exportData(type){
    const form = document.createElement("form");
    document.body.appendChild(form);
    addField("initial_date",initialDateHtml.value,"hidden",form);
    addField("final_date",finallDateHtml.value,"hidden",form);
    addField("status_payment",selectPago.value,"hidden",form);
    addField("status_order",selectPedido.value,"hidden",form);
    addField("type",type,"hidden",form);

    form.target="_blank";
    form.method="POST";
    form.action=base_url+"/pedidos/reporte/";
    form.submit();
    form.remove();
}

