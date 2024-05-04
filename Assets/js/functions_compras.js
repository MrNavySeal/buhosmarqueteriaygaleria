
const tablePurchase = document.querySelector("#tablePurchase");
const tableProducts = document.querySelector("#tableProducts");
const searchProduct = document.querySelector("#searchProduct");
const modalVariant = document.querySelector("#modalVariant") ? new bootstrap.Modal(document.querySelector("#modalVariant")) :"";
const modalSelectvariants = document.querySelector("#modalSelectvariants");
const modalVariantCost = document.querySelector("#modalVariantCost");
const modalVariantName = document.querySelector("#modalVariantName");
const btnAdd = document.querySelector("#btnAdd");
let product;
let arrProducts = [];
window.addEventListener("load",function(){
    getProducts();
});

btnAdd.addEventListener("click",function(){
    addProduct(product);
    modalVariant.hide();
});
searchProduct.addEventListener("input",function(){
    getProducts(searchProduct.value);
});

function getProducts(search=""){
    const formData = new FormData();
    formData.append("search",search);
    request(base_url+"/compras/getProducts",formData,"post").then(function(res){
        tableProducts.innerHTML = res;
    });
}
function getProduct(element,id){
    const formData = new FormData();
    formData.append("id",id);
    element.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;  
    element.setAttribute("disabled","");
    request(base_url+"/compras/getProduct",formData,"post").then(function(res){
        element.innerHTML='<i class="fas fa-plus"></i>';
        element.removeAttribute("disabled","");
        if(res.status){
            product = res.data;
            if(product.product_type){
                displayVariants(product);
            }else{
                addProduct(product)
            }
        }else{
            Swal.fire("Error",res.msg,"error");
        }

    });
}
function addProduct(product){
    tablePurchase.innerHTML ="";
    let obj = {
        "id":product.idproduct,
        "is_stock":product.is_stock,
        "stock":product.stock,
        "qty":1,
        "price_purchase":product.price_purchase,
        "price_sell":product.price,
        "reference":product.reference,
        "product_type":product.product_type,
        "name":product.name,
        "import":product.import,
        "variant_name":""
    };
    if(product.product_type == 1){
        obj.variant_name = product.variant_name;
    }
    if(arrProducts.length > 0){
        let flag = false;
        for (let i = 0; i < arrProducts.length; i++) {
            if(arrProducts[i].product_type){
                if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference
                    && arrProducts[i].name == obj.name && arrProducts[i].variant_name == obj.variant_name
                 ){
                    arrProducts[i].qty +=obj.qty 
                    flag = false;
                    break;
                 }
            }else if(arrProducts[i].id == obj.id && arrProducts[i].reference == obj.reference && arrProducts[i].name == obj.name){
                    arrProducts[i].qty +=obj.qty
                    flag = false;
                    break;
            }
            flag = true;
        }
        if(flag){
            arrProducts.push(obj);
        }
    }else{
        arrProducts.push(obj);
    }
    arrProducts.forEach(pro=>{
        let iva = 1+(pro.import/100);
        let purchase = (Math.ceil((pro.price_purchase*iva)/100))*100;
        let productTotal = pro.qty * purchase;
        let tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${pro.is_stock ? pro.stock : "N/A"}</td>
            <td>
                <p class="m-0 mb-1">${pro.name}</p>
                <p class="text-secondary m-0 mb-1">${pro.reference}</p>
                <p class="text-secondary m-0 mb-1">${pro.variant_name}</p>
            </td>
            <td>${pro.qty}</td>
            <td><input class="form-control" value="${pro.price_purchase}" type="number"></td>
            <td>${pro.import}</td>
            <td><input class="form-control" value="${purchase}" type="number"></td>
            <td><input class="form-control" value="${pro.price_sell}" type="number"></td>
            <td><input class="form-control" value="" type="number"></td>
            <td>${formatNum(productTotal,".")}</td>
            <td><button class="btn btn-danger m-1 text-white" type="button"><i class="fas fa-trash-alt"></i></button>'</td>
        `;
        tablePurchase.appendChild(tr);
    });
    console.log(arrProducts);
} 
function displayVariants(data){
    const variants = data.variation.variation;
    const option = data.options;
    modalSelectvariants.innerHTML ="";
    for (let i = 0; i < variants.length; i++) {
        let html="";
        let options = variants[i].options;
        let div = document.createElement("div");
        div.classList.add("mb-3");
        for (let j = 0; j < options.length; j++) {
            $active = j==0? "btn-primary" : "btn-secondary";
            html+=`<button type="button" class="btn ${$active} m-1 btnVariant" onclick="selectVariant(this)" data-name="${options[j]}">${options[j]}</button>`;
        }
        div.innerHTML = `
        <p class="t-color-3 m-0">${variants[i].name}</p>
        <div class="flex">${html}</div>
        `;
        modalSelectvariants.appendChild(div);
    }
    modalVariantCost.innerHTML = "Costo: "+option[0].format_purchase;
    modalVariantName.innerHTML = data.reference!="" ? data.reference+" "+data.name : data.name;
    let selectedVariants = document.querySelectorAll(".btn-primary.btnVariant");
    let arrSelected = [];
    selectedVariants.forEach(element => {
        arrSelected.push(element.getAttribute("data-name"));
    });
    //Agrego al producto la variante escogida por defecto
    let variant = arrSelected.join("-");
    let selectedOption = data.options.filter(op=>op.name == variant)[0];
    product['variant_name'] = variant;
    product['price'] = selectedOption.price_sell;
    product['price_purchase'] = selectedOption.price_purchase;
    product['stock'] = selectedOption.stock;
    openModal("variant");
}
function selectVariant(element){
    let options = product.options;
    let contentVariants = element.parentElement;
    let variants = contentVariants.children;
    for (let i = 0; i < variants.length; i++) {
        variants[i].classList.replace("btn-primary","btn-secondary");
    }
    element.classList.replace("btn-secondary","btn-primary");
    let selectedVariants = document.querySelectorAll(".btn-primary.btnVariant");
    let arrSelected = [];
    selectedVariants.forEach(element => {
        arrSelected.push(element.getAttribute("data-name"));
    });
    //Agrego al producto la variante escogida
    let variant = arrSelected.join("-");
    let selectedOption = options.filter(op=>op.name == variant)[0];
    modalVariantCost.innerHTML = "Costo: "+selectedOption.format_purchase;
    product['variant_name'] = variant;
    product['price'] = selectedOption.price_sell;
    product['price_purchase'] = selectedOption.price_purchase;
    product['stock'] = selectedOption.stock;
}
function openModal(option){
    if(option == "variant"){
        modalVariant.show();
    }
}