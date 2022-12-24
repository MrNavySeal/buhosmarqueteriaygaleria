'use strict';

$('.date-picker').datepicker( {
    closeText: 'Cerrar',
    prevText: 'atrás',
    nextText: 'siguiente',
    currentText: 'Hoy',
    monthNames: ['1 -', '2 -', '3 -', '4 -', '5 -', '6 -', '7 -', '8 -', '9 -', '10 -', '11 -', '12 -'],
    monthNamesShort: ['Enero','Febrero','Marzo','Abril', 'Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre','Noviembre','Diciembre'],
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'MM yy',
    showDays: false,
    onClose: function(dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    }
});

let search = document.querySelector("#search");
let sort = document.querySelector("#sortBy");
let element = document.querySelector("#listItem");

let btnContabilidadMes = document.querySelector("#btnContabilidadMes");
let btnContabilidadAnio = document.querySelector("#btnContabilidadAnio");
btnContabilidadMes.addEventListener("click",function(){
    let contabilidadMes = document.querySelector(".contabilidadMes").value;
    if(contabilidadMes==""){
        Swal.fire("Error", "Elija una fecha", "error");
        return false;
    }
    btnContabilidadMes.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnContabilidadMes.setAttribute("disabled","");
    let formData = new FormData();
    formData.append("date",contabilidadMes);
    request(base_url+"/dashboard/getContabilidadMes",formData,"post").then(function(objData){
        btnContabilidadMes.innerHTML=`<i class="fas fa-search"></i>`;
        btnContabilidadMes.removeAttribute("disabled");
        $("#monthChart").html(objData.script);
    });
});
btnContabilidadAnio.addEventListener("click",function(){
    
    let salesYear = document.querySelector("#sYear").value;
    let strYear = salesYear.toString();

    if(salesYear==""){
        Swal.fire("Error", "Por favor, ponga un año", "error");
        document.querySelector("#sYear").value ="";
        return false;
    }
    if(strYear.length>4){
        Swal.fire("Error", "El año es incorrecto.", "error");
        document.querySelector("#sYear").value ="";
        return false;
    }
    btnContabilidadAnio.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnContabilidadAnio.setAttribute("disabled","");

    let formData = new FormData();
    formData.append("date",salesYear);
    request(base_url+"/dashboard/getContabilidadAnio",formData,"post").then(function(objData){
        btnContabilidadAnio.innerHTML=`<i class="fas fa-search"></i>`;
        btnContabilidadAnio.removeAttribute("disabled");

        if(objData.status){
            $("#yearChart").html(objData.script);
        }else{
            Swal.fire("Error", objData.msg, "error");
            document.querySelector("#sYear").value ="";
        }
    });
});