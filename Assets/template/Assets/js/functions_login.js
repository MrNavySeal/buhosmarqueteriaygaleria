if(document.querySelector("#formRecovery")){
    let formReset = document.querySelector("#formRecovery");
    formReset.addEventListener("submit",function(e){
        e.preventDefault();
        
        let strPassword = document.querySelector("#txtPasswordRecovery").value;
        let strPasswordConfirm = document.querySelector("#txtPasswordConfirmRecovery").value;
        let idUser = document.querySelector("#idUser").value;
        let strEmail = document.querySelector("#txtEmailRecovery").value;
        let strToken = document.querySelector("#txtToken").value;
        let url = base_url+'/login/setPassword'; 
        let btn = document.querySelector("#recoverySubmit");

        let formData = new FormData(formReset);
        
        formData.append("txtToken",strToken);
        formData.append("txtEmail",strEmail);
        formData.append("idUsuario",idUser);

        
        if(strPassword == "" || strPasswordConfirm==""){
            Swal.fire("Error", "Por favor, pon tu nueva contraseña.", "error");
            return false;
        }else{
            if(strPassword.length < 8){
                Swal.fire("Error","La contraseña debe tener al menos 8 carácteres","error");
                return false;
            }if(strPassword != strPasswordConfirm){
                Swal.fire("Error","Las contraseñas no coinciden","error");
            return false;
            }
            btn.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;    
            btn.setAttribute("disabled","");
            request(url,formData,"post").then(function(objData){
                btn.innerHTML="Actualizar contraseña";    
                btn.removeAttribute("disabled");
                if(objData.status){
                    window.location.reload();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}