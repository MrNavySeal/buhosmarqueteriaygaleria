//btnSearch.classList.add("d-none");
//document.querySelector(".nav-icons-qty").classList.add("d-none");
let decrement;
let increment;
let inputs;

window.addEventListener("load",function(){
    document.querySelector("#btnCart").classList.add("d-none");
    if(document.querySelectorAll(".table-cart .btn-del")){
        
        //updateCart();
        delCart(document.querySelectorAll(".btn-del-cart"))
        /*let btns = document.querySelectorAll(".table-cart .btn-del");
        for (let i = 0; i < btns.length; i++) {
            let btn = btns[i];
            btn.addEventListener("click",function(){
                let idProduct = inputs[i].getAttribute("data-id");
                let formData = new FormData();
                formData.append("idProduct",idProduct);
                request(base_url+"/carrito/delCart",formData,"post").then(function(objData){
                    if(objData.status){
                        window.location.reload();
                    }
                });
                
            })
        }*/
    }
    let urlSearch = window.location.search;
    let params = new URLSearchParams(urlSearch);
    let boolCheck = document.querySelector("#boolCheck");
    let cupon = "";
    let url="";
    if(params.get("cupon")){
        cupon = "cupon="+params.get("cupon");
    }
    if(params.get("situ")){
        let situ = params.get("situ");
        if(situ == "true"){
            boolCheck.setAttribute("checked","");
        }else{
            boolCheck.removeAttribute("checked");
        }
    }
    boolCheck.addEventListener("input",function(){
        if(cupon!=""){
            window.location.href=base_url+"/carrito?"+cupon+"&situ="+boolCheck.checked;
        }else{
            window.location.href=base_url+"/carrito?situ="+boolCheck.checked;
        }
    })
});

if(document.querySelector("#selectCity")){
    let select = document.querySelector("#selectCity");
    let urlSearch = window.location.search;
    let params = new URLSearchParams(urlSearch);
    let cupon = "";
    if(params.get("cupon")){
        cupon = params.get("cupon");
    }
    select.addEventListener("change",function(){
        let formData = new FormData();
        formData.append("city",select.value);
        formData.append("cupon",cupon);
        if(select.value == 0){
            return false;
        }
        request(base_url+"/carrito/calculateShippingCity",formData,"post").then(function(objData){
            document.querySelector("#subtotal").innerHTML = objData.subtotal;
            document.querySelector("#totalProducts").innerHTML = objData.total;
            if(document.querySelector("#cuponTotal")){
                document.querySelector("#cuponTotal").innerHTML = objData.cupon;
            }
        });
    });
}
if(document.querySelector("#btnCoupon")){
    let btnCoupon = document.querySelector("#btnCoupon");
    let formCoupon = document.querySelector("#formCoupon");
    formCoupon.addEventListener("submit",function(e){
        e.preventDefault();
        let strCoupon = document.querySelector("#txtCoupon").value;
        if(strCoupon ==""){
            alertCoupon.innerHTML="Por favor, ingresa el cupón.";
            alertCoupon.classList.remove("d-none");
            return false;
        }
        btnCoupon.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnCoupon.setAttribute("disabled","");

        let formData = new FormData(formCoupon);
        request(base_url+"/carrito/setCouponCode",formData,"post").then(function(objData){
            btnCoupon.innerHTML=`+`;
            btnCoupon.removeAttribute("disabled");
            if(objData.status){
                let urlSearch = window.location.search;
                let params = new URLSearchParams(urlSearch);
                let situ ="";
                if(params.get("situ")){
                    situ="&situ="+params.get("situ");
                }
                window.location.href = base_url+"/carrito?cupon="+objData.data.code+situ;
            }else{
                alertCoupon.innerHTML=objData.msg;
                alertCoupon.classList.remove("d-none");
            }
        });
    })
}
function cartIncrement(element){
    let parent = element.parentElement.parentElement.parentElement.parentElement;
    let id = parent.getAttribute("data-id");
    let topic = parent.getAttribute("data-topic");
    let variant = parent.getAttribute("data-variant") ? parent.getAttribute("data-variant") : null ;
    let urlSearch = window.location.search;
    let params = new URLSearchParams(urlSearch);
    let cupon = "";
    let situ = "";
    if(params.get("cupon")){
        cupon = params.get("cupon");
    }
    if(params.get("situ")){
        situ = params.get("situ");
    }
    let city = "";
    if(document.querySelector("#selectCity")){
        city = document.querySelector("#selectCity").value;
    }
    let input = element.previousElementSibling;
    input.value++;
    let qty = input.value;
    let formData = new FormData();
    formData.append("id",id);
    formData.append("topic",topic);
    formData.append("qty",qty);
    formData.append("cupon",cupon);
    formData.append("city",city);
    formData.append("variant",variant);
    formData.append("situ",situ);
    if(topic == 1){
        let height = parent.getAttribute("data-h");
        let width = parent.getAttribute("data-w");
        let margin = parent.getAttribute("data-m");
        let style = parent.getAttribute("data-s");
        let colorMargin = parent.getAttribute("data-mc");
        let colorBorder = parent.getAttribute("data-bc");
        let idType = parent.getAttribute("data-t");
        let reference = parent.getAttribute("data-r");
        let frameColor = parent.getAttribute("data-fc");
        let material = parent.getAttribute("data-material");
        let glass = parent.getAttribute("data-glass");
        formData.append("height",height);
        formData.append("width",width);
        formData.append("margin",margin);
        formData.append("style",style);
        formData.append("colormargin",colorMargin);
        formData.append("colorborder",colorBorder);
        formData.append("idType",idType);
        formData.append("reference",reference);
        formData.append("framecolor",frameColor);
        formData.append("glass",glass);
        formData.append("material",material);
    }
    if(document.querySelector("#cuponTotal")){
        document.querySelector("#cuponTotal").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;  
    }
    parent.children[4].innerHTML =`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    document.querySelector("#totalProducts").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    document.querySelector("#subtotal").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/carrito/updateCart",formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#subtotal").innerHTML = objData.subtotal;
            document.querySelector("#totalProducts").innerHTML = objData.total;
            parent.children[4].innerHTML = objData.totalPrice;
            input.value = objData.qty;
            if(document.querySelector("#cuponTotal")){
                document.querySelector("#cuponTotal").innerHTML = objData.cupon;
            }
        }
    });
}
function cartDecrement(element){
    let parent = element.parentElement.parentElement.parentElement.parentElement;
    let id = parent.getAttribute("data-id");
    let topic = parent.getAttribute("data-topic");
    let variant = parent.getAttribute("data-variant") ? parent.getAttribute("data-variant") : null ;
    let urlSearch = window.location.search;
    let params = new URLSearchParams(urlSearch);
    let cupon = "";
    let situ = "";
    if(params.get("situ")){
        situ = params.get("situ");
    }
    if(params.get("cupon")){
        cupon = params.get("cupon");
    }
    let city = "";
    if(document.querySelector("#selectCity")){
        city = document.querySelector("#selectCity").value;
    }
    let input = element.nextElementSibling;
    if(input.value<=1){
        input.value=1;
    }else{
        input.value--;
    }
    let qty = input.value;
    let formData = new FormData();
    formData.append("id",id);
    formData.append("topic",topic);
    formData.append("qty",qty);
    formData.append("cupon",cupon);
    formData.append("city",city);
    formData.append("variant",variant);
    formData.append("situ",situ);
    if(topic == 1){
        let height = parent.getAttribute("data-h");
        let width = parent.getAttribute("data-w");
        let margin = parent.getAttribute("data-m");
        let style = parent.getAttribute("data-s");
        let colorMargin = parent.getAttribute("data-mc");
        let colorBorder = parent.getAttribute("data-bc");
        let idType = parent.getAttribute("data-t");
        let reference = parent.getAttribute("data-r");
        let frameColor = parent.getAttribute("data-fc");
        let material = parent.getAttribute("data-material");
        let glass = parent.getAttribute("data-glass");
        formData.append("height",height);
        formData.append("width",width);
        formData.append("margin",margin);
        formData.append("style",style);
        formData.append("colormargin",colorMargin);
        formData.append("colorborder",colorBorder);
        formData.append("idType",idType);
        formData.append("reference",reference);
        formData.append("framecolor",frameColor);
        formData.append("glass",glass);
        formData.append("material",material);
    }
    if(document.querySelector("#cuponTotal")){
        document.querySelector("#cuponTotal").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;  
    }
    parent.children[4].innerHTML =`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    document.querySelector("#totalProducts").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    document.querySelector("#subtotal").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/carrito/updateCart",formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#subtotal").innerHTML = objData.subtotal;
            document.querySelector("#totalProducts").innerHTML = objData.total;
            parent.children[4].innerHTML = objData.totalPrice;
            input.value = objData.qty;
            if(document.querySelector("#cuponTotal")){
                document.querySelector("#cuponTotal").innerHTML = objData.cupon;
            }
        }
    });
}
function cartInput(input){
    let parent = input.parentElement.parentElement.parentElement.parentElement;
    let id = parent.getAttribute("data-id");
    let topic = parent.getAttribute("data-topic");
    let variant = parent.getAttribute("data-variant") ? parent.getAttribute("data-variant") : null ;
    let urlSearch = window.location.search;
    let params = new URLSearchParams(urlSearch);
    let cupon = "";
    let situ = "";
    if(params.get("situ")){
        situ = params.get("situ");
    }
    if(input.value == "" || input.value == 0 || input.value < 0){
        input.value = 1
    }

    if(params.get("cupon")){
        cupon = params.get("cupon");
    }
    let city = "";
    if(document.querySelector("#selectCity")){
        city = document.querySelector("#selectCity").value;
    }
    let qty = input.value;
    let formData = new FormData();
    formData.append("id",id);
    formData.append("topic",topic);
    formData.append("qty",qty);
    formData.append("cupon",cupon);
    formData.append("city",city);
    formData.append("variant",variant);
    formData.append("situ",situ);
    if(topic == 1){
        let height = parent.getAttribute("data-h");
        let width = parent.getAttribute("data-w");
        let margin = parent.getAttribute("data-m");
        let style = parent.getAttribute("data-s");
        let colorMargin = parent.getAttribute("data-mc");
        let colorBorder = parent.getAttribute("data-bc");
        let idType = parent.getAttribute("data-t");
        let reference = parent.getAttribute("data-r");
        let frameColor = parent.getAttribute("data-fc");
        let material = parent.getAttribute("data-material");
        let glass = parent.getAttribute("data-glass");
        formData.append("height",height);
        formData.append("width",width);
        formData.append("margin",margin);
        formData.append("style",style);
        formData.append("colormargin",colorMargin);
        formData.append("colorborder",colorBorder);
        formData.append("idType",idType);
        formData.append("reference",reference);
        formData.append("framecolor",frameColor);
        formData.append("glass",glass);
        formData.append("material",material);
    }
    if(document.querySelector("#cuponTotal")){
        document.querySelector("#cuponTotal").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;  
    }
    parent.children[4].innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    document.querySelector("#totalProducts").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    document.querySelector("#subtotal").innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/carrito/updateCart",formData,"post").then(function(objData){
        if(objData.status){
            document.querySelector("#subtotal").innerHTML = objData.subtotal;
            document.querySelector("#totalProducts").innerHTML = objData.total;
            parent.children[4].innerHTML = objData.totalPrice;
            input.value = objData.qty;
            if(document.querySelector("#cuponTotal")){
                document.querySelector("#cuponTotal").innerHTML = objData.cupon;
            }
        }
    });
}
function delCart(elements){
    for (let i = 0; i < elements.length; i++) {
        let element = elements[i];
        element.addEventListener("click",function(){
            let urlSearch = window.location.search;
            let params = new URLSearchParams(urlSearch);
            let cupon = "";
            let situ="";
            let data = element.parentElement.parentElement.parentElement;
            let formData = new FormData();
            let variant = data.getAttribute("data-variant") ? data.getAttribute("data-variant") : null ;
            let topic = data.getAttribute("data-topic");
            let id = data.getAttribute("data-id");
            let city="";
            if(params.get("situ")){
                situ = params.get("situ");
            }
            if(params.get("cupon")){
                cupon = params.get("cupon");
            }
            if(document.querySelector("#selectCity")){
                city = document.querySelector("#selectCity").value;
            }
            formData.append("variant",variant);
            formData.append("topic",topic);
            formData.append("id",id);
            formData.append("situ",situ);
            formData.append("cupon",cupon);
            formData.append("city",city);
            if(topic == 1){
                let photo = data.getAttribute("data-f");
                let height = data.getAttribute("data-h");
                let width = data.getAttribute("data-w");
                let margin = data.getAttribute("data-m");
                let marginColor = data.getAttribute("data-mc");
                let borderColor = data.getAttribute("data-bc");
                let style = data.getAttribute("data-s");
                let type = data.getAttribute("data-t");
                let reference = data.getAttribute("data-r");
                let frameColor = data.getAttribute("data-fc");
                let material = data.getAttribute("data-material");
                let glass = data.getAttribute("data-glass");
                formData.append("height",height);
                formData.append("width",width);
                formData.append("margin",margin);
                formData.append("margincolor",marginColor);
                formData.append("bordercolor",borderColor);
                formData.append("style",style);
                formData.append("type",type);
                formData.append("photo",photo);
                formData.append("reference",reference);
                formData.append("framecolor",frameColor);
                formData.append("glass",glass);
                formData.append("material",material);
        
            }
            element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            element.setAttribute("disabled","");
            request(base_url+"/carrito/delCart",formData,"post").then(function(objData){
                element.innerHTML=`<i class="fas fa-times"></i>`;
                element.removeAttribute("disabled");
                if(objData.status){
                    document.querySelector("#subtotal").innerHTML = objData.subtotal;
                    document.querySelector("#totalProducts").innerHTML = objData.total;
                    data.remove();
                    if(params.get("cupon")){
                        cupon = "?cupon="+params.get("cupon");
                    }
                    if(params.get("situ") && !params.get("cupon")){
                        situ = "?situ="+params.get("situ");
                    }else{
                        situ = "&situ="+params.get("situ");
                    }
                    if(document.querySelector("#cuponTotal")){
                        document.querySelector("#cuponTotal").innerHTML = objData.cupon;
                    }
                    //window.location.href=base_url+"/carrito"+cupon+situ;
                    //updateCart();
                }
            });
        });
    }
}
