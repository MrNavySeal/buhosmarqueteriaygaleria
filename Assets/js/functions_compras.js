let purchase = {};
let arrAdvance=[];
let modalView = document.querySelector("#modalView") ? new bootstrap.Modal(document.querySelector("#modalView")) :"";
let modalAdvance = document.querySelector("#modalAdvance") ? new bootstrap.Modal(document.querySelector("#modalAdvance")) :"";
let totalPendent = 0;
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Compras/getPurchases",
        "dataSrc":""
    },
    columns: [
        
        { data: 'idpurchase'},
        { data: 'cod_bill' },
        { data: 'date' },
        { data: 'supplier' },
        { data: 'user' },
        { data: 'type' },
        { data: 'format_total' },
        { data: 'format_pendent' },
        { data: 'status' },
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
            let url = base_url+"/compras/delPurchase"
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
function viewAdvance(id){
    arrAdvance = [];
    const arrPurchase = table.rows().data().toArray();
    let index = arrPurchase.findIndex(e=>e.idpurchase==id);
    purchase = arrPurchase[index];
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
    const arrPurchase = table.rows().data().toArray();
    let index = arrPurchase.findIndex(e=>e.idpurchase==id);
    let purchase = arrPurchase[index];
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