const table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/MarqueteriaEjemplos/getExamples",
        "dataSrc":""
    },
    columns: [
        { data: 'id'},
        { 
            data: 'img',
            render: function (data, type, full, meta) {
                return '<img src="'+data+'" class="rounded" height="50" width="50">';
            }
        },
        { data: 'name'},
        { data: 'total'},
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
    const btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        /*document.querySelector(".modal-title").innerHTML = "Nueva opción de propiedad";
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
        modal.show();*/
    });
}
function editItem(id){

}
function deleteItem(id){

}
function viewItem(id){
    
}