'use strict';


let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let modalView = new bootstrap.Modal(document.querySelector("#modalViewElement"));
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/clientes/getCustomers",
        "dataSrc":""
    },
    columns: [
        { 
            data: 'image',
            render: function (data, type, full, meta) {
                return '<img src="'+data+'" class="rounded" height="50" width="50">';
            }
        },
        { data: 'fullname'},
        { data: 'identification' },
        { data: 'email' },
        { data: 'phone'},
        { data: 'date' },
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
        document.querySelector(".modal-title").innerHTML = "Nuevo cliente";
        document.querySelector("#txtFirstName").value = "";
        document.querySelector("#txtLastName").value = "";
        document.querySelector("#txtEmail").value ="";
        document.querySelector("#txtDocument").value = "";
        document.querySelector("#txtPhone").value = "";
        document.querySelector("#txtAddress").value = "";
        document.querySelector("#listCountry").value = 0;
        document.querySelector("#listState").value = "";
        document.querySelector("#listCity").value = "";
        document.querySelector("#statusList").value = 1;
        document.querySelector("#idUser").value ="";
        modal.show();
    });
}

if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    let intCountry = document.querySelector("#listCountry");
    let intState = document.querySelector("#listState");
    let intCity = document.querySelector("#listCity");

    request(base_url+"/clientes/getCountries","","get").then(function(objData){
        intCountry.innerHTML = objData;
    });

    intCountry.addEventListener("change",function(){
        request(base_url+"/clientes/getSelectCountry/"+intCountry.value,"","get").then(function(objData){
            intState.innerHTML = objData;
        });
        intCity.innerHTML = "";
    });
    intState.addEventListener("change",function(){
        request(base_url+"/clientes/getSelectState/"+intState.value,"","get").then(function(objData){
            intCity.innerHTML = objData;
        });
    });

    let img = document.querySelector("#txtImg");
    let imgLocation = ".uploadImg img";
    img.addEventListener("change",function(){
        uploadImg(img,imgLocation);
    });

    
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strFirstName = document.querySelector("#txtFirstName").value;
        let strLastName = document.querySelector("#txtLastName").value;
        let strEmail = document.querySelector("#txtEmail").value;
        let strPhone = document.querySelector("#txtPhone").value;
        let strPassword = document.querySelector("#txtPassword").value;
        let statusList = document.querySelector("#statusList").value;
        let idUser = document.querySelector("#idUser").value;
        let strDocument = document.querySelector("#txtDocument").value;
        if(strFirstName == "" || strLastName == "" || strPhone == "" || statusList == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(strPassword.length < 8 && strPassword!=""){
            Swal.fire("Error","La contraseña debe tener al menos 8 caracteres","error");
            return false;
        }
        if(!fntEmailValidate(strEmail) && strEmail!=""){
            Swal.fire("Error","El email es invalido","error");
            return false;
        }
        if(strPhone.length < 9){
            Swal.fire("Error","El número de teléfono debe tener al menos 9 dígitos","error");
            return false;
        }
        if(strDocument!=""){
            if(strDocument.length < 8 || strDocument.length > 10){
                Swal.fire("Error","El número de cédula debe tener de 8 a 10 dígitos","error");
                return false;
            }
        }
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");

        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(base_url+"/clientes/setCustomer",formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
                table.ajax.reload();
                modal.hide();
                form.reset();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
function viewItem(id){
    let formData = new FormData();
    formData.append("idUser",id);
    request(base_url+"/clientes/getCustomer",formData,"post").then(function(objData){
        if(objData.status){
            let status = objData.data.status;
            if(status==1){
                status='<span class="badge me-1 bg-success">Activo</span>';
            }else{
                status='<span class="badge me-1 bg-danger">Inactivo</span>';
            }
            document.querySelector("#strImg").setAttribute("src",objData.data.image);
            document.querySelector("#strName").innerHTML = objData.data.firstname;
            document.querySelector("#strLastname").innerHTML = objData.data.lastname;
            document.querySelector("#strEmail").innerHTML =objData.data.email;
            document.querySelector("#strCC").innerHTML = objData.data.identification;
            document.querySelector("#strPhone").innerHTML = objData.data.phone;
            document.querySelector("#strCountry").innerHTML = objData.data.country;
            document.querySelector("#strState").innerHTML = objData.data.state;
            document.querySelector("#strCity").innerHTML = objData.data.city;
            document.querySelector("#strDate").innerHTML = objData.data.date;
            document.querySelector("#strStatus").innerHTML = status;
            modalView.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function editItem(id){
    let formData = new FormData();
    formData.append("idUser",id);
    request(base_url+"/clientes/getCustomer",formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#idUser").value = objData.data.idperson;
            document.querySelector(".uploadImg img").setAttribute("src",objData.data.image);
            document.querySelector("#txtFirstName").value = objData.data.firstname;
            document.querySelector("#txtLastName").value = objData.data.lastname;
            document.querySelector("#txtEmail").value =objData.data.email;
            document.querySelector("#txtDocument").value = objData.data.identification;
            document.querySelector("#txtPhone").value = objData.data.phone;
            document.querySelector("#txtAddress").value = objData.data.address;
            document.querySelector("#listCountry").innerHTML = objData.countries;
            document.querySelector("#listState").innerHTML = objData.states;
            document.querySelector("#listCity").innerHTML = objData.cities;
            document.querySelector("#statusList").value = objData.data.status;
            document.querySelector(".modal-title").innerHTML = "Actualizar cliente";
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
            let url = base_url+"/clientes/delCustomer"
            let formData = new FormData();
            formData.append("idUser",id);
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