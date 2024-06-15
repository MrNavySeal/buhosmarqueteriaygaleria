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
        "url": " "+base_url+"/MarqueteriaConfiguracion/getCategories",
        "dataSrc":""
    },
    columns: [
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

async function getData(){
    const response = await fetch(base_url+"/MarqueteriaConfiguracion/getData");
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
                        <input class="form-check-input" type="checkbox" role="switch" checked>
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
                        <input class="form-check-input" type="checkbox" role="switch" checked>
                    </div>
                </td>
            </tr>
        `
    });
    tableProps.innerHTML = html;

}
function editItem(id){
    modal.show();
}