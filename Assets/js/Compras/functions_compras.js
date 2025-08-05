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
    const response = await fetch(base_url+"/Compras/Compras/getPurchases",{method:"POST",body:formData});
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
function viewAdvance(id){
    arrAdvance = [];
    let index = arrData.findIndex(e=>e.idpurchase==id);
    purchase = arrData[index];
    let totalAdvance = purchase.total_advance;
    let totalPendent = purchase.total;
    arrAdvance = purchase.detail_advance;
    
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
    document.querySelector("#viewStrDateAdvance").innerHTML = purchase.date;
    document.querySelector("#viewStrIdAdvance").innerHTML = purchase.idpurchase;
    document.querySelector("#viewStrCodeAdvance").innerHTML = purchase.cod_bill;
    document.querySelector("#viewStrSupplierAdvance").innerHTML = purchase.supplier;
    document.querySelector("#viewStrTotalAdvance").innerHTML = "$"+formatNum(purchase.total,".");
    document.querySelector("#viewTotalPendent").innerHTML = "$"+formatNum(totalPendent,".");
    document.querySelector("#viewTotalAdvance").innerHTML = "$"+formatNum(totalAdvance,".");
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
    if(purchase.type =="credito"){
        viewAdvance(id);
        document.querySelector("#navAdvance-tab").parentElement.classList.remove("d-none");
    }else{
        document.querySelector("#navAdvance-tab").parentElement.classList.add("d-none");
    }
    openModal("view");
}
//Modal
function openModal(type=""){
    if(type=="view"){
        modalView.show();
    }
}