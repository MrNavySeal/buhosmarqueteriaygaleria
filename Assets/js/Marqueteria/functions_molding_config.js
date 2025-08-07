'use strict';
const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
const tableProps = document.querySelector("#tableProps");
const tableFraming = document.querySelector("#tableFraming");
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Marqueteria/MarqueteriaConfiguracion/getCategories",
        "dataSrc":""
    },
    columns: [
        { data: 'id'},
        { data: 'name'},
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
window.addEventListener("DOMContentLoaded",function(){
    let img = document.querySelector("#txtImg");
    let imgLocation = ".uploadImg img";
    img.addEventListener("change",function(){
        uploadImg(img,imgLocation);
    });
    getData();
});
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        const arrProps = [];
        const htmlPropsCheck = document.querySelectorAll(".propsCheck");
        const isFrame = document.querySelector("#isFrame").checked;
        const isPrint = document.querySelector("#isPrint").checked;
        const isCost = document.querySelector("#isCost").checked;

        for (let i = 0; i < htmlPropsCheck.length; i++) {
            const e = htmlPropsCheck[i];
            const obj = {
                topic:e.getAttribute("data-topic"),
                prop:e.getAttribute("data-id"),
                is_check: e.checked ? 1 : 0
            }
            arrProps.push(obj);
        }
        
        let url = base_url+"/Marqueteria/MarqueteriaConfiguracion/setConfig";
        let formData = new FormData(form);
        formData.append("props",JSON.stringify(arrProps));
        formData.append("is_cost", isCost ? 1 : 0);
        formData.append("is_print", isPrint ? 1 : 0);
        formData.append("is_frame", isFrame ? 1 : 0);
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
    const response = await fetch(base_url+"/Marqueteria/MarqueteriaConfiguracion/getData");
    const objData = await response.json();
    const arrFraming = objData.framing;
    const arrProps = objData.properties;
    let html ="";
    arrFraming.forEach(e => {
        html+= `
            <tr>
                <td>${e.name}</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input propsCheck" type="checkbox" role="switch" data-id="${e.id}" data-topic="1" checked>
                    </div>
                </td>
            </tr>
        `
    });
    tableFraming.innerHTML = html;
    html="";
    arrProps.forEach(e => {
        html+= `
            <tr>
                <td>${e.name}</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input propsCheck" type="checkbox" role="switch" data-id="${e.id}" data-topic="2" checked>
                    </div>
                </td>
            </tr>
        `
    });
    tableProps.innerHTML = html;

}
async function editItem(id){
    document.querySelector("#id").value = id;
    const formData = new FormData();
    formData.append("id",id);
    const response = await fetch(base_url+"/Marqueteria/MarqueteriaConfiguracion/getConfig",{method:"POST",body:formData});
    const objData = await response.json();
    if(objData.status){
        const data = objData.data;
        const props = data.props;

        document.querySelector("#id").value = data.category_id;
        document.querySelector("#isFrame").checked = data.is_frame;
        document.querySelector("#isPrint").checked = data.is_print;
        document.querySelector("#isCost").checked = data.is_cost;
        document.querySelector(".uploadImg img").setAttribute("src",data.url);

        const htmlPropsCheck = document.querySelectorAll(".propsCheck");
        for (let i = 0; i < htmlPropsCheck.length; i++) {
            const html = htmlPropsCheck[i];
            const topic = html.getAttribute("data-topic");
            const idProp = html.getAttribute("data-id")
            for (let j = 0; j < props.length; j++) {
                const e = props[j];
                if(topic == e.topic && idProp == e.prop){
                    html.checked = e.is_check;
                    break;
                }
            }
        }
    }
    modal.show();
}