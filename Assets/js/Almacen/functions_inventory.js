const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
let arrData = [];
let floatTotal ="";
window.addEventListener("load",function(e){
    getData()
})
searchHtml.addEventListener("input",function(){getData();});
perPage.addEventListener("change",function(){getData();});

async function getData(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/Almacen/Inventario/getProducts",{method:"POST",body:formData});
    const objData = await response.json();
    const arrHtml = objData.html;
    arrData = objData.data;
    floatTotal = objData.total;
    document.querySelector("#pagination").innerHTML = arrHtml.pages;
    document.querySelector("#totalInventory").innerHTML = objData.total_format;
    document.querySelector("#tableData").innerHTML =arrHtml.products;
    document.querySelector("#tableData").innerHTML =arrHtml.products;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}
function exportExcel(){
    if(arrData.length == 0){
        Swal.fire("Error","No hay datos generados para exportar.","error");
        return false;
    }
    const form = document.createElement("form");
    document.body.appendChild(form);
    addField("data",JSON.stringify(arrData),"hidden",form);
    addField("total",floatTotal,"hidden",form);
    form.target="_blank";
    form.method="POST";
    form.action=base_url+"/Almacen/InventarioExport/excel";
    form.submit();
    form.remove();
}
function exportPdf(){
    if(arrData.length == 0){
        Swal.fire("Error","No hay datos generados para exportar.","error");
        return false;
    }
    const form = document.createElement("form");
    document.body.appendChild(form);
    addField("data",JSON.stringify(arrData),"hidden",form);
    addField("total",floatTotal,"hidden",form);
    form.target="_blank";
    form.method="POST";
    form.action=base_url+"/Almacen/InventarioExport/pdf";
    form.submit();
    form.remove();
}