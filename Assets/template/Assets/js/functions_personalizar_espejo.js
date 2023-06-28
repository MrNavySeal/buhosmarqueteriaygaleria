const DIMENSIONDEFAULT = 4;
const MAXDIMENSION = 200;
const rangeZoom = document.querySelector("#zoomRange");
const minusZoom = document.querySelector("#zoomMinus");
const plusZoom = document.querySelector("#zoomPlus");
const intHeight = document.querySelector("#intHeight");
const intWidth = document.querySelector("#intWidth");
const layoutImg = document.querySelector(".layout--img");
const layoutMargin = document.querySelector(".layout--margin");
const layoutBorder = document.querySelector(".layout--border");
const sliderLeft = document.querySelector(".slider--control-left");
const sliderRight = document.querySelector(".slider--control-right");
const sliderInner = document.querySelector(".slider--inner");
const colorFrame = document.querySelectorAll(".color--frame");
const selectStyle = document.querySelector("#selectStyle");
const optionsCustom = document.querySelectorAll(".option--custom");
const btnBack = document.querySelector("#btnBack");
const btnNext = document.querySelector("#btnNext");
const pages = document.querySelectorAll(".page");
const containerFrames = document.querySelector(".select--frames");
const searchFrame = document.querySelector("#searchFrame");
const sortFrame = document.querySelector("#sortFrame");
const addFrame = document.querySelector("#addFrame");
const toastLiveExample = document.getElementById('liveToast');
const closeImage = document.querySelector("#closeImg");
const framePhotos = document.querySelector("#framePhotos");
const changeImgL = document.querySelectorAll(".change__img")[0];
const changeImgR = document.querySelectorAll(".change__img")[1];
let innerP = document.querySelector(".product-image-inner");
let btnPrevP = document.querySelector(".slider-btn-left");
let btnNextP = document.querySelector(".slider-btn-right");
let indexImg = 0;
let page = 0;

window.addEventListener("load",function(){
    selectColorFrame();
    layoutImg.style.border = "none";
    layoutImg.style.background ="linear-gradient(transparent, rgba(0, 0, 0, 0.2))";
})
//----------------------------------------------
//[Change Pages]
closeImage.addEventListener("click",function(){
    framePhotos.classList.add("d-none");
});
btnPrevP.addEventListener("click",function(){
    innerP.scrollBy(-100,0);
})
btnNextP.addEventListener("click",function(){
    innerP.scrollBy(100,0);
});
btnNext.addEventListener("click",function(){
    for (let i = 0; i < pages.length; i++) {
        pages[i].classList.add("d-none");
    }
    page++;
    if(page == pages.length-1){
        btnNext.classList.add("d-none");
        btnBack.classList.remove("d-none");
    }else{
        btnBack.classList.add("d-none");
        btnNext.classList.remove("d-none");
    }
    if(page>0){
        btnBack.classList.remove("d-none");
    }
    pages[page].classList.remove("d-none");
});
btnBack.addEventListener("click",function(){
    for (let i = 0; i < pages.length; i++) {
        pages[i].classList.add("d-none");
    }
    page--;
    if(page == pages.length-1){
        btnNext.classList.add("d-none");
        btnBack.classList.remove("d-none");
    }else{
        btnBack.classList.add("d-none");
        btnNext.classList.remove("d-none");
    }
    if(page>0){
        btnBack.classList.remove("d-none");
    }
    pages[page].classList.remove("d-none");
});
changeImgL.addEventListener("click",function(){
    let divImg = document.querySelector(".frame__img__container img");
    let images = document.querySelectorAll(".product-image-item");
    
    --indexImg;
    if(indexImg < 0){
        indexImg = images.length-1;
    }
    let url = images[indexImg].children[0].getAttribute("src");
    divImg.setAttribute("src",url);
});
changeImgR.addEventListener("click",function(){
    let divImg = document.querySelector(".frame__img__container img");
    let images = document.querySelectorAll(".product-image-item");
    
    ++indexImg;
    if(indexImg >= images.length){
        indexImg = 0;
    }
    let url = images[indexImg].children[0].getAttribute("src");
    divImg.setAttribute("src",url);
});
//----------------------------------------------
//[Dimensions]
intHeight.addEventListener("change",function(){
    if(intHeight.value <= 10.0){
        intHeight.value = 10.0;
    }else if(intHeight.value >= MAXDIMENSION){
        intHeight.value = MAXDIMENSION;
    }
    calcularMarco();
    resizeFrame(intWidth.value, intHeight.value);
    filterProducts();
});
intWidth.addEventListener("change",function(){
    if(intWidth.value <= 10.0){
        intWidth.value = 10.0;
    }else if(intWidth.value >= MAXDIMENSION){
        intWidth.value = MAXDIMENSION;
    }
    calcularMarco();
    resizeFrame(intWidth.value, intHeight.value);
    filterProducts();
});
//----------------------------------------------
//[Zoom events]
rangeZoom.addEventListener("input",function(){
    layoutMargin.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutImg.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutBorder.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
}); 
minusZoom.addEventListener("click",function(){
    rangeZoom.value = parseInt(rangeZoom.value)-10;
    layoutMargin.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutImg.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutBorder.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
});
plusZoom.addEventListener("click",function(){
    rangeZoom.value = parseInt(rangeZoom.value)+10;
    layoutMargin.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutImg.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutBorder.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
});

//----------------------------------------------
//[Slider controls]
/*sliderLeft.addEventListener("click",function(){
    sliderInner.scrollBy(-100,0);
});
sliderRight.addEventListener("click",function(){
    sliderInner.scrollBy(100,0);
});*/

//----------------------------------------------
//[Frame custom]

searchFrame.addEventListener('input',function() {
    const valorBusqueda = this.value.toLowerCase();
    const lista = document.querySelectorAll(".frame--container");
    for (let i = 0; i < lista.length; i++) {
      const textoElemento = lista[i].getAttribute("data-r").toLowerCase();

      if (textoElemento.includes(valorBusqueda)) {
        lista[i].style.display = "block";
      } else {
        lista[i].style.display = "none";
      }
    }
});

sortFrame.addEventListener("change",function(){
    if(intWidth.value !="" && intHeight.value!=""){
        if(sortFrame.value == 1){
            document.querySelector("#spcFrameMaterial").innerHTML = "Madera";
            document.querySelector("#frame--color").classList.remove("d-none");
        }else if(sortFrame.value == 3){
            document.querySelector("#spcFrameMaterial").innerHTML = "Madera";
            layoutBorder.style.outlineColor="transparent";
            document.querySelector("#frame--color").classList.add("d-none");
        }else{
            document.querySelector("#spcFrameMaterial").innerHTML = "Poliestireno";
            document.querySelector("#spcFrameColor").innerHTML = "N/A";
            layoutBorder.style.outlineColor="transparent";
            document.querySelector("#frame--color").classList.add("d-none");
        }
        let formData = new FormData();
        formData.append("height",intHeight.value);
        formData.append("width",intWidth.value);
        formData.append("search",searchFrame.value);
        formData.append("sort",sortFrame.value);
        containerFrames.innerHTML=`
            <div class="text-center p-5">
                <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        request(base_url+"/enmarcar/sort",formData,"post").then(function(objData){
            if(objData.status){
                containerFrames.innerHTML = objData.data;
            }else{
                containerFrames.innerHTML = `<p class="fw-bold text-center">${objData.data}</p>`;
            }
        });
    }
});

containerFrames.addEventListener("click",function(e){
    let id = e.target.parentElement.getAttribute("data-id");
    calcularMarco(id);
    
});
//[Select style]
selectStyle.addEventListener("change",function(){
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        selectStyle.value = 1;
        return false;
    }
    if(selectStyle.value == 1){
        layoutImg.style.border = "none";
        layoutImg.style.background ="linear-gradient(transparent, rgba(0, 0, 0, 0.2))";
    }else{
        layoutImg.style.border = "12px solid rgba(0, 0, 0, 0.3)";
        layoutImg.style.background ="linear-gradient(transparent, rgba(0, 0, 0, 0.2))";
    }

    document.querySelector("#spcStyle").innerHTML = selectStyle.options[selectStyle.selectedIndex].text;
    //resizeFrame(intWidth.value, intHeight.value);
    calcularMarco();
});
//----------------------------------------------
//[Add frame]
addFrame.addEventListener("click",function(){
    let formData = new FormData();

    if(intHeight.value =="" || intWidth.value==""){
        Swal.fire("Error","Por favor, ingresa las medidas","error");
        return false;
    }
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        return false;
    }
    if(sortFrame.value == 1){
        if(!document.querySelector(".color--frame.element--active")){
            Swal.fire("Error","Por favor, elige el color del marco","error");
            return false;
        }
    }
    let margin = 0;
    let styleFrame = selectStyle.value;
    let height = intHeight.value;
    let width = intWidth.value;
    let id = document.querySelector(".frame--item.element--active").getAttribute("data-id");
    let colorMargin = 0;
    let colorBorder = 0;
    let colorFrame = document.querySelector(".color--frame.element--active") ? document.querySelector(".color--frame.element--active").getAttribute("data-id") : 0;
    let route = document.querySelector("#enmarcarTipo").getAttribute("data-route");
    let type = document.querySelector("#enmarcarTipo").getAttribute("data-name");
    let orientation = document.querySelector(".orientation.element--active").getAttribute("data-name");
    let idType = document.querySelector("#enmarcarTipo").getAttribute("data-id");

    formData.append("height",height);
    formData.append("width",width);
    formData.append("styleValue",styleFrame);
    formData.append("styleName",selectStyle.options[selectStyle.selectedIndex].text);
    formData.append("margin",margin);
    formData.append("qty",1);
    formData.append("id",id);
    formData.append("colorMargin",colorMargin);
    formData.append("colorBorder",colorBorder);
    formData.append("colorFrame",colorFrame);
    formData.append("type",type);
    formData.append("idType",idType);
    formData.append("route",route);
    formData.append("orientation",orientation);
    formData.append("glass","");
    formData.append("material",sortFrame.options[sortFrame.selectedIndex].text);
    formData.append("idGlass",0);

    addFrame.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    addFrame.setAttribute("disabled","");

    request(base_url+"/enmarcar/addCart",formData,"post").then(function(objData){
        addFrame.innerHTML=`<i class="fas fa-shopping-cart"></i> Agregar`;
        addFrame.removeAttribute("disabled");
        if(objData.status){
            document.querySelector("#qtyCart").innerHTML=objData.qty;
            document.querySelector("#qtyCartbar").innerHTML=objData.qty;
            const toast = new bootstrap.Toast(toastLiveExample);
            toast.show();
            
            document.querySelector(".toast-header img").src=objData.data.image;
            document.querySelector(".toast-header img").alt=objData.data.name;
            document.querySelector("#toastProduct").innerHTML=objData.data.name;
            document.querySelector(".toast-body").innerHTML=objData.msg;
        }
    });
}); 
function filterProducts(){
    let formData = new FormData();
    formData.append("height",intHeight.value);
    formData.append("width",intWidth.value);
    formData.append("sort",sortFrame.value);
    containerFrames.innerHTML=`
        <div class="text-center p-5">
            <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    request(base_url+"/enmarcar/filterProducts",formData,"post").then(function(objData){
        if(objData.status){
            containerFrames.innerHTML = objData.data;
        }else{
            containerFrames.innerHTML = `<p class="fw-bold text-center">${objData.data}</p>`;
        }
    });
}
function selectOrientation(element){
    let items = document.querySelectorAll(".orientation");
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove("element--active");
    }
    element.classList.add("element--active");
    document.querySelectorAll(".measures--input")[0].removeAttribute("disabled");
    document.querySelectorAll(".measures--input")[1].removeAttribute("disabled");
    btnNext.classList.remove("d-none");
    document.querySelector("#spcMeasureFrame").innerHTML = intWidth.value+" x "+intHeight.value+"cm";

    if(document.querySelectorAll(".orientation")[0].classList.contains("element--active")){
        document.querySelector("#spcOrientation").innerHTML = "Horizontal";
    }else{
        document.querySelector("#spcOrientation").innerHTML = "Vertical";
    }
    resizeFrame(intWidth.value, intHeight.value);
}
function selectActive(element =null,elements=null){
    let items = document.querySelectorAll(`${elements}`);
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove("element--active");
    }
    element.classList.add("element--active");
}
function resizeFrame(width,height){
    let margin = 0;
    height = parseFloat(height);
    width = parseFloat(width)
    document.querySelector("#spcMeasureFrame").innerHTML = (width+(margin*2))+" x "+(height+(margin*2))+"cm";

    height = height *DIMENSIONDEFAULT;
    width = width *DIMENSIONDEFAULT;

    let heightM = height;
    let widthM = width;
    let styleMargin = getComputedStyle(layoutMargin).height;
    let styleImg = getComputedStyle(layoutImg).height;
    styleMargin = parseInt(styleMargin.replace("px",""));
    styleImg = parseInt(styleImg.replace("px",""));
    if(styleMargin > styleImg){
        heightM = heightM +(margin*10);
        widthM = widthM +(margin*10); 
    }

    layoutImg.style.height = `${height}px`;
    layoutImg.style.width = `${width}px`;
    layoutMargin.style.height = `${heightM}px`;
    layoutMargin.style.width = `${widthM}px`;
    layoutBorder.style.height = `${heightM}px`;
    layoutBorder.style.width = `${widthM}px`;
    
}
function customMargin(margin){
    margin = parseFloat(margin);
    height = parseFloat(intHeight.value);
    width = parseFloat(intWidth.value);
    marginRange.value = margin;
    let marginHeight = (height*DIMENSIONDEFAULT) + (margin*10);
    let marginWidth = (width*DIMENSIONDEFAULT) + (margin*10);
    layoutMargin.style.height = `${marginHeight}px`;
    layoutMargin.style.width = `${marginWidth}px`;
    layoutBorder.style.height = `${marginHeight}px`;
    layoutBorder.style.width = `${marginWidth}px`;
    document.querySelector("#spcMeasureFrame").innerHTML = (width+(margin*2))+" x "+(height+(margin*2))+"cm";
}
function selectColorFrame(){
    for (let i = 0; i < colorFrame.length; i++) {
        let frame = colorFrame[i];
        frame.addEventListener("click",function(){
            if(!document.querySelector(".frame--item.element--active")){
                Swal.fire("Error","Por favor, seleccione la moldura","error");
                return false;
            }
            let bg = getComputedStyle(frame.children[0]).backgroundColor;
            layoutBorder.style.outlineColor=bg;
            document.querySelector("#frameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
            document.querySelector("#spcFrameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
        });
    }
}
function calcularMarco(id=null){
    if(!document.querySelector(".frame--item.element--active")){
        return false;
    }
    if(id == null){
        id = document.querySelector(".frame--item.element--active").getAttribute("data-id");
    }
    let margin = 0;
    let styleFrame = selectStyle.value;
    let height = intHeight.value;
    let width = intWidth.value;
    let styleGlass = 0;
    let type = document.querySelector("#enmarcarTipo").getAttribute("data-id");

    let formData = new FormData();
    formData.append("height",height);
    formData.append("width",width);
    formData.append("style",styleFrame);
    formData.append("glass",styleGlass)
    formData.append("margin",margin);
    formData.append("id",id);
    formData.append("type",type);

    document.querySelectorAll(".totalFrame")[0].innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/enmarcar/calcularMarcoTotal",formData,"post").then(function(objData){
        if(objData.status){
            let data = objData.data;
            let borderImage = `url(${base_url}/Assets/images/uploads/${data.frame}) 40% repeat`;
            //document.querySelector("#reference").innerHTML = "Ref: "+data.reference;
            document.querySelectorAll(".totalFrame")[0].innerHTML = data.total.format;
            //document.querySelectorAll(".totalFrame")[1].innerHTML = data.total.format;
            layoutMargin.style.borderImage= borderImage;
            layoutMargin.style.borderWidth = (data.waste/1.5)+"px";
            layoutMargin.style.boxShadow = `0px 0px 5px ${data.waste/1.6}px rgba(0,0,0,0.75)`;
            layoutBorder.style.outlineWidth = (data.waste/1.6)+"px";
            //layoutBorder.style.outlineColor = "blue";
            layoutMargin.style.borderImageOutset = (data.waste/1.6)+"px";
            
            document.querySelector("#spcReference").innerHTML=data.reference;
            document.querySelector(".product-image-inner").innerHTML = showImages(data.image);
            document.querySelector(".product-image-slider").classList.remove("d-none");
            clickShowImages()
        }
    });
}
function showImages(images){
    let html="";
    for (let i = 0; i < images.length; i++) {
        html+=`<div class="product-image-item"><img src="${images[i]}" alt=""></div>`;
    }
    return html;
}
function clickShowImages(){
    let images = document.querySelectorAll(".product-image-item");
    let divImg = document.querySelector(".frame__img__container img");
    for (let i = 0; i < images.length; i++) {
        const img = images[i];
        img.addEventListener("click",function(){
            let url = img.children[0].getAttribute("src");
            divImg.setAttribute("src",url);
            divImg.setAttribute("data-id",i);
            indexImg = i;
            framePhotos.classList.remove("d-none");
        });
    }
}