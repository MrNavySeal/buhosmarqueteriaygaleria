'use strict';
const selectMaterial = document.querySelector("#selectMaterial");
const selectDisableProps = document.querySelector("#selectDisableProp");
let arrSelectedMaterial = [];
let arrSelectedProps = [];
let arrMaterials = [];
let arrProperties = [];
let arrOptions = [];
const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
const modalMaterial = document.querySelector("#modalMaterial") ? new bootstrap.Modal(document.querySelector("#modalMaterial")) :"";
const tableMaterial = document.querySelector("#tableMaterial");
const tableProps = document.querySelector("#tableProps");
const table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Marqueteria/MarqueteriaOpciones/getOptions",
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
    order: [[0, 'desc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});

function openModal(){
    const divMargin = document.querySelector("#divMargin");
    const checkMargin = document.querySelector("#isMargin");
    checkMargin.addEventListener("change",function(){
        if(checkMargin.checked){
            divMargin.classList.remove("d-none");
        }else{
            divMargin.classList.add("d-none");
        }
    });
    document.querySelector(".modal-title").innerHTML = "Nueva opción de propiedad";
    document.querySelector("#id").value = "";
    document.querySelector("#txtName").value = "";
    document.querySelector("#txtTag").value = "";
    document.querySelector("#txtTagFrame").value = "";
    document.querySelector("#statusList").value = 1;
    document.querySelector("#isMargin").checked = false;
    document.querySelector("#isColor").checked = false;
    document.querySelector("#isDblFrame").checked = false;
    document.querySelector("#isBocel").checked = false;
    document.querySelector("#isVisible").checked = false;
    document.querySelector("#txtMargin").value = 5;
    tableMaterial.innerHTML ="";
    tableProps.innerHTML ="";
    arrSelectedMaterial =[];
    arrSelectedProps =[];

    modal.show();
    getData();
}

async function save(element){
    let strName = document.querySelector("#txtName").value;
    let intStatus = document.querySelector("#statusList").value;
    let intProp = document.querySelector("#propList").value;
    let intMargin = document.querySelector("#txtMargin").value;
    let isMargin = document.querySelector("#isMargin").checked;
    let isColor = document.querySelector("#isColor").checked;
    let isDblFrame = document.querySelector("#isDblFrame").checked;
    let isBocel = document.querySelector("#isBocel").checked;
    let isVisible = document.querySelector("#isVisible").checked;
    let intOrderList = document.querySelector("#orderList").value;
    let strTag = document.querySelector("#txtTag").value;
    let strTagFrame = document.querySelector("#txtTagFrame").value;
    let intId = document.querySelector("#id").value;

    if(strName == "" || intStatus =="" || intProp ==""){
        Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
        return false;
    }

    const formData = new FormData();
    formData.append("id",intId);
    formData.append("is_margin",isMargin ? 1 : 0);
    formData.append("is_color",isColor ? 1 : 0);
    formData.append("is_frame",isDblFrame ? 1 : 0);
    formData.append("is_bocel",isBocel ? 1 : 0);
    formData.append("is_visible",isVisible ? 1 : 0);
    formData.append("margin",intMargin);
    formData.append("order",intOrderList);
    formData.append("txtTag",strTag);
    formData.append("txtTagFrame",strTagFrame);
    formData.append("material",JSON.stringify(arrSelectedMaterial));
    formData.append("props",JSON.stringify(arrSelectedProps));
    formData.append("statusList",intStatus);
    formData.append("txtName",strName);
    formData.append("propList",intProp);

    element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    element.setAttribute("disabled","");

    const response = await fetch(base_url+"/Marqueteria/MarqueteriaOpciones/setOption",{method:"POST",body:formData});
    const objData = await response.json();

    element.innerHTML=`<i class="fas fa-save"></i> Guardar`;
    element.removeAttribute("disabled");

    if(objData.status){
        Swal.fire("Guardado",objData.msg,"success");
        table.ajax.reload();
        modal.hide();
    }else{
        Swal.fire("Error",objData.msg,"error");
    }
}

async function getData(){
    const response = await fetch(base_url+"/Marqueteria/MarqueteriaOpciones/getData");
    const objData = await response.json();
    arrProperties = objData.properties;
    arrMaterials = objData.materials;
    const selectProperties = document.querySelector("#propList");
    const selectDisableProps = document.querySelector("#selectDisableProp");
    for (let i = 0; i < arrProperties.length; i++) {
        const e = arrProperties[i];
        const option = document.createElement("option");
        const optionProp = document.createElement("option");
        option.setAttribute("value",e.id);
        option.innerHTML = e.name;

        optionProp.setAttribute("value",e.id);
        optionProp.innerHTML = e.name;
        
        selectProperties.appendChild(option);
        selectDisableProps.appendChild(optionProp);
    }
    for (let i = 0; i < arrMaterials.length; i++) {
        const e = arrMaterials[i];
        const option = document.createElement("option");
        option.setAttribute("value",e.idproduct);
        option.innerHTML = e.name;
        selectMaterial.appendChild(option);
    }
}  

function showItems(type="",data=[]){
    let html ="";
    if(data.length > 0){
        if(type=="material"){
            arrSelectedMaterial = data;
            arrSelectedMaterial.forEach(e => {
                html+= `
                <tr class="data-item w-100">
                    <td>${e.name}</td>
                    <td>${e.type}</td>
                    <td>${e.method}</td>
                    <td>${e.factor}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="delItem(this,'${e.idproduct}','material')"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </td>
                </tr>`;
            });
            tableMaterial.innerHTML = html;
        }else{
            arrSelectedProps = data;
            arrSelectedProps.forEach(e => {
                html+= `
                <tr class="data-item w-100">
                    <td>${e.name}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="delItem(this,'${e.id}','props')"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </td>
                </tr>`;
            });
            tableProps.innerHTML = html;
        }
    }
} 

function addItem(type=""){
    if(type=="material"){
        const idMaterial = selectMaterial.value;
        const material = arrMaterials.filter(e=>e.idproduct == idMaterial)[0];
        material.type = document.querySelector("#selectCalc").value;
        material.method = document.querySelector("#selectType").value;
        material.factor = document.querySelector("#txtNumber").value;
        arrSelectedMaterial.push(material);
        const html = `
            <td>${material.name}</td>
            <td>${material.type}</td>
            <td>${material.method}</td>
            <td>${material.factor}</td>
            <td><button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="delItem(this,'${idMaterial}','material')"><i class="fas fa-trash-alt"></i></button></td>
        `;
        let el = document.createElement("tr");
        el.classList.add("data-item","w-100");
        el.innerHTML = html;
        tableMaterial.appendChild(el);
    }else{
        const id = selectDisableProps.value;
        if(arrSelectedProps.filter(e=>e.id == id).length > 0){
            Swal.fire("Atención!","esta propiedad ya fue agregada, intente con otra.","warning");
            return false;
        }
        const arrData = arrProperties.filter(e=>e.id == id)[0];
        arrSelectedProps.push(arrData);

        const html = `
            <td>${arrData.name}</td>
            <td>
                <div class="d-flex justify-content-center"><button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="delItem(this,'${id}','prop')"><i class="fas fa-trash-alt"></i></button></div>
            </td>
        `;
        let el = document.createElement("tr");
        el.classList.add("data-item","w-100");
        el.innerHTML = html;
        tableProps.appendChild(el);
    }
}

function delItem(item,id,type=""){
    if(type=="material"){
        item.parentElement.parentElement.remove();
        const index = arrSelectedMaterial.findIndex(e=>e.idproduct == id);
        arrSelectedMaterial.splice(index,1);
    }else{
        item.parentElement.parentElement.parentElement.remove();
        const index = arrSelectedProps.findIndex(e=>e.id == id);
        arrSelectedProps.splice(index,1);
    }
}

function editItem(id){
    let formData = new FormData();
    formData.append("id",id);
    request(base_url+"/Marqueteria/MarqueteriaOpciones/getOption",formData,"post").then(function(objData){
        const selectProperties = document.querySelector("#propList");
        const selectDisableProps = document.querySelector("#selectDisableProp");
        arrProperties = objData.properties;
        arrMaterials = objData.materials;
        for (let i = 0; i < arrProperties.length; i++) {
            const e = arrProperties[i];
            const option = document.createElement("option");
            const optionProp = document.createElement("option");
            option.setAttribute("value",e.id);
            option.innerHTML = e.name;

            optionProp.setAttribute("value",e.id);
            optionProp.innerHTML = e.name;
            
            selectProperties.appendChild(option);
            selectDisableProps.appendChild(optionProp);
        }

        for (let i = 0; i < arrMaterials.length; i++) {
            const e = arrMaterials[i];
            const option = document.createElement("option");
            option.setAttribute("value",e.idproduct);
            option.innerHTML = e.name;
            selectMaterial.appendChild(option);
        }

        showItems('material',objData.data.materials);
        showItems('props',objData.data.disabled_props);

        document.querySelector("#id").value = objData.data.id;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#txtTag").value = objData.data.tag;
        document.querySelector("#txtTagFrame").value = objData.data.tag_frame;
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#propList").value = objData.data.prop_id;
        document.querySelector("#isMargin").checked = objData.data.is_margin;
        document.querySelector("#isColor").checked = objData.data.is_color;
        document.querySelector("#isDblFrame").checked = objData.data.is_frame;
        document.querySelector("#isBocel").checked = objData.data.is_bocel;
        document.querySelector("#isVisible").checked = objData.data.is_visible;
        document.querySelector("#txtMargin").value = objData.data.margin;
        document.querySelector("#orderList").value = objData.data.order_view;
        document.querySelector(".modal-title").innerHTML = "Actualizar opción de propiedad";
        if(objData.data.is_margin){
            divMargin.classList.remove("d-none");
        }else{
            divMargin.classList.add("d-none");
        }
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
            let url = base_url+"/Marqueteria/MarqueteriaOpciones/delOption"
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
