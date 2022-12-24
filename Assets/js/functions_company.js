
let img = document.querySelector("#txtImg");
let imgLocation = ".uploadImg img";
let intCountry = document.querySelector("#countryList");
let intState = document.querySelector("#stateList");
let intCity = document.querySelector("#cityList");
let formCompany = document.querySelector("#formCompany");
let formSocial = document.querySelector("#formSocial");
let formPayment = document.querySelector("#formPayment");

img.addEventListener("change",function(){
    uploadImg(img,imgLocation);
});

intCountry.addEventListener("change",function(){
    let url = base_url+"/empresa/getSelectCountry/"+intCountry.value;
    request(url,"","get").then(function(objData){
        intState.innerHTML = objData;
    });
    intCity.innerHTML = "";
});
intState.addEventListener("change",function(){
    let url = base_url+"/empresa/getSelectState/"+intState.value;
    request(url,"","get").then(function(objData){
        intCity.innerHTML = objData;
    });
});


formCompany.addEventListener("submit",function(e){
    e.preventDefault();

    let strName = document.querySelector("#txtName").value;
    let intCurrency = document.querySelector("#currencyList").value;
    let strCompanyEmail = document.querySelector("#txtCompanyEmail").value;
    let strEmail = document.querySelector("#txtEmail").value;
    let strPhone = document.querySelector("#txtPhone").value;
    let strAddress = document.querySelector("#txtAddress").value;
    let intCountry = document.querySelector("#countryList").value;
    let intState = document.querySelector("#stateList").value;
    let intCity = document.querySelector("#cityList").value;
    let strPassword = document.querySelector("#txtPassword").value;

    if(strName == "" || intCurrency == "" || strCompanyEmail=="" || strEmail == "" || strPhone == "" || strAddress ==""
    || intCountry == "" || intState == ""
    || intCity == "" || strPassword==""){
        Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
        return false;
    }
    if(!fntEmailValidate(strCompanyEmail)){
        Swal.fire("Error","El correo empresarial es invalido","error");
        return false;
    }
    if(!fntEmailValidate(strEmail)){
        Swal.fire("Error","El correo secundario es invalido","error");
        return false;
    }
    if(strPhone.length < 9){
        Swal.fire("Error","El número de teléfono debe tener al menos 9 dígitos","error");
        return false;
    }

    let formData = new FormData(formCompany);
    let btnAdd = document.querySelector("#btnCompany");

    btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAdd.setAttribute("disabled","");

    request(base_url+"/empresa/setCompany",formData,"post").then(function(objData){
        if(objData.status){
            Swal.fire("Guardado",objData.msg,"success");
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
        btnAdd.innerHTML="Guardar";
        btnAdd.removeAttribute("disabled");
    })
})
formSocial.addEventListener("submit",function(e){
    e.preventDefault();
    let formData = new FormData(formSocial);
    let btnAdd = document.querySelector("#btnSocial");

    btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAdd.setAttribute("disabled","");

    request(base_url+"/empresa/setSocial",formData,"post").then(function(objData){
        if(objData.status){
            Swal.fire("Guardado",objData.msg,"success");
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
        btnAdd.innerHTML="Guardar";
        btnAdd.removeAttribute("disabled");
    })
});
formPayment.addEventListener("submit",function(e){
    e.preventDefault();
    let client = document.querySelector("#txtClient");
    let secret = document.querySelector("#txtSecret");

    if(client =="" || secret==""){
        Swal.fire("Error","Los campos no pueden estar vacíos.","error");
        return false;
    }

    let formData = new FormData(formPayment);
    let btnAdd = document.querySelector("#btnPayment");

    btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnAdd.setAttribute("disabled","");

    request(base_url+"/empresa/setCredentials",formData,"post").then(function(objData){
        if(objData.status){
            Swal.fire("Guardado",objData.msg,"success");
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
        btnAdd.innerHTML="Guardar";
        btnAdd.removeAttribute("disabled");
    })
});
