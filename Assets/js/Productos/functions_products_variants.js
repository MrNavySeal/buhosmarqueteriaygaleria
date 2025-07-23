'use strict';


let arrOptions = [];
const tableData = document.querySelector("#tableVariants");
const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
const table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/ProductosOpciones/getVariants",
        "dataSrc":""
    },
    columns: [
        { data: 'id_variation'},
        { data: 'name' },
        { data: 'qty' },
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
if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector(".modal-title").innerHTML = "Nueva variante";
        document.querySelector("#txtName").value = "";
        document.querySelector("#statusList").value = 1;
        document.querySelector("#id").value ="";
        modal.show();
        arrOptions = [];
        tableData.innerHTML ="";
    });
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strName = document.querySelector("#txtName").value;
        let arrOptions = getVariants();
        if(strName == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(arrOptions.length == 0){
            Swal.fire("Error","Debe crear al menos una opción","error");
            return false;
        }
        let formData = new FormData(form);
        formData.append("options",JSON.stringify(arrOptions));
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        request(base_url+"/ProductosOpciones/setVariant",formData,"post").then(function(objData){
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
function addVariant(){
    const name = document.querySelector("#txtNameVariant");
    if(name.value==""){
        Swal.fire("Error","Para agregar una opción, debe escribir el nombre.","error");
        return false;
    }
    const html = `
    <div class="d-flex align-items-center">
        <input type="text" class="form-control" value="${name.value}">
        <button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteVariant(this)"><i class="fas fa-trash-alt"></i></button>
    </div>
    `;
    let el = document.createElement("div");
    el.classList.add("data-item","w-100");
    el.innerHTML = html;
    tableData.appendChild(el);
    name.value ="";
}
function getVariants(){
    let variants = document.querySelectorAll(".data-item");
    arrOptions = [];
    variants.forEach(el=>{
        let val = el.children[0].children[0].value;
        if(val !=""){
            arrOptions.push(val);
        }
    });
    arrOptions = [... new Set(arrOptions)];
    console.log(arrOptions);
    return arrOptions;
}
function deleteVariant(item){
    item.parentElement.parentElement.remove();
}
function editItem(id){
    let url = base_url+"/ProductosOpciones/getVariant";
    let formData = new FormData();
    formData.append("id",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            const options = objData.data.options;
            let html="";
            document.querySelector("#txtName").value = objData.data.name;
            document.querySelector("#statusList").value = objData.data.status;
            document.querySelector("#id").value = objData.data.id_variation;
            document.querySelector(".modal-title").innerHTML = "Actualizar variante";
            options.forEach(el=>{
                arrOptions.push(el.name);
                html+=`
                <div class="data-item w-100">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control" value="${el.name}">
                        <button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteVariant(this)"><i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                    </div>
                </div>
                `;
            });
            tableData.innerHTML = html;
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
            let url = base_url+"/ProductosOpciones/delVariant"
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
