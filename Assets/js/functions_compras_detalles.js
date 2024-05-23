let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Compras/getDetailPurchases",
        "dataSrc":""
    },
    columns: [
        
        { data: 'purchase_id'},
        { data: 'cod_bill' },
        { data: 'date' },
        { data: 'document' },
        { data: 'supplier' },
        { data: 'qty' },
        { data: 'price_purchase' },
        { data: 'price_discount' },
        { data: 'measure' },
        { data: 'subtotal' }
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
