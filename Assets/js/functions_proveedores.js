'use strict';


let element = document.querySelector("#listItem");

let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/compras/getSuppliers",
        "dataSrc":""
    },
    columns: [
        { data: 'idsupplier'},
        { data: 'nit'},
        { data: 'name' },
        { data: 'email'},
        { data: 'phone' },
        { data: 'address'},
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
        document.querySelector(".modal-title").innerHTML = "Nuevo proveedor";
        document.querySelector("#txtAddress").value = "";
        document.querySelector("#txtPhone").value = "";
        document.querySelector("#txtEmail").value = "";
        document.querySelector("#txtName").value = "";
        document.querySelector("#txtNit").value = "";
        document.querySelector("#idSupplier").value =0;
        modal.show();
    });
}

if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strName = document.querySelector("#txtName").value;
        let strPhone = document.querySelector("#txtPhone").value;
        let strEmail = document.querySelector("#txtEmail").value;
        if(strName == "" || strPhone == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(strEmail !=""){
            if(!fntEmailValidate(strEmail)){
                Swal.fire("Error","El email es invalido","error");
                return false;
            }
        }
        if(strPhone.length < 10){
            Swal.fire("Error","El número de teléfono debe tener al menos 10 dígitos","error");
            return false;
        }
        
        let url = base_url+"/compras/setSupplier";
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");

        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(url,formData,"post").then(function(objData){
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
    })
}

function editItem(id){
    let formData = new FormData();
    formData.append("idSupplier",id);
    request(base_url+"/compras/getSupplier",formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector(".modal-title").innerHTML = "Actualizar proveedor";
            document.querySelector("#txtAddress").value = objData.data.address;
            document.querySelector("#txtPhone").value = objData.data.phone;
            document.querySelector("#txtEmail").value = objData.data.email;
            document.querySelector("#txtName").value = objData.data.name;
            document.querySelector("#txtNit").value = objData.data.nit;
            document.querySelector("#idSupplier").value = objData.data.idsupplier;
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
            let url = base_url+"/compras/delSupplier"
            let formData = new FormData();
            formData.append("idSupplier",id);
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
