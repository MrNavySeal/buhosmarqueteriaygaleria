'use strict';

window.addEventListener("load",function(){
    let form = document.querySelector("#formItem");
    let img = document.querySelector("#txtImg");
    let imgLocation = ".uploadImg img";
    img.addEventListener("change",function(){
        uploadImg(img,imgLocation);
    });
    setTinymce("#txtDescription");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        tinymce.triggerSave();
        let strName = document.querySelector("#txtName").value;
        let strDescription = document.querySelector("#txtDescription").value;
        let strShortDescription = document.querySelector("#txtShortDescription").value;
        let intStatus = document.querySelector("#statusList");

        if(strName == "" || intStatus == "" || strShortDescription=="" || strDescription==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }

        let data = new FormData(form);
        
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(base_url+"/articulos/setArticle",data,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                window.location.href=base_url+"/articulos/articulos";
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
        
    });
});
