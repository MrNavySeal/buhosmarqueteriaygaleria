let formContact = document.querySelector("#formContact");
formContact.addEventListener("submit",function(e){
    e.preventDefault();
    
    let strName = document.querySelector("#txtContactName").value;
    let strEmail = document.querySelector("#txtContactEmail").value;
    let strPhone = document.querySelector("#txtContactPhone").value;
    let strMessage = document.querySelector("#txtContactMessage").value;
    let alert = document.querySelector("#alertContact");
    let btn = document.querySelector("#btnMessage");

    if( strName =="" || strEmail =="" || strMessage == "" || strPhone == ""){
        alert.classList.remove("d-none");
        alert.innerHTML="Por favor, completa los campos.";
        return false;
    }
    if(strPhone.length < 10 || strPhone.lenght > 10){
        alert.classList.remove("d-none");
        alert.innerHTML="El número de teléfono debe tener 10 dígitos";
        return false;
    }
    if(!fntEmailValidate(strEmail)){
        alert.classList.remove("d-none");
        alert.innerHTML = "El correo electrónico es incorrecto";
        return false;
    }

    btn.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;    
    btn.setAttribute("disabled","");
    let formData = new FormData(formContact);
    request(base_url+"/contacto/setContact",formData,"post").then(function(objData){
        btn.innerHTML="Enviar mensaje";    
        btn.removeAttribute("disabled");
        if(objData.status){
            alert.classList.remove("d-none");
            alert.classList.replace("alert-danger","alert-success");
            alert.innerHTML =objData.msg;
            formContact.reset();
        }else{
            alert.classList.remove("d-none");
            alert.classList.replace("alert-success","alert-danger");
            alert.innerHTML =objData.msg;
        }
    });

});