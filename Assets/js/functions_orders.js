'use strict';
if(document.querySelector("#quickSale")){
    window.addEventListener("load",function(){
        updateCart();
    })
    const moneyReceived = document.querySelector("#moneyReceived");
    const btnAddPos = document.querySelector("#btnAddPos");
    let searchProducts = document.querySelector("#searchProducts");
    let searchCustomers = document.querySelector("#searchCustomers");
    const cupon = document.querySelector("#discount");
    cupon.addEventListener("input",function(){
        if(cupon.value <= 0){
            cupon.value = 0;
        }else if(cupon.value >= 100){
            cupon.value = 90;
        }
        let total = moneyReceived.value;
        total = parseInt(total-(total*(cupon.value*0.01)));
        
        document.querySelector("#saleValue").innerHTML = "Valor de venta: "+MS+total;
        
    });
    moneyReceived.addEventListener("input",function(){
        let total = document.querySelector("#total").getAttribute("data-value");
        let result = 0;
        if(cupon.value > 0){
            total  = parseInt(total-(total*(cupon.value*0.01)));
            document.querySelector("#saleValue").innerHTML = "Valor de venta: "+MS+total;
        }
        result = moneyReceived.value - total;
        if(result < 0){
            result = 0;
        }
        document.querySelector("#moneyBack").innerHTML = "Dinero a devolver: "+MS+result;
    });
    
    let formPOS = document.querySelector("#formSetOrder");
    formPOS.addEventListener("submit",function(e){
        
        e.preventDefault();
        let id = document.querySelector("#idCustomer").value;
        let received = moneyReceived.value;
        let strDate = document.querySelector("#txtDate").value;
        let strNote = document.querySelector("#txtNotePos").value;
        let strTransaction = document.querySelector("#txtTransaction").value;
        if(id <= 0){
            Swal.fire("Error","Por favor, añada un cliente para establecer el pedido","error");
            return false;
        }
        if(received =="" || strNote =="" || strTransaction ==""){
            Swal.fire("Error","Los campos con (*) son obligatorios","error");
            return false;
        }
        let formData = new FormData(formPOS);
        /*formData.append("strDate",strDate);
        formData.append("received",received);
        formData.append("strNote",strNote);
        formData.append("txtTransaction",strTransaction);*/
        btnAddPos.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAddPos.setAttribute("disabled","");
        request(base_url+"/pedidos/setOrder",formData,"post").then(function(objData){
            btnAddPos.removeAttribute("disabled");
            btnAddPos.innerHTML="Guardar";
            if(objData.status){
                location.reload();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });

    });
    searchProducts.addEventListener('input',function() {
        request(base_url+"/pedidos/searchProducts/"+searchProducts.value,"","get").then(function(objData){
            if(objData.status){
                document.querySelector("#listProducts").innerHTML = objData.data;
            }else{
                document.querySelector("#listProducts").innerHTML = objData.data;
            }
        });
    });
    searchCustomers.addEventListener('input',function() {
        if(searchCustomers.value !=""){
            request(base_url+"/pedidos/searchCustomers/"+searchCustomers.value,"","get").then(function(objData){
                if(objData.status){
                    document.querySelector("#customers").innerHTML = objData.data;
                }else{
                    document.querySelector("#customers").innerHTML = objData.data;
                }
            });
        }else{
            document.querySelector("#customers").innerHTML = "";
        }
    });
}
if(document.querySelector("#pedidos")){
    let search = document.querySelector("#search");
    let sort = document.querySelector("#sortBy");
    let element = document.querySelector("#listItem");
    
    search.addEventListener('input',function() {
        request(base_url+"/pedidos/search/"+search.value,"","get").then(function(objData){
            if(objData.status){
                element.innerHTML = objData.data;
            }else{
                element.innerHTML = objData.msg;
            }
        });
    });
    
    sort.addEventListener("change",function(){
        request(base_url+"/pedidos/sort/"+sort.value,"","get").then(function(objData){
            if(objData.status){
                element.innerHTML = objData.data;
            }else{
                element.innerHTML = objData.msg;
            }
        });
    });
    element.addEventListener("click",function(e) {
        let element = e.target;
        let id = element.getAttribute("data-id");
        if(element.name == "btnDelete"){
            deleteItem(id);
        }else if(element.name=="btnEdit"){
            editItem(id);
        }
    });
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
                let url = base_url+"/pedidos/delOrder"
                let formData = new FormData();
                formData.append("idOrder",id);
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
    function editItem(id){
        let formData = new FormData();
        formData.append("id",id);
        request(base_url+"/pedidos/getOrder",formData,"post").then(function(objData){
            let modalItem = document.querySelector("#modalItem");
            let modal= `
            <div class="modal fade" id="modalElement">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Actualizar pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table align-middle text-break">
                                <tbody id="listItem">
                                    <tr>
                                        <td><strong>Orden: </strong></td>
                                        <td>${objData.data.idorder}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cliente: </strong></td>
                                        <td>${objData.data.name}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telefono: </strong></td>
                                        <td>${objData.data.phone}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total: </strong></td>
                                        <td>${objData.data.amount}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <form id="formOrder">
                                <input type="hidden" id="idOrder" name="idOrder" value="${objData.data.idorder}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-3 mb-3">
                                            <label for="" class="form-label">Transacción <span class="text-danger">*</span></label>
                                            <input type="number" name="txtTransaction" id="txtTransaction" class="form-control" value="${objData.data.idtransaction}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-3 mb-3">
                                            <label for="" class="form-label">Fecha <span class="text-danger">*</span></label>
                                            <input type="date" name="strDate" id="txtDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 mb-3">
                                    <label for="" class="form-label">Notas</label>
                                    <textarea rows="5" name="strNote" id="txtNotePos" class="form-control">${objData.data.note}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="typeList" class="form-label">Estado de pago <span class="text-danger">*</span></label>
                                            <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                                ${objData.data.options}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="typeList" class="form-label">Estado de pedido <span class="text-danger">*</span></label>
                                            <select class="form-control" aria-label="Default select example" id="statusOrder" name="statusOrder" required>
                                                ${objData.data.statusorder}
                                            </select>
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
            let arrDate = new String(objData.data.date).split("/");
            document.querySelector("#txtDate").valueAsDate = new Date(arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0]);
            modalView.show();
            let formOrder = document.querySelector("#formOrder");
            formOrder.addEventListener("submit",function(e){
                e.preventDefault();
                let formData = new FormData(formOrder);
                let status = document.querySelector("#statusList").value;
                let strDate = document.querySelector("#txtDate").value;
                let statusOrder = document.querySelector("#statusOrder").value;
                let strTransaction = document.querySelector("#txtTransaction").value;
                if(status =="" || strDate =="" || strTransaction=="" || statusOrder ==""){
                    Swal.fire("Error","Todos los campos con (*) son obligatorios","error");
                    return false;
                }
                let btnAdd = document.querySelector("#btnAdd");
    
                btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
                btnAdd.setAttribute("disabled","");
                request(base_url+"/pedidos/updateOrder",formData,"post").then(function(objData){
                    btnAdd.removeAttribute("disabled");
                    btnAdd.innerHTML = "Actualizar"
                    if(objData.status){
                        Swal.fire("Actualizado",objData.msg,"success");
                        modalView.hide();
                        element.innerHTML = objData.data;
                    }else{
                        Swal.fire("Error",objData.msg,"error");
                    }
                });
            })
        });
    }
}

function addProduct(id=null, element){
    let formData = new FormData();
    const toastLiveExample = document.getElementById('liveToast');
    let topic = 0;
    let intQty = 1;
    if(id!=null){
        topic = 2;
    }else{
        id = 0;
        topic = 3;
        let strService = document.querySelector("#txtService").value;
        let intPrice = document.querySelector("#intPrice").value;
        intQty = document.querySelector("#intQty").value;
        formData.append("txtService",strService);
        formData.append("intPrice",intPrice);

        if(strService=="" || intPrice =="" || intQty ==""){
            Swal.fire("Error","Todos los campos son obligatorios","error");
            return false;
        }
    }
    let idProduct = id;
    formData.append("idProduct",idProduct);
    formData.append("topic",topic);
    formData.append("txtQty",intQty);

    element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    element.setAttribute("disabled","");
    request(base_url+"/pedidos/addCart",formData,"post").then(function(objData){
        element.innerHTML=`Agregar`;
        element.removeAttribute("disabled");
        document.querySelector(".toast-header img").src=objData.data.image;
        document.querySelector(".toast-header img").alt=objData.data.name;
        document.querySelector("#toastProduct").innerHTML=objData.data.name;
        document.querySelector(".toast-body").innerHTML=objData.msg;
        if(objData.status){
            document.querySelector("#total").innerHTML = objData.total;
            document.querySelector("#posProducts").innerHTML = objData.html;
            document.querySelector("#total").setAttribute("data-value",objData.value);
            statusPOS();
            updateCart();
        }

        const toast = new bootstrap.Toast(toastLiveExample);
        toast.show();
    });
}
function delProduct(element){
    let formData = new FormData();
    let topic = element.parentElement.getAttribute("data-topic");
    let id = element.parentElement.getAttribute("data-id");
    formData.append("topic",topic);
    formData.append("id",id);
    if(topic == 1){
        let photo = element.parentElement.getAttribute("data-f");
        let height = element.parentElement.getAttribute("data-h");
        let width = element.parentElement.getAttribute("data-w");
        let margin = element.parentElement.getAttribute("data-m");
        let marginColor = element.parentElement.getAttribute("data-mc");
        let borderColor = element.parentElement.getAttribute("data-bc");
        let style = element.parentElement.getAttribute("data-s");
        let type = element.parentElement.getAttribute("data-t");
        let reference = element.parentElement.getAttribute("data-r");
        formData.append("height",height);
        formData.append("width",width);
        formData.append("margin",margin);
        formData.append("margincolor",marginColor);
        formData.append("bordercolor",borderColor);
        formData.append("style",style);
        formData.append("type",type);
        formData.append("photo",photo);
        formData.append("reference",reference);
    }else if(topic==3){
        let strService = element.parentElement.getAttribute("data-name");
        let intPrice = element.parentElement.getAttribute("data-price");
        formData.append("txtService",strService);
        formData.append("intPrice",intPrice);
    }
    element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    element.setAttribute("disabled","");
    request(base_url+"/pedidos/delCart",formData,"post").then(function(objData){
        element.innerHTML=`<i class="fas fa-times"></i>`;
        element.removeAttribute("disabled");
        if(objData.status){
            document.querySelector("#total").innerHTML = objData.total;
            document.querySelector("#total").setAttribute("data-value",objData.value);
            document.querySelector("#posProducts").innerHTML = objData.html;
            element.parentElement.remove();
            statusPOS();
            updateCart();
        }
    });
}
function updateCart(){
    let decrement = document.querySelectorAll(".productDec");
    let increment = document.querySelectorAll(".productInc");
    for (let i = 0; i < increment.length; i++) {
        let minus = decrement[i];
        let plus = increment[i];
        
        minus.addEventListener("click",function(){
            let productTotal = minus.parentElement.nextElementSibling;
            let qtyProduct = minus.parentElement.parentElement.previousElementSibling.children[0].children[1].children[1].children[0];
            let qty=parseInt(qtyProduct.innerHTML);
            if(qty <=1){
                qty = 1;
            }else{
                qty--;
            }
            let id = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-id");
            let topic = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-topic");
            let formData = new FormData();
            if(topic == 1){
                let height = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-h");
                let width = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-w");
                let margin = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-m");
                let style = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-s");
                let colorMargin = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-mc");
                let colorBorder = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-bc");
                let idType = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-t");
                let reference = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-r");
                formData.append("height",height);
                formData.append("width",width);
                formData.append("margin",margin);
                formData.append("style",style);
                formData.append("colormargin",colorMargin);
                formData.append("colorborder",colorBorder);
                formData.append("idType",idType);
                formData.append("reference",reference);
            }
            
            formData.append("id",id);
            formData.append("topic",topic);
            formData.append("qty",qty);

            document.querySelector("#total").innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            productTotal.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            request(base_url+"/pedidos/updateCart",formData,"post").then(function(objData){
                if(objData.status){
                    qtyProduct.innerHTML = objData.qty;
                    productTotal.innerHTML = objData.totalprice;
                    document.querySelector("#total").innerHTML = objData.total;
                    document.querySelector("#total").setAttribute("data-value",objData.value);
                }
            });
        });
        plus.addEventListener("click",function(){
            let productTotal = minus.parentElement.nextElementSibling;
            let qtyProduct = minus.parentElement.parentElement.previousElementSibling.children[0].children[1].children[1].children[0];
            let qty=parseInt(qtyProduct.innerHTML);
            let formData = new FormData();
            
            let id = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-id");
            let topic = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-topic");
            if(topic == 1){
                let height = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-h");
                let width = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-w");
                let margin = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-m");
                let style = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-s");
                let colorMargin = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-mc");
                let colorBorder = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-bc");
                let idType = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-t");
                let reference = minus.parentElement.parentElement.parentElement.parentElement.getAttribute("data-r");

                formData.append("height",height);
                formData.append("width",width);
                formData.append("margin",margin);
                formData.append("style",style);
                formData.append("colormargin",colorMargin);
                formData.append("colorborder",colorBorder);
                formData.append("idType",idType);
                formData.append("reference",reference);
            }
            formData.append("id",id);
            formData.append("topic",topic);
            formData.append("qty",++qty);
            document.querySelector("#total").innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            productTotal.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            request(base_url+"/pedidos/updateCart",formData,"post").then(function(objData){
                if(objData.status){
                    qtyProduct.innerHTML = objData.qty;
                    productTotal.innerHTML = objData.totalprice;
                    document.querySelector("#total").innerHTML = objData.total;
                    document.querySelector("#total").setAttribute("data-value",objData.value);
                }
            });
        })
    }
}
function openModalOrder(){
    let modal = new bootstrap.Modal(document.querySelector("#modalPos"));
    moneyReceived.value = document.querySelector("#total").getAttribute("data-value");
    let total = moneyReceived.value;
    document.querySelector("#saleValue").innerHTML = "Valor de venta: "+MS+total;
    document.querySelector("#moneyBack").innerHTML = "Dinero a devolver: "+MS+0;
    modal.show();
}
function addCustom(element){
    element.setAttribute("onclick","delCustom(this)");
    element.classList.add("border","border-primary");
    document.querySelector("#selectedCustomer").appendChild(element);
    document.querySelector("#customers").innerHTML = "";
    document.querySelector("#idCustomer").value = element.getAttribute("data-id");
    searchCustomers.parentElement.classList.add("d-none");
}
function delCustom(element){
    searchCustomers.parentElement.classList.remove("d-none");
    document.querySelector("#idCustomer").value = 0;
    element.remove();
}
function statusPOS(){
    if(document.querySelector("#posProducts").children.length > 0){
        document.querySelector("#btnPos").classList.remove("d-none");
    }else{
        document.querySelector("#btnPos").classList.add("d-none");
    }
}