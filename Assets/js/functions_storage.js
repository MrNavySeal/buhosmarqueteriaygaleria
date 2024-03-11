let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let formItem = document.querySelector("#formItem");
let element = document.querySelector("#listItem");

let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/compras/getProducts",
        "dataSrc":""
    },
    columns: [
        { data: 'id_storage'},
        { data: 'reference'},
        { data: 'name' },
        { data: 'supplier'},
        { data: 'cost' },
        { data: 'iva'},
        { data: 'costiva'},
        { data: 'costtotal'},
        { data: 'status'},
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
    order: [[1, 'asc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector(".modal-title").innerHTML = "Nuevo producto";
        document.querySelector("#txtName").value = "";
        document.querySelector("#txtReference").value = "";
        document.querySelector("#txtCost").value = "";
        document.querySelector("#typeList").value = 1;
        document.querySelector("#statusList").value = 1;
        document.querySelector("#suppList").value = 1;
        document.querySelector("#id").value = 0;
        modal.show();
    });
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let name = document.querySelector("#txtName").value;
        let cost = document.querySelector("#txtCost").value;

        if(cost == "" || name==""){
            Swal.fire("Error","Todos los campos con (*) son obligatorios","error");
            return false;
        }
        if(cost < 0){
            cost.value = "";
            Swal.fire("Error","El monto no puede ser menor a 0","error");
            return false;
        }
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(base_url+"/compras/setProduct",formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
                table.ajax.reload();
                modal.hide();
                form.reset();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    });
}
function editItem(id){
    let url = base_url+"/compras/getProduct";
    let formData = new FormData();
    formData.append("id",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector(".modal-title").innerHTML ="Actualizar producto";
            document.querySelector("#statusList").value = objData.data.status;
            document.querySelector("#suppList").value = objData.data.supplier_id;
            document.querySelector("#typeList").value = objData.data.import;
            document.querySelector("#txtName").value = objData.data.name;
            document.querySelector("#id").value = objData.data.id_storage;
            document.querySelector("#txtReference").value = objData.data.reference;
            document.querySelector("#txtCost").value = objData.data.cost;
            modal.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
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
            let url = base_url+"/compras/delProduct"
            let formData = new FormData();
            formData.append("id",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    table.ajax.reload();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}