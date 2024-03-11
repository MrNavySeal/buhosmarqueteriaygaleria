const listProducts = document.querySelector("#listProducts");
const btnAdd = document.querySelector(".btnAdd");
const btnPurchase = document.querySelector("#btnPurchase");
const total = document.querySelector("#total");
const selectSupplier = document.querySelector("#selectSupplier");
const selectProduct = document.querySelector("#selectProduct");
const element = document.querySelector("#listItem");
const setSimple = document.querySelector("#setSimple");
const setCustom = document.querySelector("#setCustom");
const selectType = document.querySelector("#selectType");


let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/compras/getPurchases",
        "dataSrc":""
    },
    columns: [
        { data: 'idpurchase'},
        { data: 'name' },
        { data: 'total'},
        { data: 'date' },
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
    order: [[1, 'desc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});

selectType.addEventListener("change",function(){
    if(selectType.value == 1){
        setSimple.classList.remove("d-none");
        setCustom.classList.add("d-none");
        selectSupplier.value=0;
        selectProduct.value=0;
    }else{
        setSimple.classList.add("d-none");
        setCustom.classList.remove("d-none");
    }
});
element.addEventListener("click",function(e) {
    let element = e.target;
    let id = element.getAttribute("data-id");
    if(element.name == "btnDelete"){
        deleteItem(id);
    }
});
selectProduct.addEventListener("change",function(){
    if(((selectProduct.value == 0 || selectSupplier.value == 0)&& selectType.value == 1)){
        btnAdd.setAttribute("disabled","disabled");
    }else{
        btnAdd.removeAttribute("disabled");
    }
});
selectSupplier.addEventListener("change",function(){
    let formData = new FormData();
    formData.append("id",selectSupplier.value);

    if(selectType.value == 1){
        if(selectProduct.value == 0 || selectSupplier.value == 0){
            btnAdd.setAttribute("disabled","disabled");
        }else{
            btnAdd.removeAttribute("disabled");
        }
        request(base_url+"/compras/getSelectProducts",formData,"post").then(function(objData){
            selectProduct.innerHTML = objData;
        });
    }
    
    /*document.querySelector("#txtProduct").value ="";
    document.querySelector("#intQty").value ="";
    document.querySelector("#intPrice").value="";
    total.innerHTML = "$0";
    listProducts.innerHTML="";*/
});
function addProduct(){
    let id = selectProduct.value;
    let formData = new FormData();
    formData.append("type",selectType.value);
    formData.append("id",id);
    if(selectType.value==1){
        
        let intQty = document.querySelector("#intQty").value;
        let discount = document.querySelector("#intDiscount").value;
        if(intQty == "" || intQty < 0){
            Swal.fire("Error","Por favor, ingrese una cantidad correcta","error");
            document.querySelector("#intQty").value = "";
            return false;
        }
        if(discount != "" && (discount <= 0 || discount>100)){
            Swal.fire("Error","Por favor, ingrese un descuento correcto","error");
            document.querySelector("#intDiscount").value="";
            return false;
        }
        formData.append("discount",discount);
        formData.append("qty",intQty);
    }else{
        let customProduct = document.querySelector("#customProduct").value;
        let customQty = document.querySelector("#customQty").value;
        let customPrice = document.querySelector("#customPrice").value;
        formData.append("price",customPrice);
        formData.append("name",customProduct);
        formData.append("qty",customQty);
    }
    
    request(base_url+"/compras/getSelectProduct",formData,"post").then(function(objData){
        if(objData.status){
            let tr = document.createElement("tr");
            tr.setAttribute("data-reference",objData.reference);
            tr.setAttribute("data-id",objData.id);
            tr.setAttribute("data-type",objData.type);
            tr.setAttribute("data-name",objData.name);
            tr.setAttribute("data-ivatext",objData.ivatext);
            tr.setAttribute("data-subtotal",objData.subtotal);
            tr.setAttribute("data-total",objData.total);
            tr.setAttribute("data-iva",objData.iva);
            tr.setAttribute("data-discount",objData.discount);
            tr.setAttribute("data-cost",objData.cost);
            tr.setAttribute("data-qty",objData.qty);
            tr.classList.add("buyItem");
            tr.innerHTML = objData.data;
            listProducts.appendChild(tr);
            let products = document.querySelectorAll(".buyItem");
            let total = 0;
            let iva = 0;
            let subtotal = 0;
            let discount=0;
            for (let i = 0; i < products.length; i++) {
                subtotal+=parseInt(products[i].getAttribute("data-subtotal"));
                total+=parseInt(products[i].getAttribute("data-total"));
                iva+=parseInt(products[i].getAttribute("data-iva"));
                discount+=parseInt(products[i].getAttribute("data-discount"));
            }
            document.querySelector("#txtSubtotal").innerHTML = "$"+formatNum(subtotal,".");
            document.querySelector("#txtTotal").innerHTML = "$"+formatNum(total,".");
            document.querySelector("#txtIva").innerHTML = "$"+formatNum(iva,".");
            document.querySelector("#txtDiscount").innerHTML = "$"+formatNum(discount,".");
        }
    });
}
btnPurchase.addEventListener("click",function(){
    let products = document.querySelectorAll(".buyItem");
    let arrProducts = [];
    let totalValue = 0;
    let strDate = document.querySelector("#txtDate").value;

    if(products.length == 0){
        Swal.fire("Error","No hay productos para procesar la compra.","error");
        return false;
    }

    for (let i = 0; i < products.length; i++) {
        arr = {
            "reference":products[i].getAttribute("data-reference"),
            "id":products[i].getAttribute("data-id"),
            "type":products[i].getAttribute("data-type"),
            "name":products[i].getAttribute("data-name"),
            "ivatext":products[i].getAttribute("data-ivatext"),
            "qty":products[i].getAttribute("data-qty"),
            "cost":products[i].getAttribute("data-cost"),
            "discount":products[i].getAttribute("data-discount"),
            "iva":products[i].getAttribute("data-iva"),
            "subtotal":products[i].getAttribute("data-subtotal"),
            "total":products[i].getAttribute("data-total"),
        };
        totalValue += parseInt(products[i].getAttribute("data-total"));
        arrProducts.push(arr);
    }
    let formData = new FormData();
    formData.append("date",strDate);
    formData.append("idSupplier",selectSupplier.value);
    formData.append("arrProducts",JSON.stringify(arrProducts));
    formData.append("total",totalValue);
    request(base_url+"/compras/setPurchase",formData,"post").then(function(objData){
        if(objData.status){
            window.location.reload();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
},false);

function delProduct(element){
    element.remove();
    let products = document.querySelectorAll(".buyItem");
    let total = 0;
    let iva = 0;
    let subtotal = 0;
    let discount=0;
    for (let i = 0; i < products.length; i++) {
        subtotal+=parseInt(products[i].getAttribute("data-subtotal"));
        total+=parseInt(products[i].getAttribute("data-total"));
        iva+=parseInt(products[i].getAttribute("data-iva"));
        discount+=parseInt(products[i].getAttribute("data-discount"));
    }
    document.querySelector("#txtSubtotal").innerHTML = "$"+formatNum(subtotal,".");
    document.querySelector("#txtTotal").innerHTML = "$"+formatNum(total,".");
    document.querySelector("#txtIva").innerHTML = "$"+formatNum(iva,".");
    document.querySelector("#txtDiscount").innerHTML = "$"+formatNum(discount,".");
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