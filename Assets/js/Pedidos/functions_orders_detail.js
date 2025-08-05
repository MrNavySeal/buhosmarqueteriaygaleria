let arrData = [];
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");

window.addEventListener("load",function(){
    initialDateHtml.value = new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0];
    finallDateHtml.value = new Date().toISOString().split("T")[0]; 
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
    const response = await fetch(base_url+"/Pedidos/Pedidos/getDetailOrders",{method:"POST",body:formData});
    const objData = await response.json();
    arrData = objData.data;
    tableData.innerHTML =objData.html;
    document.querySelector("#pagination").innerHTML = objData.html_pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
}