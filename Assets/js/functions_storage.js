let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));
let formItem = document.querySelector("#formItem");
let element = document.querySelector("#listItem");
let searchPanel = document.querySelector("#search");

searchPanel.addEventListener('input',function() {
    request(base_url+"/compras/searchS/"+searchPanel.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
})
if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector("#id").value ="";
        document.querySelector(".modal-title").innerHTML ="Nuevo producto";
        formItem.reset();
        openModal();
    });
}
element.addEventListener("click",function(e) {
    let element = e.target;
    let id = element.getAttribute("data-id");
    if(element.name == "btnDelete"){
        deleteItem(id);
    }else if(element.name == "btnEdit"){
        editItem(id);
    }
});
window.addEventListener("load",function(){
    formItem.addEventListener("submit",function(e){
        e.preventDefault();
        let name = document.querySelector("#txtName").value;
        let cost = document.querySelector("#txtCost").value;

        if(cost == "" || name==""){
            Swal.fire("Error","Todos los campos con (*) son obligatorios","error");
            return false;
        }
        if(cost < 0){
            cost.value = "";
            Swal.fire("Error","El monto no puede ser menor a 0","error");
            return false;
        }
        let formData = new FormData(formItem);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(base_url+"/compras/setProduct",formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
                element.innerHTML = objData.data;
                modalView.hide();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    });
});
function editItem(id){
    let url = base_url+"/compras/getProduct";
    let formData = new FormData();
    formData.append("id",id);
    request(url,formData,"post").then(function(objData){
        document.querySelector(".modal-title").innerHTML ="Actualizar producto";
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#suppList").value = objData.data.supplier_id;
        document.querySelector("#typeList").value = objData.data.import;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#id").value = objData.data.id_storage;
        document.querySelector("#txtReference").value = objData.data.reference;
        document.querySelector("#txtCost").value = objData.data.cost;
        modalView.show();
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
            let url = base_url+"/compras/delProduct"
            let formData = new FormData();
            formData.append("id",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    element.innerHTML = objData.data;
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}
function openModal(){
    modalView.show();
}