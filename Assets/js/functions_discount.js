'use strict';

let element = document.querySelector("#listItem");
let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector(".modal-title").innerHTML ="Nuevo descuento";
        modalView.show();
    });
}

window.addEventListener("DOMContentLoaded",function() {
    showItems(element);
    let categoryList = document.querySelector("#categoryList");
    let subcategoryList = document.querySelector("#subcategoryList");
    let typeList = document.querySelector("#typeList");

    categoryList.addEventListener("change",function(){
        let formData = new FormData();
        formData.append("idCategory",categoryList.value);
        request(base_url+"/descuentos/getSelectSubcategories",formData,"post").then(function(objData){
            document.querySelector("#subcategoryList").innerHTML = objData.data;
        });
    });
    typeList.addEventListener("change",function(){
        if(typeList.value == 2){
            document.querySelector(".subcategoryDisplay").classList.remove("d-none");
        }else{
            document.querySelector(".subcategoryDisplay").classList.add("d-none");
        }
    });
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();

        let intDiscount = document.querySelector("#intDiscount").value;
        let intStatus = document.querySelector("#statusList").value;
        if(intStatus == "" || intDiscount==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(intDiscount <=0){
            Swal.fire("Error","El campo de descuento no puede ser menor o igual a 0%","error");
            return false;
        }else if(intDiscount >100){
            Swal.fire("Error","El campo de descuento no puede ser superior al 100%","error");
            return false;
        }
        if(categoryList.value ==  0){
            Swal.fire("Error","Por favor, seleccione una categoría","error");
            return false;
        }
        if(typeList.value == 2 && subcategoryList.value == 0){
            Swal.fire("Error","Por favor, seleccione una subcategoría","error");
            return false;
        }
        let url = base_url+"/descuentos/setDiscount";
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");

        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(url,formData,"post").then(function(objData){
            btnAdd.innerHTML=`Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardar",objData.msg,"success");
                modalView.hide();
                form.reset();
                showItems(element);
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
})

element.addEventListener("click",function(e) {
    let element = e.target;
    let id = element.getAttribute("data-id");
    if(element.name == "btnDelete"){
        deleteItem(id);
    }else if(element.name == "btnEdit"){
        editItem(id);
    }else if(element.name == "btnView"){
        viewItem(id);
    }
});

function showItems(element){
    let url = base_url+"/descuentos/getDiscounts";
    request(url,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.msg;
        }
    })
}
function editItem(id){
    let url = base_url+"/descuentos/getDiscount";
    let formData = new FormData();
    formData.append("idDiscount",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#idDiscount").value = objData.data.id_discount;
            document.querySelector("#typeList").innerHTML = objData.data.htmlType;
            document.querySelector("#categoryList").innerHTML = objData.data.htmlc;
            document.querySelector("#statusList").innerHTML = objData.data.htmlStatus;
            document.querySelector("#intDiscount").value = objData.data.discount;
            document.querySelector(".modal-title").innerHTML ="Actualizar descuento";
            if(objData.data.type == 2){
                document.querySelector(".subcategoryDisplay").classList.remove("d-none");
                document.querySelector("#subcategoryList").innerHTML = objData.data.htmls;
            }else{
                document.querySelector(".subcategoryDisplay").classList.add("d-none");
            }
            modalView.show();
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
            let url = base_url+"/descuentos/delDiscount"
            let formData = new FormData();
            formData.append("idDiscount",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    showItems(element);
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}