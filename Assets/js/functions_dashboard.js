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


window.addEventListener("load",function(e){
    getDatosIniciales();
}); 
function getDatosIniciales(){
    let strFechaActual = new Date();
    let intVigencia = strFechaActual.getFullYear();
    let intMes = strFechaActual.getMonth()+1;
    let valorMes = intMes+" - "+intVigencia;
    let valorAnual = intVigencia;
    let formData = new FormData();

    //Ventas
    formData.append("date",valorMes);
    request(base_url+"/dashboard/getContabilidadMes",formData,"post").then(function(objData){
        $("#monthChart").html(objData.script);
    });

    formData.append("date",valorAnual);
    request(base_url+"/dashboard/getContabilidadAnio",formData,"post").then(function(objData){
        $("#yearChart").html(objData.script);
    });
    //Clientes
    formData.append("date",valorMes);
    request(base_url+"/dashboard/getClientesMes",formData,"post").then(function(objData){
        $("#monthChartCustomers").html(objData.script);
    });
    formData.append("date",valorAnual);
    request(base_url+"/dashboard/getClientesAnio",formData,"post").then(function(objData){
        $("#yearChartCustomers").html(objData.script);
    });
    //Vistas
    formData.append("date",valorMes);
    request(base_url+"/dashboard/getVisitasMes",formData,"post").then(function(objData){
        $("#monthChartViews").html(objData.script);
    });
    formData.append("date",valorAnual);
    request(base_url+"/dashboard/getVisitasAnio",formData,"post").then(function(objData){
        $("#yearChartViews").html(objData.script);
    });

    request(base_url+"/dashboard/getVisitasPais","","get").then(function(objData){
        $("#countryChartViews").html(objData.script);
    });
    request(base_url+"/dashboard/getVisitasPaginas","","get").then(function(objData){
        $("#pageChartViews").html(objData.script);
    });
}
let btnContabilidadMes = document.querySelector("#btnContabilidadMes");
let btnContabilidadAnio = document.querySelector("#btnContabilidadAnio");
let btnVisitasMes = document.querySelector("#btnVisitasMes");
let btnVisitasAnio = document.querySelector("#btnVisitasAnio");
let btnClientesMes = document.querySelector("#btnClientesMes");
let btnClientesAnio = document.querySelector("#btnClientesAnio");

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

btnVisitasMes.addEventListener("click",function(){
    let valor = document.querySelector(".visitasMes").value;
    if(valor==""){
        Swal.fire("Error", "Elija una fecha", "error");
        return false;
    }
    btnVisitasMes.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnVisitasMes.setAttribute("disabled","");
    let formData = new FormData();
    formData.append("date",valor);
    request(base_url+"/dashboard/getVisitasMes",formData,"post").then(function(objData){
        btnVisitasMes.innerHTML=`<i class="fas fa-search"></i>`;
        btnVisitasMes.removeAttribute("disabled");
        $("#monthChartViews").html(objData.script);
    });
});
btnVisitasAnio.addEventListener("click",function(){
    
    let salesYear = document.querySelector("#viewYear").value;
    let strYear = salesYear.toString();

    if(salesYear==""){
        Swal.fire("Error", "Por favor, ponga un año", "error");
        document.querySelector("#viewYear").value ="";
        return false;
    }
    if(strYear.length>4){
        Swal.fire("Error", "El año es incorrecto.", "error");
        document.querySelector("#viewYear").value ="";
        return false;
    }
    btnVisitasAnio.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnVisitasAnio.setAttribute("disabled","");

    let formData = new FormData();
    formData.append("date",salesYear);
    request(base_url+"/dashboard/getVisitasAnio",formData,"post").then(function(objData){
        btnVisitasAnio.innerHTML=`<i class="fas fa-search"></i>`;
        btnVisitasAnio.removeAttribute("disabled");

        if(objData.status){
            $("#yearChartViews").html(objData.script);
        }else{
            Swal.fire("Error", objData.msg, "error");
            document.querySelector("#viewYear").value ="";
        }
    });
});

btnClientesMes.addEventListener("click",function(){
    let valor = document.querySelector(".clientesMes").value;
    if(valor==""){
        Swal.fire("Error", "Elija una fecha", "error");
        return false;
    }
    btnClientesMes.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnClientesMes.setAttribute("disabled","");
    let formData = new FormData();
    formData.append("date",valor);
    request(base_url+"/dashboard/getClientesMes",formData,"post").then(function(objData){
        btnClientesMes.innerHTML=`<i class="fas fa-search"></i>`;
        btnClientesMes.removeAttribute("disabled");
        $("#monthChartCustomers").html(objData.script);
    });
});
btnClientesAnio.addEventListener("click",function(){
    
    let salesYear = document.querySelector("#customerYear").value;
    let strYear = salesYear.toString();

    if(salesYear==""){
        Swal.fire("Error", "Por favor, ponga un año", "error");
        document.querySelector("#customerYear").value ="";
        return false;
    }
    if(strYear.length>4){
        Swal.fire("Error", "El año es incorrecto.", "error");
        document.querySelector("#customerYear").value ="";
        return false;
    }
    btnClientesAnio.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    btnClientesAnio.setAttribute("disabled","");

    let formData = new FormData();
    formData.append("date",salesYear);
    request(base_url+"/dashboard/getClientesAnio",formData,"post").then(function(objData){
        btnClientesAnio.innerHTML=`<i class="fas fa-search"></i>`;
        btnClientesAnio.removeAttribute("disabled");

        if(objData.status){
            $("#yearChartCustomers").html(objData.script);
        }else{
            Swal.fire("Error", objData.msg, "error");
            document.querySelector("#customerYear").value ="";
        }
    });
});