let arrData = [];
window.addEventListener("load",function(e){
    getKardex();
})
async function getKardex(){
    const response = await fetch(base_url+"/inventario/getKardex");
    const objData = await response.json();
    arrData = objData.data;
    document.querySelector("#tableData").innerHTML =objData.html;
}