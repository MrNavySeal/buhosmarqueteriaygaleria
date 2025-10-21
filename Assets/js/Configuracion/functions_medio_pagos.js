'use strict';


let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/English.json"
    },
    "ajax":{
        "url": " "+base_url+"/Configuracion/MedioPagos/getSpecs",
        "dataSrc":""
    },
    columns: [
        { data: 'id'},
        { data: 'name' },
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

function openModal(){
    document.querySelector(".modal-title").innerHTML = "Nuevo método de pago";
    document.querySelector("#txtName").value = "";
    document.querySelector("#statusList").value = 1;
    document.querySelector("#id").value ="";
    modal.show();
}

if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strName = document.querySelector("#txtName").value;
        if(strName == ""){
            Swal.fire("Error","All fields with (*) are required","error");
            return false;
        }
        
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        request(base_url+"/Configuracion/MedioPagos/setSpec",formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Save`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Saved",objData.msg,"success");
                table.ajax.reload();
                form.reset();
                modal.hide();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
     
function editItem(id){
    let url = base_url+"/Configuracion/MedioPagos/getSpec";
    let formData = new FormData();
    formData.append("id",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#txtName").value = objData.data.name;
            document.querySelector("#statusList").value = objData.data.status;
            document.querySelector("#id").value = objData.data.id;
            document.querySelector(".modal-title").innerHTML = "Actualizar método de pago";
            modal.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function deleteItem(id){
    Swal.fire({
        title:"¿Estas seguro?",
        text:"Se eliminará para siempre...",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Yes",
        cancelButtonText:"No"
    }).then(function(result){
        if(result.isConfirmed){
            let url = base_url+"/Configuracion/MedioPagos/delSpec"
            let formData = new FormData();
            formData.append("id",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Deleted",objData.msg,"success");
                    table.ajax.reload();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}
