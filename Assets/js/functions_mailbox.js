'use strict';

if(document.querySelector("#correo")){
    setTinymce("#txtMessage");
    
    let formEmail = document.querySelector("#formEmail");
    formEmail.addEventListener("submit",function(e){
        e.preventDefault();
        tinymce.triggerSave();
        let strEmail = document.querySelector("#txtEmail");
        let strMessage = document.querySelector("#txtMessage");
        let btn = document.querySelector("#btnSubmit");
        if(strEmail == "" || strMessage ==""){
            Swal.fire("Error", "Por favor, rellene los campos con (*)", "error");
            return false;
        }
        let formData = new FormData(formEmail);
        btn.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> `;
        btn.setAttribute("disabled","");
        request(base_url+"/administracion/sendEmail",formData,"post").then(function(objData){
            btn.innerHTML=`<i class="fas fa-paper-plane"></i> Responder`;
            btn.removeAttribute("disabled");
            if(objData.status){
                window.location.reload();
            }else{
                Swal.fire("Error", objData.msg, "error");
            }
        });
    });
}

if(document.querySelector("#mensaje") && document.querySelector("#formReply")){
    setTinymce("#txtMessage");
    let formReply = document.querySelector("#formReply");
    formReply.addEventListener("submit",function(e){
        e.preventDefault();
        tinymce.triggerSave();
        let btn = document.querySelector("#btnSubmit");
        let strMessage = document.querySelector("#txtMessage").value;
        if(strMessage ==""){
            Swal.fire("Error", "Please fill the field", "error");
            return false;
        }
        let formData = new FormData(formReply);
        btn.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> `;
        btn.setAttribute("disabled","");
        request(base_url+"/administracion/setReply",formData,"post").then(function(objData){
            btn.innerHTML=`<i class="fas fa-paper-plane"></i> Responder`;
            btn.removeAttribute("disabled");
            if(objData.status){
                window.location.reload();
            }else{
                Swal.fire("Error", objData.msg, "error");
            }
        });
    });

}
function delMail(id,option){
    Swal.fire({
        title:"¿Estás seguro de eliminarlo?",
        text:"Se eliminará para siempre...",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Sí, eliminar",
        cancelButtonText:"No, cancelar"
    }).then(function(result){
        if(result.isConfirmed){
            let formData = new FormData();
            formData.append("id",id);
            formData.append("option",option);
            request(base_url+"/administracion/delMail",formData,"post").then(function(objData){
                if(objData.status){
                    window.location.reload();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}