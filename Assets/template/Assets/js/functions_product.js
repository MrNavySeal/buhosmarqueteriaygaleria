let productImages = document.querySelectorAll(".product-image-item");
let btnPrevP = document.querySelector(".slider-btn-left");
let btnNextP = document.querySelector(".slider-btn-right");
let innerP = document.querySelector(".product-image-inner");
let btnReview = document.querySelector("#btnReview");
let modal = new bootstrap.Modal(document.querySelector("#modalReview"));

/***************************Product Page Events****************************** */
window.addEventListener("load",function(){
    rateProduct();
    if(document.querySelector("#showMore")){
        showMore(document.querySelectorAll(".comment-block"),4,document.querySelector("#showMore"));
    }
})
//Select image
for (let i = 0; i < productImages.length; i++) {
    let productImage = productImages[i];
    productImage.addEventListener("click",function(e){
        for (let j = 0; j < productImages.length; j++) {
            productImages[j].classList.remove("active");
        }
        productImage.classList.add("active");
        let image = productImage.children[0].src;
        document.querySelector(".product-image img").src = image;
    })
}
if(document.querySelector("#sortReviews")){
    let sortReview = document.querySelector("#sortReviews");
    sortReview.addEventListener("change",function(){
        let idProduct = document.querySelector("#idProduct").value;
        let intSort = sortReview.value;
        let formData = new FormData();
    
        formData.append("id",idProduct);
        formData.append("sort",intSort);
        request(base_url+"/tienda/sortReviews",formData,"post").then(function(objData){
            document.querySelector(".comment-list").innerHTML= objData;
        });
    });
}
btnPrevP.addEventListener("click",function(){
    innerP.scrollBy(-100,0);
})
btnNextP.addEventListener("click",function(){
    innerP.scrollBy(100,0);
});
btnReview.addEventListener("click",function(){
    modal.show();
})

let btnPPlus = document.querySelector("#btnPIncrement");
let btnPMinus = document.querySelector("#btnPDecrement");
let intPQty = document.querySelector("#txtQty");

if(document.querySelector("#btnPIncrement")){
    btnPPlus.addEventListener("click",function(){
        let maxStock = parseInt(intPQty.getAttribute("max"));
        if(intPQty.value >=maxStock){
            intPQty.value = maxStock;
        }else{
            intPQty.value++; 
        }
    });
    btnPMinus.addEventListener("click",function(){
        if(intPQty.value <=1){
            intPQty.value = 1;
        }else{
            --intPQty.value; 
        }
    });
    intPQty.addEventListener("input",function(){
        let maxStock = parseInt(intPQty.getAttribute("max"));
        if(intPQty.value >= maxStock){
            intPQty.value= maxStock;
        }else if(intPQty.value <= 1){
            intPQty.value= 1;
        }
    });
}

formReview.addEventListener("submit",function(e){
    e.preventDefault();
    let formData = new FormData(formReview);
    let intRate = document.querySelector("#intRate").value;
    let strReview = document.querySelector("#txtReview").value;
    let addReview = document.querySelector("#addReview");
    if(intRate ==0 || strReview ==""){
        Swal.fire("Error","Por favor, califique el producto y escriba su opinión","error");
        return false;
    }
    addReview.setAttribute("disabled","disabled");
    addReview.innerHTML = `<span class="spinner-border text-primary spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/tienda/setReview",formData,"post").then(function(objData){
        addReview.removeAttribute("disabled");
        addReview.innerHTML="Publicar";
        if(objData.status){
            document.querySelector("#intRate").value="";
            document.querySelector("#txtReview").value="";
            Swal.fire("¡Gracias por compartir su opinión!",objData.msg,"success");
            modal.hide();
        }else if(objData.login == false){
            openLoginModal();
            modal.hide();
        }else{
            Swal.fire("Error",objData.msg,"error");
            modal.hide();
        }
    });
});
function rateProduct(){
    let stars = document.querySelectorAll(".starBtn");
    for (let i = 0; i < stars.length; i++) {
        let star = stars[i];
        star.addEventListener("click",function(){
            document.querySelector("#intRate").value = i+1;
            for (let j = 0; j < stars.length; j++) {
                if(j>i){
                    stars[j].innerHTML =`<i class="far fa-star"></i>`;
                }else{
                    stars[j].innerHTML =`<i class="fas fa-star"></i>`;
                }
            }
        })
    }
}
function showMore(elements,max=null,handler){
    let currentElement = 0;
    
    if(max!=null){
        if(elements.length >= max){
            handler.classList.remove("d-none");
            for (let i = max; i < elements.length; i++) {
                elements[i].classList.add("d-none");
            }
        }
    }
    handler.addEventListener("click",function(){
        currentElement+=max;
        for (let i = currentElement; i < currentElement+max; i++) {
            if(elements[i]){
                elements[i].classList.remove("d-none");
            }
            if(i >= elements.length){
                document.querySelector("#showMore").classList.add("d-none");
            }
        }
        
    })
}
function selVariant(element){
    let variants = document.querySelectorAll(".btnv");
    for (let i = 0; i < variants.length; i++) {
        variants[i].classList.remove("active");
    }
    
    let formData = new FormData();
    formData.append("id_product",element.getAttribute("data-id"));
    formData.append("id_variant",element.getAttribute("data-idv"));
    if(!element.classList.contains("active")){
        element.disabled = true;
        request(base_url+"/tienda/getProductVariant",formData,"post").then(function(objData){
            if(objData.status){
                element.classList.add("active");
                let priceElement = document.querySelector("#productPrice");
                document.querySelector("#txtQty").setAttribute("max",objData.stock);
                document.querySelector("#txtQty").value=1;
                //document.querySelector("#productStock").innerHTML = `Stock: (${objData.stock}) unidades`
                if(priceElement.children.length>1){
                    priceElement.children[0].innerHTML = objData.pricediscount;
                    priceElement.children[1].innerHTML = objData.price;
                }else{
                    priceElement.innerHTML = objData.price;
                }
            }
        }).finally(function() {
            element.disabled = false;
        });
    }
}
