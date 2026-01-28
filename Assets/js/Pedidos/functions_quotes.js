'use strict';


let purchase = {};
let arrAdvance=[];
let arrData = [];
let modalView = document.querySelector("#modalView") ? new bootstrap.Modal(document.querySelector("#modalView")) :"";
let modalEdit = document.querySelector("#modalSetOrder") ? new bootstrap.Modal(document.querySelector("#modalSetOrder")) :"";
let tableData = document.querySelector("#tableData");
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");
const btnAddPos = document.querySelector("#btnSetPurchase");

let totalPendent = 0;
window.addEventListener("load",function(){
    initialDateHtml.value = new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0];
    finallDateHtml.value = new Date().toISOString().split("T")[0]; 
    getData();
});
if(document.querySelector("#btnSetPurchase")){
    btnAddPos.addEventListener("click",function(e){
        e.preventDefault();
        Swal.fire({
            title:"¿Estás segur@ de facturar esta cotización?",
            text:"",
            icon: 'warning',
            showCancelButton:true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText:"Sí, facturar",
            cancelButtonText:"No, cancelar"
        }).then(function(result){
            if(result.isConfirmed){
                const url = base_url+"/Pedidos/Cotizaciones/setOrder";
                const formData= new FormData();
                formData.append("id",document.querySelector("#id").value);
                formData.append("type",document.querySelector("#paymentList").value);
                formData.append("statusOrder",document.querySelector("#statusOrder").value);
                btnAddPos.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
                btnAddPos.setAttribute("disabled","");
                request(url,formData,"post").then(function(objData){
                    btnAddPos.removeAttribute("disabled");
                    btnAddPos.innerHTML="Guardar";
                    if(objData.status){
                        Swal.fire("Facturado",objData.msg,"success");
                        modalEdit.hide();
                        getData();
                    }else{
                        Swal.fire("Error",objData.msg,"error");
                    }
                });
            }
        });
    });
}

searchHtml.addEventListener("input",function(){getData();});
perPage.addEventListener("change",function(){getData();});
initialDateHtml.addEventListener("input",function(){getData();});
finallDateHtml.addEventListener("input",function(){getData();});

async function getData(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    formData.append("initial_date",initialDateHtml.value);
    formData.append("final_date",finallDateHtml.value);
    const response = await fetch(base_url+"/Pedidos/Cotizaciones/getQuotes",{method:"POST",body:formData});
    const objData = await response.json();
    arrData = objData.data;
    tableData.innerHTML =objData.html;
    document.querySelector("#pagination").innerHTML = objData.html_pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}
function viewItem(id){
    document.querySelector("#tablePurchaseDetail").innerHTML ="";
    let index = arrData.findIndex(e=>e.id==id);
    let order = arrData[index];
    let detail = order.detail;
    let tableDetail = document.querySelector("#tablePurchaseDetail");
    let subtotal = 0;
    for (let i = 0; i < detail.length; i++) {
        let tr = document.createElement("tr");
        let product = detail[i];
        let flag = product.description.includes("{");
        let subtotalProduct = product.price*product.qty;
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
            <td class="text-end">$${formatNum(product.price,".")}</td>
            <td class="text-center">${product.qty}</td>
            <td class="text-end">$${formatNum(subtotalProduct,".")}</td>
        `;
        tableDetail.appendChild(tr);
    }
    let discount = parseInt(order.discount);
    let total = subtotal-discount;
    document.querySelector("#strDate").innerHTML = order.date;
    document.querySelector("#strDateBeat").innerHTML = order.date_beat;
    document.querySelector("#strId").innerHTML = order.id;
    document.querySelector("#strStatus").innerHTML = order.status;
    document.querySelector("#strName").innerHTML = order.name;
    document.querySelector("#strAddress").innerHTML = order.address;
    document.querySelector("#strPhone").innerHTML = order.phone;
    document.querySelector("#strEmail").innerHTML = order.email;
    document.querySelector("#strNit").innerHTML = order.identification;
    document.querySelector("#strNotes").innerHTML = order.note;

    document.querySelector("#subtotal").innerHTML = "$"+formatNum(subtotal,".");
    document.querySelector("#orderDiscount").innerHTML = "$"+formatNum(discount,".");
    document.querySelector("#total").innerHTML = "$"+formatNum(total,".");
    openModal("view");
}
function editItem(id){
    document.querySelector("#id").value = id;
    openModal("edit");
}
function deleteItem(id){
    Swal.fire({
        title:"¿Estás segur@ de anular la cotización?",
        text:"Tendrás que volverla a hacer...",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Sí, anular",
        cancelButtonText:"No, cancelar"
    }).then(function(result){
        if(result.isConfirmed){
            let url = base_url+"/Pedidos/Cotizaciones/delOrder"
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

