const perPage = document.querySelector("#perPage");
const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");
const searchHtml = document.querySelector("#txtSearch");
const exportExcel = document.querySelector("#exportExcel");
const exportPDF = document.querySelector("#exportPDF");
let arrData = [];
window.addEventListener("load",function(e){
    const initialDate = new Date();
    const finalDate  = new Date(new Date(initialDate.getFullYear(), initialDate.getMonth() + 1, 0));
    const initialDateFormat = new Date((initialDate.getMonth()+1)+"-01-"+initialDate.getFullYear()).toISOString().split("T")[0];
    const finalDateFormat = finalDate.toISOString().split("T")[0];
    initialDateHtml.value = initialDateFormat;  // Get the current date
    finallDateHtml.value = finalDateFormat;   // Get the current date
    getData();
})
initialDateHtml.addEventListener("input",function(){getData();});
finallDateHtml.addEventListener("input",function(){getData();});
searchHtml.addEventListener("input",function(){getData();});
perPage.addEventListener("change",function(){getData();});

async function getData(page = 1){
    const formData = new FormData();
    formData.append("initial_date",initialDateHtml.value);
    formData.append("final_date",finallDateHtml.value);
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/inventarioAjuste/getAdjustmentDet",{method:"POST",body:formData});
    const objData = await response.json();
    const arrHtml = objData.html;
    arrData = objData.export;
    document.querySelector("#pagination").innerHTML = arrHtml.pages;
    document.querySelector("#tableData").innerHTML =arrHtml.products;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}

exportExcel.addEventListener("click",function(){
    if(arrData.length == 0){
        Swal.fire("Error","No hay datos generados para exportar.","error");
        return false;
    }
    const form = document.createElement("form");
    document.body.appendChild(form);
    addField("data",JSON.stringify(arrData),"hidden",form);
    addField("strInititalDate",initialDateHtml.value,"hidden",form);
    addField("strFinalDate",finallDateHtml.value,"hidden",form);
    form.target="_blank";
    form.method="POST";
    form.action=base_url+"/InventarioAjusteDetExport/excel";
    form.submit();
    form.remove();
});
exportPDF.addEventListener("click",async function(){
    if(arrData.length == 0){
        Swal.fire("Error","No hay datos generados para exportar.","error");
        return false;
    }
    const form = document.createElement("form");
    document.body.appendChild(form);
    addField("data",JSON.stringify(arrData),"hidden",form);
    addField("strInititalDate",initialDateHtml.value,"hidden",form);
    addField("strFinalDate",finallDateHtml.value,"hidden",form);
    form.target="_blank";
    form.method="POST";
    form.action=base_url+"/InventarioAjusteDetExport/pdf";
    form.submit();
    form.remove();
});