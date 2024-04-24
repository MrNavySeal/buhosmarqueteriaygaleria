const inputFile = document.querySelector("#formFile");
const btnAdd = document.querySelector("#btnAdd");

btnAdd.addEventListener("click",function(){
    if(inputFile.files.length == 0){
        Swal.fire("Error","Debe subir la plantilla.","error");
        return false;
    }
    let file = inputFile.files[0];
    let extension = file.name.split(".")[1];
    if(extension != "xlsx"){
        Swal.fire("Error","El archivo es incorrecto; por favor, utiliza nuestra plantilla.","error");
        return false;
    }
    let formData = new FormData();
    formData.append("template",file);

    btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verificando productos...`;  
    //btnAdd.setAttribute("disabled","");
    request(base_url+"/ProductosMasivos/uploadProducts",formData,"post").then(function(objData){
    });
    //setInterval(getProgress(),1);
});
function getProgress(){
    request(base_url+"/ProductosMasivos/getProgress","","get").then(function(objData){
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verificando productos ${objData}%`;  
        //btnAdd.removeAttribute("disabled");
    });
}