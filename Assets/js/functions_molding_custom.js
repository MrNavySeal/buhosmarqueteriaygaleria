const DIMENSIONDEFAULT = 4;
const MAXDIMENSION = 500;
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
let colorMargin = document.querySelectorAll(".color--margin");
let colorBorder = document.querySelectorAll(".color--border");

const selectStyle = document.querySelectorAll(".selectProp");
const optionsCustom = document.querySelectorAll(".option--custom");
const btnBack = document.querySelector("#btnBack");
const btnNext = document.querySelector("#btnNext");
const pages = document.querySelectorAll(".page");
const containerFrames = document.querySelector(".select--frames");
const searchFrame = document.querySelector("#searchFrame");
const sortFrame = document.querySelector("#sortFrame");
const addFrame = document.querySelector("#addFrame");
const uploadPicture = document.querySelector("#txtPicture");
const uploadFramingImg = document.querySelector("#txtImgShow");
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
/*********************Events************************ */
window.addEventListener("DOMContentLoaded",function(){
    resizeFrame(intWidth.value, intHeight.value);
})

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

intHeight.addEventListener("change",function(){
    let height = intHeight.value;
    let width = intWidth.value;
    if(intHeight.value <= 10.0){
        intHeight.value = 10.0;
    }
    if(height >= MAXDIMENSION){
        intHeight.value = MAXDIMENSION;
    }
    setDefaultConfig();
    resizeFrame(intWidth.value, intHeight.value);
});
intWidth.addEventListener("change",function(){
    let height = intHeight.value;
    let width = intWidth.value;
    if(intHeight.value <= 10.0){
        intHeight.value = 10.0;
    }
    if(width >= MAXDIMENSION){
        intWidth.value = MAXDIMENSION;
    }
    setDefaultConfig();
    resizeFrame(intWidth.value, intHeight.value);
});

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

uploadPicture.addEventListener("change",function(){
    uploadImg(uploadPicture,".layout--img img");
});
uploadFramingImg.addEventListener("change",function(){
    uploadImg(uploadFramingImg,".layout--img img");
});

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
        request(base_url+"/marcos/sort",formData,"post").then(function(objData){
            if(objData.status){
                containerFrames.innerHTML = objData.data;
                setDefaultConfig();
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

/*********************FUNCTIONS************************ */
function updateFramingConfig(select){ 
    const element = select.options[select.selectedIndex];
    const isMargin = element.getAttribute("data-ismargin");
    const isColor = element.getAttribute("data-iscolor");
    const isBocel = element.getAttribute("data-isbocel");
    const isFrame = element.getAttribute("data-isframe");
    select.setAttribute("data-ismargin",isMargin);
    select.setAttribute("data-iscolor",isColor);
    select.setAttribute("data-isbocel",isBocel);
    select.setAttribute("data-isframe",isFrame);

    if(document.querySelectorAll(".selectProp")[0]){
        colorMargin = document.querySelectorAll(".color--margin");
        colorBorder = document.querySelectorAll(".color--border");
        const selectFrameStyle = document.querySelectorAll(".selectProp")[0];
        const divMargin = document.querySelector("#isMargin");
        const divBorder = document.querySelector("#isBorder");
        const isMarginStyle = selectFrameStyle.getAttribute("data-ismargin");
        const isColorStyle = selectFrameStyle.getAttribute("data-iscolor");
        const isBocelStyle = selectFrameStyle.getAttribute("data-isbocel");
        const isFrameStyle = selectFrameStyle.getAttribute("data-isframe");
        if(isMarginStyle == 1){
            divMargin.classList.remove("d-none");
            if(!document.querySelector(".color--margin.element--active")){
                document.querySelectorAll(".color--margin")[0].classList.add("element--active");
            }
            document.querySelector("#marginColor").innerHTML = document.querySelector(".color--margin.element--active").getAttribute("title");
            let bm = getComputedStyle(colorMargin[0]).backgroundColor;
            layoutMargin.style.backgroundColor=bm;
        }else{
            divMargin.classList.add("d-none");
        }
        if(isBocelStyle == 1){
            divBorder.classList.remove("d-none");
            if(!document.querySelector(".color--border.element--active")){
                document.querySelectorAll(".color--border")[0].classList.add("element--active");
            }
            document.querySelector("#borderColor").innerHTML = document.querySelector(".color--border.element--active").getAttribute("title");
            let bb = getComputedStyle(colorBorder[0]).backgroundColor;
            layoutImg.style.borderColor=bb;
        }else{
            divBorder.classList.add("d-none");
        }
    }
    
}
function selectColor(element=null,option=null){
    console.log(option);
    const select = document.querySelectorAll(".selectProp")[0];
    const isBocel = select.getAttribute("data-isbocel");
    const isFrame = select.getAttribute("data-isframe");
    layoutImg.style.border="none";
    layoutMargin.style.backgroundColor="#000";
    if(option =="margin"){
        document.querySelector("#marginColor").innerHTML = document.querySelector(".color--margin.element--active").getAttribute("title");
        let bg = getComputedStyle(element.children[0]).backgroundColor;
        layoutMargin.style.backgroundColor=bg;
    }else if(option=="border"){
        if(isBocel)layoutImg.style.border="5px solid #fff";
        if(isFrame)layoutImg.style.border="10px solid #fff";
        document.querySelector("#borderColor").innerHTML = document.querySelector(".color--border.element--active").getAttribute("title");
        let bg = getComputedStyle(element.children[0]).backgroundColor;
        layoutImg.style.backgroundColor=bg;
    }
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
    const selectStyle = document.querySelectorAll(".selectProp")[0];
    let margin = 0;
    if(document.querySelector(".selectProp")){
        const selectStyle = document.querySelectorAll(".selectProp")[0];
        if(selectStyle.getAttribute("data-ismargin")==1){
            margin = parseInt(document.querySelector("#marginRange").value);
        }
    }
    height = parseFloat(height);
    width = parseFloat(width)

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
function selectColorFrame(element){
    const colorFrame = document.querySelectorAll(".color--frame");
    for (let i = 0; i < colorFrame.length; i++) {
        let frame = colorFrame[i];
        if(frame.className.includes("element--active")){
            frame.classList.remove("element--active");
        }
    }
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        return false;
    }
    element.classList.add("element--active");
    let bg = getComputedStyle(element.children[0]).backgroundColor;
    layoutBorder.style.outlineColor=bg;
    document.querySelector("#frameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
    
}
function setDefaultConfig(){
    /*if(!document.querySelector(".frame--item.element--active")){
        document.querySelectorAll(".frame--item")[0].classList.add("element--active");
    }
    if(!document.querySelector(".color--frame.element--active")){
        document.querySelectorAll(".color--frame")[2].classList.add("element--active");
    }else if(sortFrame.value == 1){
        let bg = getComputedStyle(document.querySelector(".color--frame.element--active").children[0]).backgroundColor;
        layoutBorder.style.outlineColor=bg;
        document.querySelector("#frameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
        //document.querySelector("#spcFrameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
    }else{
        //document.querySelector("#spcFrameColor").innerHTML = "N/A";
        layoutBorder.style.outlineColor="transparent";
        selectColorFrame();
    }*/
    //document.querySelectorAll(".orientation")[0].classList.add("element--active");
    //calcularMarco();
}



function selectMargin(element){
    margin = parseFloat(element.value);
    height = parseFloat(intHeight.value);
    width = parseFloat(intWidth.value);
    let marginHeight = (height*DIMENSIONDEFAULT) + (margin*10);
    let marginWidth = (width*DIMENSIONDEFAULT) + (margin*10);
    layoutMargin.style.height = `${marginHeight}px`;
    layoutMargin.style.width = `${marginWidth}px`;
    layoutBorder.style.height = `${marginHeight}px`;
    layoutBorder.style.width = `${marginWidth}px`;
    document.querySelector("#marginData").innerHTML= margin+" cm";
}
function selectStyleFrame(option){
    document.querySelector(".borderColor").classList.remove("d-none");
    document.querySelector("#spanP").innerHTML="Medida del paspartú";
    document.querySelector("#spanPC").innerHTML="Elige el color del paspartú";
    document.querySelector("#spanBorde").innerHTML="Elige el color del bocel";
    if(option == 1){
        optionsCustom[0].classList.add("d-none");
        //optionsCustom[1].classList.add("d-none");
        customMargin(0);
        selectColors();
        document.querySelector("#spcColorP").innerHTML ="N/A";
        document.querySelector("#spcColorB").innerHTML ="N/A";
        document.querySelector("#spcMeasureP").innerHTML = "0cm";
    }else if(option == 2 || option == 4){
        optionsCustom[0].classList.remove("d-none");
        //optionsCustom[1].classList.add("d-none");
        customMargin(1);
        document.querySelector("#spcMeasureP").innerHTML = "1cm";
        if(option==2){
            selectColors(1);
        }else{
            document.querySelector("#spanP").innerHTML="Medida del fondo";
            document.querySelector("#spanPC").innerHTML="Elige el color del fondo";
            document.querySelector("#spanBorde").innerHTML="Elige el color del marco interno";
            selectColors(2);
        }
        if(!document.querySelector(".color--border.element--active") && !document.querySelector(".color--margin.element--active")){
            document.querySelectorAll(".color--border")[2].classList.add("element--active");
            document.querySelectorAll(".color--margin")[2].classList.add("element--active");
            layoutMargin.style.backgroundColor = getComputedStyle(document.querySelectorAll(".color--margin")[2]).backgroundColor;
            layoutImg.style.borderColor = getComputedStyle(document.querySelectorAll(".color--border")[2]).backgroundColor;
            document.querySelector("#marginColor").innerHTML = "Blanco";
            document.querySelector("#spcColorP").innerHTML = "Blanco";
            document.querySelector("#borderColor").innerHTML = "Blanco";
            document.querySelector("#spcColorB").innerHTML = "Blanco";
        }
    }else if(option == 3){
        optionsCustom[0].classList.remove("d-none");
        //optionsCustom[1].classList.add("d-none");
        document.querySelector(".borderColor").classList.add("d-none");
        customMargin(1);
        selectColors(0);
        document.querySelector("#spcColorB").innerHTML ="N/A";
        document.querySelector("#spcMeasureP").innerHTML = "1cm";
        if(!document.querySelector(".color--margin.element--active")){
            document.querySelectorAll(".color--margin")[2].classList.add("element--active");
            layoutMargin.style.backgroundColor = getComputedStyle(document.querySelectorAll(".color--margin")[2]).backgroundColor;
            document.querySelector("#marginColor").innerHTML = document.querySelector(".color--margin.element--active").getAttribute("title");
            document.querySelector("#spcColorP").innerHTML = document.querySelector(".color--margin.element--active").getAttribute("title");
        }
    }else if(option == 5){
        document.querySelector("#glassDiv").classList.add("d-none");
        selectGlass.value = 3;
        optionsCustom[0].classList.add("d-none");
        //optionsCustom[1].classList.add("d-none");
        customMargin(0);
        selectColors();
        document.querySelector("#spcColorP").innerHTML ="N/A";
        document.querySelector("#spcColorB").innerHTML ="N/A";
        document.querySelector("#spcMeasureP").innerHTML = "0cm";
        document.querySelector("#spcStyle").innerHTML = "N/A";
    }else{
        customMargin(0);
        selectColors();
        optionsCustom[0].classList.add("d-none");
        //optionsCustom[1].classList.remove("d-none");
        document.querySelector("#spcMeasureP").innerHTML = "0cm";
    }
    document.querySelector("#spcStyle").innerHTML = selectStyle.options[selectStyle.selectedIndex].text;
    document.querySelector("#spcGlass").innerHTML = selectGlass.options[selectGlass.selectedIndex].text;

}

function calcularMarco(id=null){
    if(!document.querySelector(".frame--item.element--active")){
        return false;
    }
    if(id == null){
        id = document.querySelector(".frame--item.element--active").getAttribute("data-id");
    }
    let margin = selectStyle.value == 1 || selectStyle.value == 5 ? 0 : marginRange.value;
    let styleFrame = selectStyle.value;
    let height = intHeight.value;
    let width = intWidth.value;
    let styleGlass = selectGlass.value;
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
    request(base_url+"/marcos/calcularMarcoTotal",formData,"post").then(function(objData){
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
function uploadImg(img,location){
    let imgUpload = img.value;
    let fileUpload = img.files;
    let type = fileUpload[0].type;
    if(type != "image/png" && type != "image/jpg" && type != "image/jpeg" && type != "image/gif"){
        imgUpload ="";
        Swal.fire("Error","Solo se permite imágenes.","error");
    }else{
        let objectUrl = window.URL || window.webkitURL;
        let route = objectUrl.createObjectURL(fileUpload[0]);
        document.querySelector(location).setAttribute("src",route);
    }
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