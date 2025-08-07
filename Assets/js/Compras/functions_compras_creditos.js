let purchase = {};
let arrAdvance=[];
let arrData = [];
let modalView = document.querySelector("#modalView") ? new bootstrap.Modal(document.querySelector("#modalView")) :"";
let modalAdvance = document.querySelector("#modalAdvance") ? new bootstrap.Modal(document.querySelector("#modalAdvance")) :"";
let totalPendent = 0;
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");

searchHtml.addEventListener("input",function(){getData();});
perPage.addEventListener("change",function(){getData();});
initialDateHtml.addEventListener("input",function(){getData();});
finallDateHtml.addEventListener("input",function(){getData();});

window.addEventListener("load",function(){
    initialDateHtml.value = new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0];
    finallDateHtml.value = new Date().toISOString().split("T")[0]; 
    getData();
});

async function getData(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    formData.append("initial_date",initialDateHtml.value);
    formData.append("final_date",finallDateHtml.value);
    const response = await fetch(base_url+"/Compras/Compras/getCreditPurchases",{method:"POST",body:formData});
    const objData = await response.json();
    arrData = objData.data;
    tableData.innerHTML =objData.html;
    document.querySelector("#pagination").innerHTML = objData.html_pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
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
            let url = base_url+"/Compras/Compras/delPurchase"
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
function viewItem(id){
    document.querySelector("#tablePurchaseDetail").innerHTML ="";
    let index = arrData.findIndex(e=>e.idpurchase==id);
    let purchase = arrData[index];
    let detail = purchase.detail;
    let tableDetail = document.querySelector("#tablePurchaseDetail");
    let subtotal = 0;
    let iva = 0;
    for (let i = 0; i < detail.length; i++) {
        let tr = document.createElement("tr");
        let subtotalProduct = detail[i].price_base*detail[i].qty;
        subtotal+=subtotalProduct;
        tr.innerHTML=`
            <td colspan="3">${detail[i].name+" "+detail[i].variant_name}</td>
            <td>$${formatNum(detail[i].price_base,".")}</td>
            <td class="text-center">${detail[i].qty}</td>
            <td>$${formatNum(subtotalProduct,".")}</td>
        `;
        tableDetail.appendChild(tr);
    }
    iva = purchase.total - subtotal;
    document.querySelector("#strDate").innerHTML = purchase.date;
    document.querySelector("#strId").innerHTML = purchase.idpurchase;
    document.querySelector("#strCode").innerHTML = purchase.cod_bill;
    document.querySelector("#strMethod").innerHTML = purchase.type;
    document.querySelector("#strStatus").innerHTML = purchase.status;
    document.querySelector("#strSupplier").innerHTML = purchase.supplier;
    document.querySelector("#strUser").innerHTML = purchase.user;

    document.querySelector("#subtotal").innerHTML = "$"+formatNum(subtotal,".");
    document.querySelector("#iva").innerHTML = "$"+formatNum(iva,".");
    document.querySelector("#discount").innerHTML = "$"+formatNum(purchase.discount,".");
    document.querySelector("#total").innerHTML = "$"+formatNum(purchase.total,".");
    viewAdvance(id);
    openModal("view");
}
//Funciones para abonos de factura
function viewAdvance(id){
    let viewArrAdvance = [];
    let index = arrData.findIndex(e=>e.idpurchase==id);
    purchase = arrData[index];
    let totalAdvance = purchase.total_advance;
    let totalPendent = purchase.total;
    viewArrAdvance = purchase.detail_advance;
    
    if(viewArrAdvance.length > 0){
        document.querySelector("#viewTablePurchaseAdvance").innerHTML ="";
        let tableDetail = document.querySelector("#viewTablePurchaseAdvance");
        for (let i = 0; i < viewArrAdvance.length; i++) {
            let tr = document.createElement("tr");
            tr.innerHTML=`
                <td>${viewArrAdvance[i].user_name}</td>
                <td>${viewArrAdvance[i].date}</td>
                <td class="text-center">${viewArrAdvance[i].type}</td>
                <td>$${formatNum(viewArrAdvance[i].advance,".")}</td>
            `;
            tableDetail.appendChild(tr);
        }
        totalPendent = totalPendent - totalAdvance;
        document.querySelector("#viewTotalPendent").innerHTML = "$"+formatNum(totalPendent,".");
        document.querySelector("#viewTotalAdvance").innerHTML = "$"+formatNum(totalAdvance,".");
    }
    document.querySelector("#viewStrDateAdvance").innerHTML =  purchase.date;
    document.querySelector("#viewStrIdAdvance").innerHTML = purchase.idpurchase;
    document.querySelector("#viewStrCodeAdvance").innerHTML = purchase.cod_bill;
    document.querySelector("#viewStrSupplierAdvance").innerHTML = purchase.supplier;
    document.querySelector("#viewStrTotalAdvance").innerHTML = "$"+formatNum(purchase.total,".");
    document.querySelector("#viewTotalPendent").innerHTML = "$"+formatNum(totalPendent,".");
    document.querySelector("#viewTotalAdvance").innerHTML = "$"+formatNum(totalAdvance,".");
}
function advanceItem(id){
    document.querySelector("#tablePurchaseAdvance").innerHTML ="";
    arrAdvance = [];
    let index = arrData.findIndex(e=>e.idpurchase==id);
    purchase = arrData[index];
    let totalAdvance = purchase.total_advance;
    let totalPendent = purchase.total;
    arrAdvance = purchase.detail_advance;
    
    if(arrAdvance.length > 0){
        showAdvance();
        totalPendent = totalPendent - totalAdvance;
    }
    document.querySelector("#strDateAdvance").innerHTML = purchase.date;
    document.querySelector("#strIdAdvance").innerHTML = purchase.idpurchase;
    document.querySelector("#strCodeAdvance").innerHTML = purchase.cod_bill;
    document.querySelector("#strSupplierAdvance").innerHTML = purchase.supplier;
    document.querySelector("#strTotalAdvance").innerHTML = "$"+formatNum(purchase.total,".");
    document.querySelector("#totalPendent").innerHTML = "$"+formatNum(totalPendent,".");
    document.querySelector("#totalAdvance").innerHTML = "$"+formatNum(totalAdvance,".");
    openModal();
}
function addAdvance(){
    let strDate = document.querySelector("#subDate").value;
    const strValue = parseInt(document.querySelector("#subDebt").value);
    const strType = document.querySelector("#subType").value;
    let fechaActual = new Date();
    let fechaFormateada = fechaActual.toISOString().split('T')[0];
    strDate = strDate !="" ? strDate : fechaFormateada;
    if(strValue == "" || strValue <= 0){
        Swal.fire("Error","El valor del abono no puede estar vacio","error");
        return false;
    }
    if(strValue > purchase.total_pendent){
        Swal.fire("Error","El valor no puede superar el total pendiente","error");
        return false;
    }
    
    if(arrAdvance.length > 0){
        let total = 0;
        let pendent = 0;
        arrAdvance.forEach(element => {
            total+=element.advance;
        });
        total+=strValue;
        pendent = purchase.total - total;
        if(pendent < 0){
            Swal.fire("Error","Ya ha superdo el total pendiente","error");
            return false;
        }
    }
    arrAdvance.push({
        user:purchase.id_actual_user,
        user_name:purchase.actual_user,
        advance:parseInt(strValue),
        type:strType,
        date:strDate
    });
    showAdvance();
}
function showAdvance(){
    document.querySelector("#tablePurchaseAdvance").innerHTML ="";
    let tableDetail = document.querySelector("#tablePurchaseAdvance");
    let totalAdvance = 0;
    let totalPendent = 0;
    for (let i = 0; i < arrAdvance.length; i++) {
        totalAdvance+=arrAdvance[i].advance;
        let tr = document.createElement("tr");
        tr.innerHTML=`
            <td>${arrAdvance[i].user_name}</td>
            <td>${arrAdvance[i].date}</td>
            <td>$${formatNum(arrAdvance[i].advance,".")}</td>
            <td class="text-center">${arrAdvance[i].type}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-danger text-white" type="button" title="Eliminar" onclick='deleteAdvance(this,${i})' >
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        tableDetail.appendChild(tr);
    }
    totalPendent = purchase.total - totalAdvance;
    document.querySelector("#totalPendent").innerHTML = "$"+formatNum(totalPendent,".");
    document.querySelector("#totalAdvance").innerHTML = "$"+formatNum(totalAdvance,".");
}
function deleteAdvance(element,index){
    element.parentElement.parentElement.remove();
    arrAdvance.splice(index,1);
    showAdvance();
}
async function saveAdvance(){
    const formData = new FormData();
    let totalAdvance = 0;
    let isSuccess = 0;
    arrAdvance.forEach(element => {
        totalAdvance+=element.advance;
    });
    if(totalAdvance == purchase.total){
        isSuccess = 1;
    }
    formData.append("id",purchase.idpurchase);
    formData.append("data",JSON.stringify(arrAdvance));
    formData.append("is_success",isSuccess);
    const btnAdd = document.querySelector("#btnAdd");
    btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
    btnAdd.removeAttribute("disabled");

    const response = await fetch(base_url+"/Compras/Compras/setAdvance",{method:"POST",body:formData});
    const objData = await response.json();
    if(objData.status){
        Swal.fire("Guardado",objData.msg,"success");
        getData();
        modalAdvance.hide();
    }else{
        Swal.fire("Error",objData.msg,"error");
    }
    btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
    btnAdd.removeAttribute("disabled");
}
//Modal
function openModal(type=""){
    if(type=="view"){
        modalView.show();
    }else{
        modalAdvance.show();
    }
}