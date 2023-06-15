if(document.querySelectorAll(".btn-del")){
    
    let btns = document.querySelectorAll(".btn-del");
    for (let i = 0; i < btns.length; i++) {
        let btn = btns[i];
        btn.addEventListener("click",function(){
            let idProduct = btn.parentElement.parentElement.parentElement.getAttribute("data-id");
            let formData = new FormData();
            formData.append("idProduct",idProduct);
            request(base_url+"/tienda/delWishList",formData,"post").then(function(objData){
                if(objData.status){
                    btn.parentElement.parentElement.parentElement.remove();
                }
            });
        })
    }
}
