setTinymce("#txtDescription",600);
document.querySelector("#btnNew").classList.add("d-none");
let img = document.querySelector("#txtImg");
let imgLocation = ".uploadImg img";
img.addEventListener("change",function(){
    uploadImg(img,imgLocation);
});
let formPage = document.querySelector("#formPage");
formPage.addEventListener("submit",function(e){
    e.preventDefault();
    tinymce.triggerSave();
    let strName = document.querySelector("#txtName").value;
    let strDescription = document.querySelector("#txtDescription").value;
    let intStatus = document.querySelector("#statusList").value;
    let intType = document.querySelector("#typeList").value;
    if(strName == "" || strDescription =="" || intStatus =="" || intType==""){
        Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
        return false;
    }
    
    let btnAdd = document.querySelector("#btnAdd");
    let formData = new FormData(formPage);
    btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAdd.setAttribute("disabled","");
    request(base_url+"/paginas/setPage",formData,"post").then(function(objData){
        btnAdd.innerHTML=`Actualizar`;
        btnAdd.removeAttribute("disabled");
        if(objData.status){
            window.location.reload();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
});