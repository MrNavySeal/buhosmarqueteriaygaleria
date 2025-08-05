'use strict';


let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Marqueteria/Marqueteria/getColors",
        "dataSrc":""
    },
    columns: [
        { data: 'id'},
        { data: 'color',
            
            render: function (data, type, full, meta) {
                return '<div style="height: 50px;width: 50px; border:1px solid #000;background-color:#'+data+'"></div>';
            }
        },
        { data: 'name'},
        { data: 'color' },
        { data: 'order_view' },
        { data: 'is_visible' },
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
    document.querySelector(".modal-title").innerHTML = "Nuevo color";
    document.querySelector("#txtName").value = "";
    document.querySelector("#txtColor").value = "";
    document.querySelector("#statusList").value =1;
    document.querySelector("#idColor").value ="";
    document.querySelector("#orderList").value = 5;
    document.querySelector("#isVisible").checked =0;
    modal.show();
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strName = document.querySelector("#txtName").value;
        let strColor = document.querySelector("#txtColor").value;
        let intStatus = document.querySelector("#statusList").value;
        let isVisible = document.querySelector("#isVisible").checked;
        if(strName == "" || strColor == "" || intStatus==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        let url = base_url+"/Marqueteria/Marqueteria/setColor";
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");
        formData.append("is_visible",isVisible ? 1 : 0);

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
    formData.append("idColor",id);
    request(base_url+"/Marqueteria/Marqueteria/getColor",formData,"post").then(function(objData){
        document.querySelector("#idColor").value = objData.data.id;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#txtColor").value = objData.data.color;
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#orderList").value = objData.data.order_view;
        document.querySelector("#isVisible").checked = objData.data.is_visible;
        document.querySelector(".modal-title").innerHTML = "Actualizar color";
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
            let url = base_url+"/Marqueteria/Marqueteria/delColor"
            let formData = new FormData();
            formData.append("idColor",id);
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
