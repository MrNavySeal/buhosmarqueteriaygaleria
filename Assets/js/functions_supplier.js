'use strict';


const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
const modalView = new bootstrap.Modal(document.querySelector("#modalViewElement"));
let arrContacts = [];
let tableData = "";
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Proveedores/getSuppliers",
        "dataSrc":""
    },
    columns: [
        
        { data: 'id_supplier'},
        { 
            data: 'img',
            render: function (data, type, full, meta) {
                return '<img src="'+data+'" class="rounded" height="50" width="50">';
            }
        },
        { data: 'category'},
        { data: 'nit' },
        { data: 'name' },
        { data: 'phone' },
        { data: 'email' },
        { data: 'address' },
        { data: 'datecreated' },
        { data: 'dateupdated' },
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
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector(".modal-title").innerHTML = "Nuevo proveedor";
        document.querySelector(".uploadImg img").setAttribute("src",base_url+"/Assets/images/uploads/category.jpg");
        document.querySelector("#id").value ="";
        document.querySelector("#txtName").value = "";
        document.querySelector("#txtNit").value = "";
        document.querySelector("#txtEmail").value = "";
        document.querySelector("#txtPhone").value = "";
        document.querySelector("#txtWeb").value = "";
        document.querySelector("#listCountry").value = 0;
        document.querySelector("#txtAddress").value = "";
        document.querySelector("#statusList").value =1;
        document.querySelector("#tableContacts").innerHTML ="";
        arrContacts = [];
        modal.show();
    });
}
if(document.querySelector("#formItem")){
    tableData = document.querySelector("#tableContacts")
    const intCountry = document.querySelector("#listCountry");
    const intState = document.querySelector("#listState");
    const intCity = document.querySelector("#listCity");
    const img = document.querySelector("#txtImg");
    const imgLocation = ".uploadImg img";
    img.addEventListener("change",function(){
        uploadImg(img,imgLocation);
    });

    request(base_url+"/proveedores/getCountries","","get").then(function(objData){
        intCountry.innerHTML = objData;
    });

    intCountry.addEventListener("change",function(){
        request(base_url+"/proveedores/getSelectCountry/"+intCountry.value,"","get").then(function(objData){
            intState.innerHTML = objData;
        });
        intCity.innerHTML = "";
    });
    intState.addEventListener("change",function(){
        request(base_url+"/proveedores/getSelectState/"+intState.value,"","get").then(function(objData){
            intCity.innerHTML = objData;
        });
    });

    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        const strName = document.querySelector("#txtName").value;
        const strNit = document.querySelector("#txtNit").value;
        const categoryList = document.querySelector("#categoryList").value;
        const strPhone = document.querySelector("#txtPhone").value;
        const strEmail = document.querySelector("#txtEmail").value;
        if(strName == "" || categoryList <= 0 || strPhone == "" || intCity.value <=0 
        || intState.value <=0 || intCountry.value <=0   ){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }

        if(!fntEmailValidate(strEmail) && strEmail!=""){
            Swal.fire("Error","El email es invalido","error");
            return false;
        }
        if(strPhone.length < 10){
            Swal.fire("Error","El número de teléfono debe tener al menos 10 dígitos","error");
            return false;
        }
        if(strNit!=""){
            if(strNit.length < 8 || strNit.length > 10){
                Swal.fire("Error","El NIT debe tener de 8 a 10 dígitos","error");
                return false;
            }
        }
        let formData = new FormData(form);
        formData.append("contacts",JSON.stringify(arrContacts));
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        request(base_url+"/proveedores/setSupplier",formData,"post").then(function(objData){
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

function addContact(){
    const name = document.querySelector("#txtContact").value;
    const phone = document.querySelector("#txtPhoneContact").value;

    if(name=="" || phone==""){
        Swal.fire("Error","Para agregar datos de contacto adicionales, ambos campos deben estar llenos.","error");
        return false;
    }
    const html = `
    <td>${name}</td>
    <td>${phone}<td>
    <td><button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteContact(this)"><i class="fas fa-trash-alt"></i></button></td>`;
    let tr = document.createElement("tr");
    tr.setAttribute("data-id",arrContacts.length);
    tr.innerHTML = html;
    tableData.appendChild(tr);
    arrContacts.push({name:name,phone:phone});
    //document.querySelector("#txtContact").value ="";
    //document.querySelector("#txtPhoneContact").value ="";
}
function deleteContact(item){
    const element = item.parentElement.parentElement;
    const id = element.getAttribute("data-id");
    arrContacts.splice(id,1);
    element.remove();
}
function viewItem(id){
    let formData = new FormData();
    formData.append("id",id);
    request(base_url+"/Proveedores/getSupplier",formData,"post").then(function(objData){
        if(objData.status){
            let html = "";
            const contacts = objData.data.contacts;
            let status = objData.data.status;
            for (let i = 0; i < contacts.length; i++) {
                let btnWp = "";
                let btnCall = `
                <button onclick="window.open('tel:${contacts[i]['phone']}')" class="text-white btn btn-info btn-sm text-white m-1" type="button">
                    <i class="fa fa-phone"></i>
                </button> `;
                if(contacts[i]['phone'].length > 9){
                    btnWp =`
                    <a href="https://wa.me/57${contacts[i]['phone']}" class="btn btn-success btn-sm text-white m-1" type="button" title="Whatsapp" target="_blank">
                    <i class="fab fa-whatsapp"></i></a>'`
                }
                html+=`
                    <tr>
                        <td>${contacts[i]['name']}</td>
                        <td>${contacts[i]['phone']}</td>
                        <td>
                            ${btnCall + btnWp}
                        </td>
                    </tr>
                `
            }
            

            if(objData.data.email !=""){
                document.querySelector("#strEmail").innerHTML = `
                <button onclick="window.open('mailto:${objData.data.email}')" class="text-white btn btn-info btn-sm text-white m-1" type="button">
                    <i class="fa fa-envelope"></i>
                </button> ` + objData.data.email;
            }
            if(objData.data.website !=""){
                document.querySelector("#strWeb").innerHTML = `
                <a href="${objData.data.website}" class="btn btn-primary btn-sm text-white m-1" type="button" title="Sitio web" target="_blank"><i class="fa fa-globe"></i></a>'
                `+objData.data.website;
            }
            if(status==1){
                status='<span class="badge me-1 bg-success">Activo</span>';
            }else{
                status='<span class="badge me-1 bg-danger">Inactivo</span>';
            }
            document.querySelector("#strImage").setAttribute("src",objData.data.img);
            document.querySelector("#strName").innerHTML = objData.data.name;
            


            let phoneData = `
            <button onclick="window.open('tel:${objData.data.phone}')" class="text-white btn btn-info btn-sm text-white m-1" type="button">
                <i class="fa fa-phone"></i>
            </button> `;
            phoneData+= objData.data.phone.length > 9 ? `<a href="https://wa.me/57${objData.data.phone}" class="btn btn-success btn-sm text-white m-1" type="button" title="Whatsapp" target="_blank">
            <i class="fab fa-whatsapp"></i>
            </a>` : "";
            document.querySelector("#strPhone").innerHTML = phoneData+objData.data.phone;
            document.querySelector("#strAddress").innerHTML = objData.data.compact_address;

            

            document.querySelector("#strCreated").innerHTML = objData.data.datecreated;
            document.querySelector("#strUpdated").innerHTML = objData.data.datecreated;
            document.querySelector("#strStatus").innerHTML = status;
            document.querySelector("#strCategory").innerHTML = objData.data.category;
            document.querySelector("#strNit").innerHTML = objData.data.nit;
            document.querySelector("#dataContacts").innerHTML = html;
            modalView.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function editItem(id){
    let formData = new FormData();
    formData.append("id",id);
    request(base_url+"/Proveedores/getSupplier",formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector(".modal-title").innerHTML = "Actualizar proveedor";
            document.querySelector(".uploadImg img").setAttribute("src",objData.data.img);
            document.querySelector("#id").value =objData.data.id_supplier;
            document.querySelector("#txtName").value = objData.data.name;
            document.querySelector("#txtNit").value = objData.data.nit;
            document.querySelector("#txtEmail").value = objData.data.email;
            document.querySelector("#txtPhone").value = objData.data.phone;
            document.querySelector("#txtWeb").value = objData.data.website;
            document.querySelector("#txtAddress").value = objData.data.address;
            document.querySelector("#statusList").value =objData.data.status;
            document.querySelector("#categoryList").value = objData.data.categories_id;
            document.querySelector("#listCountry").innerHTML = objData.countries;
            document.querySelector("#listState").innerHTML =objData.states;
            document.querySelector("#listCity").innerHTML =objData.cities;

            let html ="";
            arrContacts = objData.data.contacts;
            for (let i = 0; i < arrContacts.length; i++) {
                html+=`
                    <tr data-id="${i}">
                        <td>${arrContacts[i]['name']}</td>
                        <td>${arrContacts[i]['phone']}</td>
                        <td><button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteContact(this)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                `
            }
            document.querySelector("#tableContacts").innerHTML =html;
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
            let url = base_url+"/Proveedores/delSupplier"
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
