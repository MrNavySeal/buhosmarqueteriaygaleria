const inputFile = document.querySelector("#formFile");
const btnAdd = document.querySelector("#btnAdd");
const btnDownloadEdit = document.querySelector("#btnDownloadEdit");
const categoryList = document.querySelector("#categoryList");
const subCategoryContent = document.querySelector("#subCategoryContent");
const subcategoryList = document.querySelector("#subcategoryList");
if(categoryList.value == 0){
    subCategoryContent.classList.add("d-none");
}else{
    subCategoryContent.classList.remove("d-none");
}

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

    btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Espere...`;  
    btnAdd.setAttribute("disabled","");
    request(base_url+"/ProductosMasivos/uploadProducts",formData,"post").then(function(objData){
        btnAdd.innerHTML="Cargar archivo";
        btnAdd.removeAttribute("disabled","");
        if(objData.status){
            inputFile.files[0] = "";
            Swal.fire("",objData.msg,"success");
        }
    });
});
btnDownloadEdit.addEventListener("click",function(){
    let formData = new FormData();
    formData.append("category",categoryList.value);
    formData.append("subcategory",subcategoryList.value);
    formData.append("action","editar");
    request(base_url+"/ProductosMasivos/plantilla",formData,"post").then(function(objData){});
})
function changeCategory(){
    if(categoryList.value != 0){
        subCategoryContent.classList.remove("d-none");
        let formData = new FormData();
        formData.append("idCategory",categoryList.value);
        request(base_url+"/ProductosCategorias/getSelectSubcategories",formData,"post").then(function(objData){
            subcategoryList.innerHTML = objData.data;
            subcategoryList.options[0].innerHTML = "Todos";
        });
    }else{
        subCategoryContent.classList.add("d-none");
    }
}