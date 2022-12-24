let productImages = document.querySelectorAll(".product-image-item");
let btnPrevP = document.querySelector(".slider-btn-left");
let btnNextP = document.querySelector(".slider-btn-right");
let innerP = document.querySelector(".product-image-inner");

/***************************Product Page Events****************************** */

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
btnPrevP.addEventListener("click",function(){
    innerP.scrollBy(-100,0);
})
btnNextP.addEventListener("click",function(){
    innerP.scrollBy(100,0);
});
if(document.querySelector("#btnPqty")){
    let btnPPlus = document.querySelector("#btnPIncrement");
    let btnPMinus = document.querySelector("#btnPDecrement");
    let intPQty = document.querySelector("#txtQty");
    let maxStock = parseInt(intPQty.getAttribute("max"));

    btnPPlus.addEventListener("click",function(){
        if(intPQty.value >=maxStock){
            intPQty.value = maxStock;
        }else{
            ++intPQty.value; 
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
        if(intPQty.value >= maxStock){
            intPQty.value= maxStock;
        }else if(intPQty.value <= 1){
            intPQty.value= 1;
        }
    });
}

