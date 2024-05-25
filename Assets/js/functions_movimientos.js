const tableResume = document.querySelector("#tableResume");
const total = document.querySelector("#total");

let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/contabilidad/getMovements",
        "dataSrc":""
    },
    columns: [
        { data: 'id'},
        { data: 'type' },
        { data: 'name'},
        { data: 'detail' },
        { data: 'date' },
        { data: 'method' },
        { data: 'amount' },
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
    scrollY:'300px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});



window.addEventListener("load",function(){
    getData();
});
async function getData(){
    const response = await fetch(base_url+"/contabilidad/getMovementsResume");
    const objData = await response.json();
    if(objData.detail){
        const detail = objData.detail;
        for (let i = 0; i < detail.length; i++) {
            const element = detail[i];
            const tr = document.createElement("tr");
            tr.innerHTML = `<td>${element['method']}</td><td>${element['total']}</td>`;
            tableResume.appendChild(tr);
        }
        total.innerHTML = objData.total;
    }
}
