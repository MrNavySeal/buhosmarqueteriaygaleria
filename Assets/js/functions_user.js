'use strict';


let element = document.querySelector("#listItem");
let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let modalView = new bootstrap.Modal(document.querySelector("#modalViewElement"));
let table = new DataTable("#tableData",{
    "dom": 'lBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/usuarios/getUsers",
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
        { data: 'email' },
        { data: 'phone'},
        { data: 'identification' },
        { data: 'role' },
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
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
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
        let typeValue = document.querySelector("#typeList").value;
        let strPassword = document.querySelector("#txtPassword").value;
        let statusList = document.querySelector("#statusList").value;
        let idUser = document.querySelector("#idUser").value;
    
        if(strFirstName == "" || strLastName == "" || strEmail == "" || strPhone == "" || typeValue == "" || statusList == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(strPassword.length < 8 && strPassword!=""){
            Swal.fire("Error","La contraseña debe tener al menos 8 caracteres","error");
            return false;
        }
        if(!fntEmailValidate(strEmail)){
            Swal.fire("Error","El email es invalido","error");
            return false;
        }
        if(strPhone.length < 9){
            Swal.fire("Error","El número de teléfono debe tener al menos 9 dígitos","error");
            return false;
        }
        
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            
        
        btnAdd.setAttribute("disabled","");
        request(base_url+"/Usuarios/setUser",formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-plus-circle"></i> Agregar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
                table.ajax.reload();
                modal.hide();
                form.reset();
                document.querySelector("#idUser").value ="";
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector(".modal-title").innerHTML = "Nuevo usuario";
        document.querySelector("#idUser").value = "";
        document.querySelector("#txtFirstName").value = "";
        document.querySelector("#txtLastName").value = "";
        document.querySelector("#txtEmail").value = "";
        document.querySelector("#txtPhone").value = "";
        modal.show();
    });
}

function viewItem(id){
    let url = base_url+"/Usuarios/getUser";
    let formData = new FormData();
    formData.append("idUser",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            let status = objData.data.status;
            if(status==1){
                status='<span class="badge me-1 bg-success">Activo</span>';
            }else{
                status='<span class="badge me-1 bg-danger">Inactivo</span>';
            }
            document.querySelector("#strImage").setAttribute("src",objData.data.image);
            document.querySelector("#strName").innerHTML = objData.data.firstname;
            document.querySelector("#strSurname").innerHTML = objData.data.lastname;
            document.querySelector("#strEmail").innerHTML = objData.data.email;
            document.querySelector("#strPhone").innerHTML = objData.data.phone;
            document.querySelector("#strCity").innerHTML = objData.data.city;
            document.querySelector("#strCountry").innerHTML = objData.data.country;
            document.querySelector("#strState").innerHTML = objData.data.state;
            document.querySelector("#strDate").innerHTML = objData.data.date;
            document.querySelector("#strRole").innerHTML = objData.data.role;
            document.querySelector("#strStatus").innerHTML = status;
            modalView.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function editItem(id){
    let url = base_url+"/Usuarios/getUser";
    let formData = new FormData();
    formData.append("idUser",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#idUser").value = objData.data.idperson
            document.querySelector("#txtImg").setAttribute("src",objData.data.image);
            document.querySelector("#txtFirstName").value = objData.data.firstname;
            document.querySelector("#txtLastName").value = objData.data.lastname;
            document.querySelector("#txtEmail").value = objData.data.email;
            document.querySelector("#txtPhone").value = objData.data.phone;
            /*let roles = document.querySelectorAll("#typeList option");
            for (let i = 0; i < roles.length; i++) {
                if(roles[i].value == objData.data.roleid){
                    roles[i].setAttribute("selected",true);
                }
            }*/
            document.querySelector("#typeList").value = objData.data.roleid;
            document.querySelector("#statusList").value = objData.data.status;
            document.querySelector(".modal-title").innerHTML = "Actualizar usuario";
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
            let url = base_url+"/Usuarios/delUser"
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