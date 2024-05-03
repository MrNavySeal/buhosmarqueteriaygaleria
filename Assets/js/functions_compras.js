
const tableProducts = document.querySelector("#tableProducts");
const searchProduct = document.querySelector("#searchProduct");
const modalVariant = document.querySelector("#modalVariant") ? new bootstrap.Modal(document.querySelector("#modalVariant")) :"";
const modalSelectvariants = document.querySelector("#modalSelectvariants");
const modalVariantCost = document.querySelector("#modalVariantCost");
const modalVariantName = document.querySelector("#modalVariantName");
window.addEventListener("load",function(){
    getProducts();
});

searchProduct.addEventListener("input",function(){
    getProducts(searchProduct.value);
})
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
            const data = res.data;
            console.log(data);
            if(data.product_type){
                displayVariants(data);
            }else{
                addProduct(id)
            }
        }else{
            Swal.fire("Error",res.msg,"error");
        }

    });
}
function addProduct(id){
    console.log("agregado "+id);
} 
function displayVariants(data){
    const variants = data.variation.variation;
    modalSelectvariants.innerHTML ="";
    for (let i = 0; i < variants.length; i++) {
        let html="";
        let options = variants[i].options;
        let div = document.createElement("div");
        div.classList.add("mb-3");
        for (let j = 0; j < options.length; j++) {
            html+=`<button type="button" class="btn btn-secondary m-1" onclick="selectVariant(this)" data-name="${options[j]}">${options[j]}</button>`;
        }
        div.innerHTML = `
        <p class="t-color-3 m-0">${variants[i].name}</p>
        <div class="flex">${html}</div>
        `;
        modalSelectvariants.appendChild(div);
    }
    modalVariantName.innerHTML = data.reference!="" ? data.reference+" "+data.name : data.name;
    openModal("variant");
}
function openModal(option){
    if(option == "variant"){
        modalVariant.show();
    }
}