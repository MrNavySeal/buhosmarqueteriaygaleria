const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
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
        { data: 'category'},
        { data: 'name'},
        { data: 'total'},
        { data: 'date'},
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
        modal.show();
    });
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    let img = document.querySelector("#txtImg");
    let imgLocation = ".uploadImg img";
    img.addEventListener("change",function(){
        uploadImg(img,imgLocation);
    });
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let url = base_url+"/MarqueteriaEjemplos/setExample";
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
function editItem(id){
    let formData = new FormData();
    formData.append("id",id);
    request(base_url+"/MarqueteriaEjemplos/getExample",formData,"post").then(function(objData){
        const data = objData.data;
        const specs = data.specs;
        let html="";
        specs.detail.forEach(e => {
            html+=`
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="" class="form-label fw-bold">${e.name}</label>
                    <p class="text-break" id="strDate">${e.value}</p>
                </div>
            </div>
            `
        });
        document.querySelector("#id").value = data.id;
        document.querySelector(".uploadImg img").setAttribute("src",data.img);
        document.querySelector("#strName").innerHTML = data.name;
        document.querySelector("#strDate").innerHTML = data.date;
        document.querySelector("#strType").innerHTML = specs.name;
        document.querySelector("#statusList").value = data.status;
        document.querySelector("#frameDescription").innerHTML = html;
        document.querySelector(".modal-title").innerHTML = "Actualizar ejemplo";
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
            let url = base_url+"/MarqueteriaEjemplos/delExample"
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