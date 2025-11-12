const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");
const searchHtml = document.querySelector("#txtSearch");
const btnGenerate = document.querySelector("#btnGenerate");

let arrData = [];
window.addEventListener("load",function(e){
    const initialDate = new Date();
    const finalDate  = new Date(new Date(initialDate.getFullYear(), initialDate.getMonth() + 1, 0));
    const initialDateFormat = new Date((initialDate.getMonth()+1)+"-01-"+initialDate.getFullYear()).toISOString().split("T")[0];
    const finalDateFormat = finalDate.toISOString().split("T")[0];
    initialDateHtml.value = initialDateFormat;  // Get the current date
    finallDateHtml.value = finalDateFormat;   // Get the current date
})
async function getKardex(){
    const formData = new FormData();
    formData.append("initial_date",initialDateHtml.value);
    formData.append("final_date",finallDateHtml.value);
    formData.append("search",searchHtml.value);

    /* btnGenerate.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnGenerate.setAttribute("disabled",""); */

    const response = await fetch(base_url+"/Almacen/inventario/getKardex",{method:"POST",body:formData});
    const objData = await response.json();

    /* btnGenerate.innerHTML=`Generar`;
    btnGenerate.removeAttribute("disabled"); */

    arrData = objData.data;
    document.querySelector("#tableData").innerHTML =objData.html;
}

function exportExcel(){
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
    form.action=base_url+"/Almacen/InventarioKardexExport/excel";
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
    addField("strInititalDate",initialDateHtml.value,"hidden",form);
    addField("strFinalDate",finallDateHtml.value,"hidden",form);
    form.target="_blank";
    form.method="POST";
    form.action=base_url+"/Almacen/InventarioKardexExport/pdf";
    form.submit();
    form.remove();
}
