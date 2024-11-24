'use strict';

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
}
let arrData = [];
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");

window.addEventListener("load",function(){
    getData();
});
searchHtml.addEventListener("input",function(){getData();});
perPage.addEventListener("change",function(){getData();});

async function getData(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/Productos/getProducts",{method:"POST",body:formData});
    const objData = await response.json();
    arrData = objData.data;
    tableData.innerHTML =objData.html;
    document.querySelector("#pagination").innerHTML = objData.html_pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
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
            let formData = new FormData();
            formData.append("idProduct",id);
            request(base_url+"/Productos/delProduct",formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    getData();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}