'use strict';
const selectMaterial = document.querySelector("#selectMaterial");
const arrSelectedMaterial = [];
let arrMaterials = [];
const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
const modalMaterial = document.querySelector("#modalMaterial") ? new bootstrap.Modal(document.querySelector("#modalMaterial")) :"";
const tableMaterial = document.querySelector("#tableMaterial");
const table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/MarqueteriaOpciones/getOptions",
        "dataSrc":""
    },
    columns: [
        { data: 'id'},
        { data: 'name'},
        { data: 'property'},
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
if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector(".modal-title").innerHTML = "Nueva opción de propiedad";
        document.querySelector("#id").value = "";
        document.querySelector("#txtName").value = "";
        document.querySelector("#statusList").value = 1;
        modal.show();
    });
    getData();
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();

        let strName = document.querySelector("#txtName").value;
        let intStatus = document.querySelector("#statusList").value;
        let intProp = document.querySelector("#propList").value;

        if(strName == "" || intStatus =="" || intProp ==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        let url = base_url+"/MarqueteriaOpciones/setOption";
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
                form.reset();
                modal.hide();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
async function getData(){
    const response = await fetch(base_url+"/MarqueteriaOpciones/getData");
    const objData = await response.json();
    const arrProperties = objData.properties;
    arrMaterials = objData.materials;
    const selectProperties = document.querySelector("#propList");
    for (let i = 0; i < arrProperties.length; i++) {
        const e = arrProperties[i];
        const option = document.createElement("option");
        option.setAttribute("value",e.id);
        option.innerHTML = e.name;
        selectProperties.appendChild(option);
    }
    for (let i = 0; i < arrMaterials.length; i++) {
        const e = arrMaterials[i];
        const option = document.createElement("option");
        option.setAttribute("value",e.idproduct);
        option.innerHTML = e.name;
        selectMaterial.appendChild(option);
    }
}  
function showMaterial(){
    modalMaterial.show();
} 
function addMaterial(){
    const idMaterial = selectMaterial.value;
    const material = arrMaterials.filter(e=>e.idproduct == idMaterial)[0];
    
    if(arrSelectedMaterial.length > 0){
        const flag = arrSelectedMaterial.filter(e=>e.idproduct == idMaterial).length > 0 ? true : false;
        if(flag){
            Swal.fire("Error","El material ya fue agregado","error");
            return false;
        }else{
            arrSelectedMaterial.push(material);
        }
    }else{
        arrSelectedMaterial.push(material);
    }
    const html = `
        <td>${material.name}</td>
        <td><button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteMaterial(this,'${idMaterial}')"><i class="fas fa-trash-alt"></i></button></td>
    `;
    let el = document.createElement("tr");
    el.classList.add("data-item","w-100");
    el.innerHTML = html;
    tableMaterial.appendChild(el);
}
function deleteMaterial(item,id){
    item.parentElement.parentElement.remove();
    const index = arrSelectedMaterial.findIndex(e=>e.idproduct == id);
    arrSelectedMaterial.splice(index,1);
}
function editItem(id){
    let formData = new FormData();
    formData.append("id",id);
    request(base_url+"/MarqueteriaOpciones/getOption",formData,"post").then(function(objData){
        document.querySelector("#id").value = objData.data.id;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#propList").value = objData.data.prop_id;
        document.querySelector(".modal-title").innerHTML = "Actualizar opción de propiedad";
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
            let url = base_url+"/MarqueteriaOpciones/delOption"
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
