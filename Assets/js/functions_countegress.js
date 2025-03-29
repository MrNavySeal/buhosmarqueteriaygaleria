let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let formItem = document.querySelector("#formItem");
let tableData = document.querySelector("#tableData");
let arrData = [];
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");


if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        document.querySelector("#id").value ="";
        document.querySelector(".modal-title").innerHTML ="Nuevo egreso";
        formItem.reset();
        modal.show();
    });
}
window.addEventListener("load",function(){
    let typeList = document.querySelector("#typeList");
    initialDateHtml.value = new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0];
    finallDateHtml.value = new Date().toISOString().split("T")[0]; 
    typeList.addEventListener("change",function(){
        request(base_url+"/contabilidad/getSelectCategories/"+typeList.value,"","get").then(function(objData){
            document.querySelector("#categoryList").innerHTML=objData.data;
        });
    });
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

        request(base_url+"/contabilidad/setEgress",formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
                getData();
                modal.hide();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    });
    getData();
});
searchHtml.addEventListener("input",function(){getData();});
perPage.addEventListener("change",function(){getData();});
initialDateHtml.addEventListener("input",function(){getData();});
finallDateHtml.addEventListener("input",function(){getData();});
async function getData(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    formData.append("initial_date",initialDateHtml.value);
    formData.append("final_date",finallDateHtml.value);
    const response = await fetch(base_url+"/contabilidad/getOutgoings",{method:"POST",body:formData});
    const objData = await response.json();
    tableData.innerHTML =objData.html;
    arrData = objData.full_data;
    document.querySelector("#pagination").innerHTML = objData.html_pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}
function editItem(id){
    let url = base_url+"/contabilidad/getEgress";
    let formData = new FormData();
    formData.append("id",id);
    request(url,formData,"post").then(function(objData){
        document.querySelector(".modal-title").innerHTML ="Actualizar egreso";
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#categoryList").innerHTML = objData.data.options.data;
        document.querySelector("#categoryList").value = objData.data.category_id;
        document.querySelector("#typeList").value = objData.data.type_id;
        document.querySelector("#txtName").value = objData.data.name;
        document.querySelector("#id").value = objData.data.id;
        document.querySelector("#txtAmount").value = objData.data.amount;
        document.querySelector("#subType").value = objData.data.method;
        let arrDate = new String(objData.data.date).split("/");
        document.querySelector("#txtDate").valueAsDate = new Date(arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0]);
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
            let url = base_url+"/contabilidad/delEgress"
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