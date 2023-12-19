'use strict';



let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let form = document.querySelector("#formItem");
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/roles/getRoles",
        "dataSrc":""
    },
    columns: [
        { data: 'idrole'},
        { data: 'name' },
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

form.addEventListener("submit",function(e){
    e.preventDefault();
    let strName = document.querySelector("#txtName").value;

    if(strName ==""){
        Swal.fire("Error","Los campos no pueden estar vacíos","error");
        return false;
    }
    
    let url = base_url+"/roles/setRole";
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
            document.querySelector("#idRol").value ="";
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
})

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector(".modal-title").innerHTML = "Nuevo rol";
        document.querySelector("#idRol").value = "";
        document.querySelector("#txtName").value = "";
        modal.show();
    });
}
function editItem(id){
    let formData = new FormData();
    formData.append("idRol",id);
    request(base_url+"/roles/getRole",formData,"post").then(function(objData){
        console.log(objData);
        if(objData.status){
            document.querySelector("#txtName").value = objData.data.name;
            document.querySelector("#idRol").value = objData.data.idrole;
            document.querySelector(".modal-title").innerHTML = "Actualizar rol";
            modal.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function permitItem(id){
    let formData = new FormData();
    formData.append("idRol",id);
    requestText(base_url+"/roles/getPermits",formData,"post").then(function(objData){
        document.querySelector("#contentResponse").innerHTML =objData;
        let modalPermits = new bootstrap.Modal(document.querySelector("#modalPermits"));
        modalPermits.show();
        document.querySelector("#modalPermits").addEventListener("submit",editPermits,false);

    });
}
function editPermits(e){
    e.preventDefault();
    let formData = new FormData(document.querySelector("#formPermits"));
    document.querySelector("#btnPermit").innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    document.querySelector("#btnPermit").setAttribute("disabled","");
    request(base_url+"/roles/setPermits",formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#btnPermit").innerHTML=`<i class="fas fa-save"></i> Guardar`;
            document.querySelector("#btnPermit").removeAttribute("disabled");
            Swal.fire("Permisos",objData.msg,"success");

        }else{
            Swal.fire("Permisos",objData.msg,"error");
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
            let url = base_url+"/roles/delRole"
            let formData = new FormData();
            formData.append("idRol",id);
            request(url,formData,"post").then(function(objData){
                Swal.fire("Eliminado",objData.msg,"success");
                table.ajax.reload();
            });
        }
    });
}
