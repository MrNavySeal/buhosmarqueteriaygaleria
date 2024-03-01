'use strict';

let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Marqueteria/getCategories",
        "dataSrc":""
    },
    columns: [
        { 
            data: 'image',
            render: function (data, type, full, meta) {
                return '<img src="'+data+'" class="rounded" height="50" width="50">';
            }
        },
        { data: 'name'},

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
    order: [[1, 'asc']],
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
        document.querySelector(".uploadImg img").setAttribute("src",base_url+"/Assets/images/uploads/category.jpg");
        document.querySelector(".modal-title").innerHTML = "Nueva categoría";
        document.querySelector("#idCategory").value = "";
        document.querySelector("#txtName").value = "";
        document.querySelector("#txtDescription").value = "";
        document.querySelector("#txtBtn").value = "";
        document.querySelector("#statusList").value = 1;
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

        let strName = document.querySelector("#txtName").value;
        let strDescription = document.querySelector("#txtDescription").value;
        let intStatus = document.querySelector("#statusList").value;
        let idCategory = document.querySelector("#idCategory").value;

        if(strName == "" || strDescription =="" || intStatus ==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        let url = base_url+"/Marqueteria/setCategory";
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
    formData.append("idCategory",id);
    request(base_url+"/Marqueteria/getCategory",formData,"post").then(function(objData){
        document.querySelector("#idCategory").value = objData.data.id;
        document.querySelector(".uploadImg img").setAttribute("src",objData.data.image);
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#txtDescription").value = objData.data.description;
        document.querySelector("#txtBtn").value = objData.data.button;
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector(".modal-title").innerHTML = "Actualizar categoría";
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
            let url = base_url+"/marqueteria/delCategory"
            let formData = new FormData();
            formData.append("idCategory",id);
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
