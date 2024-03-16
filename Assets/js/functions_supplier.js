'use strict';


const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
const tableData = document.querySelector("#tableData");
const arrContacts = [];
/*let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Proveedores/getSuppliers",
        "dataSrc":""
    },
    columns: [
        { data: 'id_categories'},
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
});*/
if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector(".modal-title").innerHTML = "Nuevo proveedor";
        document.querySelector("#id").value ="";
        modal.show();
    });
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strName = document.querySelector("#txtName").value;
        if(strName == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        request(base_url+"/proveedores/setCategory",formData,"post").then(function(objData){
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
function addContact(){
    const name = document.querySelector("#txtContact").value;
    const phone = document.querySelector("#txtPhoneContact").value;

    if(name=="" || phone==""){
        Swal.fire("Error","Para agregar datos de contacto adicionales, ambos campos deben estar llenos.","error");
        return false;
    }
    const html = `
    <td><input type="text" class="form-control" value="${name}"></td>
    <td><input type="number" class="form-control" value="${phone}"><td>
    <td><button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteContact(this)"><i class="fas fa-trash-alt"></i></button></td>`;
    let tr = document.createElement("tr");
    tr.classList.add("item-contact");
    tr.setAttribute("data-id",arrContacts.length);
    tr.innerHTML = html;
    tableData.appendChild(tr);
    arrContacts.push({name:name,phone:phone});
    //document.querySelector("#txtContact").value ="";
    //document.querySelector("#txtPhoneContact").value ="";
}
function deleteContact(item){
    const element = item.parentElement.parentElement;
    const id = element.getAttribute("data-id");
    arrContacts.splice(id,1);
    element.remove();
}
function editItem(id){
    let url = base_url+"/proveedores/getCategory";
    let formData = new FormData();
    formData.append("id",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#txtName").value = objData.data.name;
            document.querySelector("#statusList").value = objData.data.status;
            document.querySelector("#id").value = objData.data.id_categories;
            document.querySelector(".modal-title").innerHTML = "Actualizar categoría";
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
            let url = base_url+"/proveedores/delCategory"
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
