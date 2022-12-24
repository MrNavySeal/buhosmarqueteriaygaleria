
let img = document.querySelector("#txtImg");
let imgLocation = ".uploadImg img";
img.addEventListener("change",function(){
    uploadImg(img,imgLocation);
});

let intCountry = document.querySelector("#countryList");
let intState = document.querySelector("#stateList");
let intCity = document.querySelector("#cityList");
let formProfile = document.querySelector("#formProfile");

request(base_url+"/usuarios/getSelectLocationInfo","","get").then(function(objData){
    intCountry.innerHTML = objData.countries;
    intState.innerHTML = objData.states;
    intCity.innerHTML = objData.cities;
});

intCountry.addEventListener("change",function(){
    let url = base_url+"/usuarios/getSelectCountry/"+intCountry.value;
    request(url,"","get").then(function(objData){
        intState.innerHTML = objData;
    });
    intCity.innerHTML = "";
});
intState.addEventListener("change",function(){
    let url = base_url+"/usuarios/getSelectState/"+intState.value;
    request(url,"","get").then(function(objData){
        intCity.innerHTML = objData;
    });
});

formProfile.addEventListener("submit",function(e){
    e.preventDefault();

    let url = base_url+"/usuarios/updateProfile";
    let strFirstName = document.querySelector("#txtFirstName").value;
    let strLastName = document.querySelector("#txtLastName").value;
    let strEmail = document.querySelector("#txtEmail").value;
    let strPhone = document.querySelector("#txtPhone").value;
    let intCountry = document.querySelector("#countryList").value;
    let intState = document.querySelector("#stateList").value;
    let intCity = document.querySelector("#cityList").value;
    let strAddress = document.querySelector("#txtAddress");
    let strPassword = document.querySelector("#txtPassword").value;
    let strConfirmPassword = document.querySelector("#txtConfirmPassword").value;
    let idusuarios = document.querySelector("#idUser").value;

    if(strFirstName == "" || strLastName == "" || strEmail == "" || strPhone == "" || intCountry == 0 || intState == 0
    || intCity == 0 || strAddress ==""){
        Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
        return false;
    }
    if(strPassword!=""){
        if(strPassword.length < 8){
            Swal.fire("Error","La contraseña debe tener al menos 8 caracteres","error");
            return false;
        }
        if(strPassword != strConfirmPassword){
            Swal.fire("Error","Las contraseñas no coinciden","error");
            return false;
        }
    }
    if(!fntEmailValidate(strEmail)){
        Swal.fire("Error","El correo electrónico no es válido","error");
        return false;
    }
    if(strPhone.length < 9){
        Swal.fire("Error","El número de teléfono debe tener al menos 9 dígitos","error");
        return false;
    }

    let formData = new FormData(formProfile);
    let btnAdd = document.querySelector("#btnAdd");
    btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAdd.setAttribute("disabled","");
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            Swal.fire("Perfil",objData.msg,"success");
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
        btnAdd.innerHTML="Actualizar";
        btnAdd.removeAttribute("disabled");
    })
})
