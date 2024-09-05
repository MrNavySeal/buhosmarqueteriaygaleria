window.addEventListener("load",function(e){
    let table = new DataTable("#tableData",{
        "dom": 'lfBrtip',
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Inventario/getProducts",
            "dataSrc":''
        },
        initComplete: function (settings, json) {
            const text = document.querySelector(".dataTables_info").innerHTML;
            let total = 0;
            json.forEach(e => {
                total+= e.total;
            });
            document.querySelector(".dataTables_info").innerHTML = `
                <div class="d-flex flex-column-reverse px-4">
                    <span >${text}</span>
                    <span class="text-end"><strong>Costo total: </strong>$${formatNum(total,".")}</span>
                </div>
            `;
        },
        columns: [
            
            { data: 'idproduct'},
            { data: 'reference' },
            { data: 'name' },
            { data: 'category' },
            { data: 'subcategory' },
            { data: 'stock' },
            { data: 'price_purchase_format' },
            { data: 'total_format' }
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
    
})