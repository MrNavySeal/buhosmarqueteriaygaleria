'use strict';

let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Marqueteria/Marqueteria/getProperties",
        "dataSrc":""
    },
    columns: [
        { data: 'id'},
        { data: 'name'},
        { data: 'is_material'},
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
    order: [[0, 'asc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});
function openModal(){
    getFraming();
    document.querySelector(".modal-title").innerHTML = "Nueva propiedad";
    document.querySelector("#id").value = "";
    document.querySelector("#txtName").value = "";
    document.querySelector("#statusList").value = 1;
    modal.show();
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        const arrFraming = [];
        const htmlFramingCheck = document.querySelectorAll(".frameCheck");
        let strName = document.querySelector("#txtName").value;
        let intStatus = document.querySelector("#statusList").value;
        let isVisible = document.querySelector("#isVisible").checked;
        if(strName == "" || intStatus ==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        for (let i = 0; i < htmlFramingCheck.length; i++) {
            const e = htmlFramingCheck[i];
            const obj = {
                id:e.getAttribute("data-id"),
                is_check: e.checked ? 1 : 0
            }
            arrFraming.push(obj);
        }
        let url = base_url+"/Marqueteria/Marqueteria/setProperty";
        let formData = new FormData(form);
        formData.append("is_visible",isVisible ? 1 : 0);
        formData.append("framing",JSON.stringify(arrFraming));
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        request(url,formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
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
    let formData = new FormData();
    formData.append("id",id);
    request(base_url+"/Marqueteria/Marqueteria/getProperty",formData,"post").then(function(objData){
        document.querySelector("#id").value = objData.data.id;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#orderList").value = objData.data.order_view;
        document.querySelector("#isVisible").checked = objData.data.is_material;
        document.querySelector(".modal-title").innerHTML = "Actualizar propiedad";
        if(objData.data.framing!=""){
            document.querySelector("#tableFraming").innerHTML =objData.data.framing; 
        }
        console.log(objData);
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
            let url = base_url+"/Marqueteria/Marqueteria/delProperty"
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
async function getFraming(){
    const response = await fetch(base_url+"/Marqueteria/Marqueteria/getFraming");
    document.querySelector("#tableFraming").innerHTML =await response.json(); 
}
