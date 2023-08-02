let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));
let formItem = document.querySelector("#formItem");
let element = document.querySelector("#listItem");

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector("#idIncome").value ="";
        document.querySelector(".modal-title").innerHTML ="Nuevo ingreso";
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
        let amount = document.querySelector("#txtAmount").value;

        if(amount == ""){
            Swal("Error","Todos los campos con (*) son obligatorios","error");
            return false;
        }
        if(amount <= 0){
            amount.value = "";
            Swal("Error","El monto no puede ser menor o igual a 0","error");
            return false;
        }
        let formData = new FormData(formItem);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(base_url+"/contabilidad/setIncome",formData,"post").then(function(objData){
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
    let url = base_url+"/contabilidad/getIncome";
    let formData = new FormData();
    formData.append("idIncome",id);
    request(url,formData,"post").then(function(objData){
        document.querySelector(".modal-title").innerHTML ="Actualizar ingreso";
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#typeList").value = objData.data.category_id;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#idIncome").value = objData.data.id;
        document.querySelector("#txtAmount").value = objData.data.amount;
        let arrDate = new String(objData.data.date).split("/");
        document.querySelector("#txtDate").valueAsDate = new Date(arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0]);
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
            let url = base_url+"/contabilidad/delIncome"
            let formData = new FormData();
            formData.append("idIncome",id);
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