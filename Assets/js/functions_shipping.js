'use strict';

let forms = document.querySelectorAll("form");
let intCountry = document.querySelector("#countryList");
let intState = document.querySelector("#stateList");
let intCity = document.querySelector("#cityList");
let addCity = document.querySelector("#addCity");
let btnShipping = document.querySelectorAll(".btnShipping");

for (let i = 0; i < forms.length; i++) {
    let form = forms[i]
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let idShipping = document.querySelectorAll(".idShipping")[i].value;
        if(idShipping == 2){
            if(document.querySelector("#intValue").value==""){
                Swal.fire("Error","Por favor, establezca un valor de envío.","error");
                return false;
            }
        }
        if(idShipping == 3 && document.querySelectorAll("tr").length == 0){
            Swal.fire("Error","Por favor, agregue una ciudad.","error");
            return false;
        }
        let formData = new FormData(form);
        btnShipping[i].innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnShipping[i].setAttribute("disabled","");
        request(base_url+"/administracion/setShippingMode",formData,"post").then(function(objData){
            btnShipping[i].innerHTML=`Guardar`;
            btnShipping[i].removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Envio",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
if(document.querySelector("#addCity")){
    intCountry.addEventListener("change",function(){
        request(base_url+"/administracion/getSelectCountry/"+intCountry.value,"","get").then(function(objData){
            intState.innerHTML = objData;
        });
        intCity.innerHTML = "";
    });
    intState.addEventListener("change",function(){
        request(base_url+"/administracion/getSelectState/"+intState.value,"","get").then(function(objData){
            intCity.innerHTML = objData;
        });
    });
    addCity.addEventListener("click",function(){
        let idCountry = intCountry.value;
        let idState = intState.value;
        let idCity = intCity.value;
        let value = document.querySelector("#valueCity").value;
        if(idCountry == 0 || idState == 0 || idCity == 0 || value ==""){
            Swal.fire("Error","Por favor, rellene los campos.","error");
            return false;
        }
        let formData = new FormData();
        formData.append("idCountry",idCountry);
        formData.append("idState",idState);
        formData.append("idCity",idCity);
        formData.append("value",value);
        addCity.innerHTML=`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        addCity.setAttribute("disabled","");
        request(base_url+"/administracion/setShippingCity",formData,"post").then(function(objData){
            addCity.innerHTML="+";
            addCity.removeAttribute("disabled");
            if(objData.status){
                document.querySelector("#listItem").innerHTML = objData.html;
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    });
}

function deleteCityShipp(id){
    request(base_url+"/administracion/delShippingCity/"+id,"","get").then(function(objData){
        if(objData.status){
            document.querySelector("#listItem").innerHTML = objData.html;
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
    
