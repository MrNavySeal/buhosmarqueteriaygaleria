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
    request(base_url+"/contabilidad/getContabilidadMes",formData,"post").then(function(objData){
        btnContabilidadMes.innerHTML=`<i class="fas fa-search"></i>`;
        btnContabilidadMes.removeAttribute("disabled");
        document.querySelector("#txtMensual").innerHTML = `Mes de ${objData.mes+" "+objData.anio}`;
        document.querySelector("#costosMes").innerHTML=objData.costos;
        document.querySelector("#gastosMes").innerHTML=objData.gastos;
        document.querySelector("#utilidadBruta").innerHTML = objData.ingresos;
        document.querySelector("#utilidadNeta").innerHTML = objData.neto;
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
    request(base_url+"/contabilidad/getContabilidadAnio",formData,"post").then(function(objData){
        btnContabilidadAnio.innerHTML=`<i class="fas fa-search"></i>`;
        btnContabilidadAnio.removeAttribute("disabled");

        if(objData.status){
            $("#yearChart").html(objData.script);
            document.querySelector("#txtAnual").innerHTML = `Año ${objData.data.anio}`;
            document.querySelector("#costosAnual").innerHTML=objData.data.costos;
            document.querySelector("#gastosAnual").innerHTML=objData.data.gastos;
            document.querySelector("#utilidadBrutaAnual").innerHTML = objData.data.ingresos;
            document.querySelector("#utilidadNetaAnual").innerHTML = objData.data.neto;
        }else{
            Swal.fire("Error", objData.msg, "error");
            document.querySelector("#sYear").value ="";
        }
    });
});

search.addEventListener('input',function() {
    request(base_url+"/contabilidad/search/"+search.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

sort.addEventListener("change",function(){
    request(base_url+"/contabilidad/sort/"+sort.value,"","get").then(function(objData){
        if(objData.status){
            element.innerHTML = objData.data;
        }else{
            element.innerHTML = objData.data;
        }
    });
});

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        addItem();
    });
}

element.addEventListener("click",function(e) {
    let element = e.target;
    let id = element.getAttribute("data-id");
    if(element.name == "btnDelete"){
        deleteItem(id);
    }else if(element.name == "btnEdit"){
        editItem(id);
    }else if(element.name == "btnView"){
        viewItem(id);
    }
});

function addItem(){
    let modalItem = document.querySelector("#modalItem");
    let modal= `
    <div class="modal fade" id="modalElement">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Nuevo costo/gasto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formItem" name="formItem" class="mb-4">
                        <input type="hidden" id="idContabilidad" name="idContabilidad">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="typeList" class="form-label">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="typeList" name="typeList" required>
                                        <option value="1">Costo</option>
                                        <option value="2">Gasto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Fecha</label>
                                <input type="date" name="strDate" id="txtDate" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="txtNit" class="form-label">NIT (opcional)</label>
                                    <input type="text" class="form-control" id="txtNit" name="txtNit">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="txtName" class="form-label">Nombre de empresa<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtName" name="txtName" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="txtDescription" class="form-label">Descripción </label>
                                    <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="txtAmount" class="form-label">Total <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="txtAmount" name="txtAmount" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fas fa-plus-circle"></i> Agregar</button>
                            <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    `;

    modalItem.innerHTML = modal;
    let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));
    modalView.show();

    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();

        let strName = document.querySelector("#txtName").value;
        let strDescription = document.querySelector("#txtDescription").value;
        let intStatus = document.querySelector("#typeList").value;
        let intAmount = document.querySelector("#txtAmount").value;

        if(strName == "" || strDescription == "" || intStatus=="" || intAmount==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        
        let url = base_url+"/contabilidad/setCost";
        let formData = new FormData(form);
        let btnAdd = document.querySelector("#btnAdd");

        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");

        request(url,formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-plus-circle"></i> Agregar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Agregado",objData.msg,"success");
                element.innerHTML = objData.data;
                form.reset();
                modalView.hide();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
function viewItem(id){
    let url = base_url+"/contabilidad/getCost";
    let formData = new FormData();
    formData.append("idContabilidad",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            let type = "";

            if(objData.data.type == 1){
                type = 'Costo'
            }else{
                type ="Gasto"
            }
            let modalItem = document.querySelector("#modalItem");
            let modal= `
            <div class="modal fade" id="modalElement">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Datos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table align-middle text-break">
                                <tbody id="listItem">
                                    <tr>
                                        <td><strong>NIT:</strong></td>
                                        <td>${objData.data.nit}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td><strong>Nombre de empresa: </strong></td>
                                        <td>${objData.data.name} cm</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo: </strong></td>
                                        <td>${type}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descripcion: </strong></td>
                                        <td>${objData.data.description}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total: </strong></td>
                                        <td>${objData.data.priceFormat}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha: </strong></td>
                                        <td>${objData.data.date}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            `;
            modalItem.innerHTML = modal;
            let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));
            modalView.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function editItem(id){
    let url = base_url+"/contabilidad/getCost";
    let formData = new FormData();
    formData.append("idContabilidad",id);
    request(url,formData,"post").then(function(objData){
        let modalItem = document.querySelector("#modalItem");
        let modal= `
        <div class="modal fade" id="modalElement">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Actualizar costo/gasto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formItem" name="formItem" class="mb-4">
                            <input type="hidden" id="idContabilidad" name="idContabilidad" value="${objData.data.id}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="typeList" class="form-label">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-control" aria-label="Default select example" id="typeList" name="typeList" required>
                                            <option value="1">Costo</option>
                                            <option value="2">Gasto</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3 mb-3">
                                    <label for="" class="form-label">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" name="strDate" id="txtDate" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="txtNit" class="form-label">NIT (opcional)</label>
                                        <input type="text" class="form-control" id="txtNit" name="txtNit" value="${objData.data.nit}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="txtName" class="form-label">Nombre de la empresa<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="txtName" name="txtName" required value="${objData.data.name}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="txtDescription" class="form-label">Descripción </label>
                                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5">${objData.data.description}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="txtAmount" class="form-label">Total <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="txtAmount" name="txtAmount" value="${objData.data.total}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="btnAdd">Actualizar</button>
                                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        `;

        modalItem.innerHTML = modal;
        let modalView = new bootstrap.Modal(document.querySelector("#modalElement"));

        let status = document.querySelectorAll("#typeList option");
        for (let i = 0; i < status.length; i++) {
            if(status[i].value == objData.data.status){
                status[i].setAttribute("selected",true);
                break;
            }
        }
        let arrDate = new String(objData.data.date).split("/");

        document.querySelector("#txtDate").valueAsDate = new Date(arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0]);
        modalView.show();

        let form = document.querySelector("#formItem");
        form.addEventListener("submit",function(e){
            e.preventDefault();
    
            let strName = document.querySelector("#txtName").value;
            let strDescription = document.querySelector("#txtDescription").value;
            let intStatus = document.querySelector("#typeList").value;
            let intAmount = document.querySelector("#txtAmount").value;
            let strDate = document.querySelector("#txtDate").value;
            if(strName == "" || strDescription == "" || intStatus=="" || intAmount=="" || strDate==""){
                Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
                return false;
            }
            
            let url = base_url+"/contabilidad/setCost";
            let formData = new FormData(form);
            let btnAdd = document.querySelector("#btnAdd");
    
            btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            btnAdd.setAttribute("disabled","");
    
            request(url,formData,"post").then(function(objData){
                btnAdd.innerHTML=`Actualizar`;
                btnAdd.removeAttribute("disabled");
                if(objData.status){
                    Swal.fire("Actualizado",objData.msg,"success");
                    element.innerHTML = objData.data;
                    form.reset();
                    modalView.hide();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        })
    });
}
function deleteItem(id){
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
            let url = base_url+"/contabilidad/delCost"
            let formData = new FormData();
            formData.append("idContabilidad",id);
            request(url,formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    element.innerHTML = objData.data;
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}
