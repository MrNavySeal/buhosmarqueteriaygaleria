'use strict';


let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Marqueteria/getMaterials",
        "dataSrc":""
    },
    columns: [
        { data: 'name'},
        { data: 'price' },
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
        document.querySelector(".modal-title").innerHTML = "Nuevo cliente";
        document.querySelector("#txtName").value = "";
        document.querySelector("#txtPrice").value = "";
        document.querySelector("#txtUnit").value ="";
        document.querySelector("#idMaterial").value ="";
        modal.show();
    });
}

if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strName = document.querySelector("#txtName").value;
        let intPrice = document.querySelector("#txtPrice").value;
        let strUnit = document.querySelector("#txtUnit").value;

        if(strName == "" || strUnit == "" || intPrice==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");

        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(base_url+"/marqueteria/setMaterial",formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
                table.ajax.reload();
                modal.hide();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    });
}

function editItem(id){
    let url = base_url+"/marqueteria/getMaterial";
    let formData = new FormData();
    formData.append("idMaterial",id);
    request(url,formData,"post").then(function(objData){
        document.querySelector("#idMaterial").value = objData.data.id;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#txtPrice").value = objData.data.price;
        document.querySelector("#txtUnit").value = objData.data.unit;
        document.querySelector(".modal-title").innerHTML = "Actualizar cliente";
        modal.show();
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
            let url = base_url+"/marqueteria/delMaterial"
            let formData = new FormData();
            formData.append("idMaterial",id);
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
