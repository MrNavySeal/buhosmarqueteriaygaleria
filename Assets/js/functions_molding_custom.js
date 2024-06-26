
const DIMENSIONDEFAULT = 4;
const MAXDIMENSION = 500;
const BORDERBOCEL = 3;
const BORDERFLOTANTE = 10;
const BORDERRADIUS = 2;
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
const selectStyle = document.querySelectorAll(".selectProp");
const btnBack = document.querySelector("#btnBack");
const btnNext = document.querySelector("#btnNext");
const pages = document.querySelectorAll(".page");
const containerFrames = document.querySelector(".select--frames");
const searchFrame = document.querySelector("#searchFrame");
const sortFrame = document.querySelector("#sortFrame");
const uploadPicture = document.querySelector("#txtPicture");
const uploadFramingImg = document.querySelector("#txtImgShow");
const toastLiveExample = document.getElementById('liveToast');
const closeImage = document.querySelector("#closeImg");
const framePhotos = document.querySelector("#framePhotos");
const changeImgL = document.querySelectorAll(".change__img")[0];
const changeImgR = document.querySelectorAll(".change__img")[1];
const addFrame = document.querySelector("#addFrame");
let innerP = document.querySelector(".product-image-inner");
let btnPrevP = document.querySelector(".slider-btn-left");
let btnNextP = document.querySelector(".slider-btn-right");
let indexImg = 0;
let page = 0;
let PPI = 100;
let colorMargin = document.querySelectorAll(".color--margin");
let colorBorder = document.querySelectorAll(".color--border");
let arrFrame = [];
let totalFrame = 0;
let nameTopic = "";
let imageUrl ="";
/*********************Events************************ */
window.addEventListener("load",function(){
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
    if(isPrint== 1){
        calcPpi(intHeight.value,intWidth.value,document.querySelector(".layout--img img"));
    }
    setDefaultConfig();
    resizeFrame(intWidth.value, intHeight.value);
});
intWidth.addEventListener("change",function(){
    const isPrint = document.querySelector("#isPrint").getAttribute("data-print");
    let height = intHeight.value;
    let width = intWidth.value;
    if(intHeight.value <= 10.0){
        intHeight.value = 10.0;
    }
    if(width >= MAXDIMENSION){
        intWidth.value = MAXDIMENSION;
    }
    if(isPrint== 1){
        calcPpi(intHeight.value,intWidth.value,document.querySelector(".layout--img img"));
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
    let file = uploadPicture.files[0];
    if(uploadPicture.value !=""){
        let size = file.size;
        let kb = parseInt(size / 1024);
        let mb = parseInt(kb / 1024);
        if(mb <= 30){
            let reader = new FileReader();
            reader.readAsDataURL (file);
            reader.onload = function() {
                imageUrl=reader.result;
            };
            uploadImg(uploadPicture,".layout--img img");
            if(intHeight.value !="" && intWidth.value!=""){
                btnNext.classList.remove("d-none");
            }
            if(document.querySelector(".orientation.element--active")){
                btnNext.classList.remove("d-none");
            }
        }else{
            Swal.fire("Error","La imagen supera los 30MB, optimiza o cambia de imagen","error");
            uploadPicture.value ="";
            return false;
        }
    }else{
        btnNext.classList.add("d-none");
    }
    setTimeout(function() {
        calcDimension(document.querySelector(".layout--img img"));
        calcularMarco();
    }, 100);
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
        const arrPropContent = Array.from(document.querySelectorAll(".selectPropContent"));
        const colorFrame = document.querySelector("#frame--color");
        const colorSelectedFrame = document.querySelector(".color--frame.element--active");
        let bgSelectedFrame = getComputedStyle(colorSelectedFrame.children[0]).backgroundColor;
        const data = arrDataMolding.filter(e=>e.name == sortFrame.value)[0];
        const frames = data.frames
        const needle = data.name.toLowerCase();
        let contentFrames ="";
        frames.forEach(e=>{
            contentFrames+=`
                <div class="mb-3 frame--container" data-r="${e.reference}">
                    <div class="frame--item frame-main element--hover" data-id="${e.idproduct}" data-frame="${e.framing_img}" data-waste = "${e.waste}"
                    onclick="selectActive(this,'.frame-main')">
                        <img src="${e.image}">
                        <p>REF: ${e.reference}</p>
                    </div>
                </div>
            `;
        });
        arrPropContent.forEach(e=>{
            const arrAttributes = Array.from(e.attributes).filter(a=>a.name.includes("data"));
            for (let i = 0; i < arrAttributes.length; i++) {
                const element = arrAttributes[i];
                if(element.value == sortFrame.value){
                    e.classList.remove("d-none");
                    break;
                }else{
                    e.classList.add("d-none");
                }
            }
        })
        if(needle.includes("madera")){
            colorFrame.classList.remove("d-none");
            document.querySelector("#frameColor").innerHTML = colorSelectedFrame.getAttribute("title");
        }else{
            colorFrame.classList.add("d-none");
            bgSelectedFrame = "transparent";
        }

        document.querySelector(".select--frames").innerHTML = contentFrames;
        document.querySelectorAll(".frame--item")[0].classList.add("element--active");
        const defaultFrame = document.querySelector(".frame--item.element--active");
        const imgFrame = defaultFrame.getAttribute("data-frame");
        const waste = defaultFrame.getAttribute("data-waste");
        layoutMargin.style.borderImage= imgFrame;
        layoutMargin.style.borderWidth = (waste/1.5)+"px";
        layoutMargin.style.boxShadow = `0px 0px 5px ${waste/1.6}px rgba(0,0,0,0.75)`;
        layoutMargin.style.borderImageOutset = (waste/1.6)+"px";
        layoutBorder.style.outlineWidth = (waste/1.6)+"px";
        layoutBorder.style.outlineColor=bgSelectedFrame; 
        calcularMarco();
    }
});

containerFrames.addEventListener("click",function(e){
    let id = e.target.parentElement.getAttribute("data-id");
    calcularMarco(id);
});
addFrame.addEventListener("click",function(){
    addProduct(arrFrame,1);
    modalFrame.hide();
});

/*********************FUNCTIONS************************ */
function updateFramingConfig(select){ 
    const element = select.options[select.selectedIndex];
    const isMargin = element.getAttribute("data-ismargin");
    const isColor = element.getAttribute("data-iscolor");
    const isBocel = element.getAttribute("data-isbocel");
    const isFrame = element.getAttribute("data-isframe");
    const intMarginMax = element.getAttribute("data-max");
    const intMargin = parseInt(element.getAttribute("data-margin"));
    select.setAttribute("data-ismargin",isMargin);
    select.setAttribute("data-iscolor",isColor);
    select.setAttribute("data-isbocel",isBocel);
    select.setAttribute("data-isframe",isFrame);
    select.setAttribute("data-margin",intMargin);
    select.setAttribute("data-max",intMarginMax);
    if(document.querySelectorAll(".selectProp")[0].getAttribute("data-id") == select.getAttribute("data-id")){
        setDefaultConfig();
    }
    calcularMarco();
}
function selectColor(element=null,option=null){
    const select = document.querySelectorAll(".selectProp")[0];
    const isBocel = select.getAttribute("data-isbocel");
    const isFrame = select.getAttribute("data-isframe");
    if(option =="margin"){
        document.querySelector("#marginColor").innerHTML = element.getAttribute("title");
        let bg = getComputedStyle(element.children[0]).backgroundColor;
        layoutMargin.style.backgroundColor=bg;
    }else if(option=="border"){
        document.querySelector("#borderColor").innerHTML = element.getAttribute("title");
        let bg = getComputedStyle(element.children[0]).backgroundColor;
        layoutImg.style.backgroundColor=bg;
        if(isBocel == 1){
            layoutImg.style.border=BORDERBOCEL+"px solid "+bg;
            layoutImg.style.borderRadius=BORDERRADIUS+"px";
        }else if(isFrame == 1){
            layoutImg.style.border=BORDERFLOTANTE+"px solid "+bg;
            layoutImg.style.borderRadius="0";
        }
    }
    calcularMarco();
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
function selectMargin(value){
    const selectFrameStyle = document.querySelectorAll(".selectProp")[0];
    selectFrameStyle.setAttribute("data-margin",value);
    document.querySelector("#marginRange").setAttribute("max",selectFrameStyle.getAttribute("data-max"));
    margin = parseFloat(value);
    height = parseFloat(intHeight.value);
    width = parseFloat(intWidth.value);
    let marginHeight = (height*DIMENSIONDEFAULT) + (margin*10);
    let marginWidth = (width*DIMENSIONDEFAULT) + (margin*10);
    layoutMargin.style.height = `${marginHeight}px`;
    layoutMargin.style.width = `${marginWidth}px`;
    layoutBorder.style.height = `${marginHeight}px`;
    layoutBorder.style.width = `${marginWidth}px`;
    document.querySelector("#marginData").innerHTML= margin+" cm";
    calcularMarco();
}
function resizeFrame(width,height){
    let margin = 0;
    if(document.querySelector(".selectProp")){
        const selectStyle = document.querySelectorAll(".selectProp")[0];
        if(selectStyle.getAttribute("data-ismargin")==1){
            margin = parseInt(selectStyle.getAttribute("data-margin"));
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
    calcularMarco();
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
    calcularMarco();
}
function setDefaultConfig(){
    colorMargin = document.querySelectorAll(".color--margin")[0];
    colorBorder = document.querySelectorAll(".color--border")[0];
    const selectFrameStyle = document.querySelectorAll(".selectProp")[0];
    const divMargin = document.querySelector("#isMargin");
    const divBorder = document.querySelector("#isBorder");
    const isMarginStyle = selectFrameStyle.getAttribute("data-ismargin");
    const isColorStyle = selectFrameStyle.getAttribute("data-iscolor");
    const isBocelStyle = selectFrameStyle.getAttribute("data-isbocel");
    const isFrameStyle = selectFrameStyle.getAttribute("data-isframe");
    const intMarginStyle = selectFrameStyle.getAttribute("data-margin");
    selectMargin(0);
    if(isMarginStyle == 1){
        divMargin.classList.remove("d-none");
        if(!document.querySelector(".color--margin.element--active")){
            colorMargin.classList.add("element--active");
        }else{
            colorMargin = document.querySelector(".color--margin.element--active");
        }
        document.querySelector("#marginColor").innerHTML = colorMargin.getAttribute("title");
        let bm = getComputedStyle(colorMargin.children[0]).backgroundColor;
        layoutMargin.style.backgroundColor=bm;
        document.querySelector("#marginRange").value=intMarginStyle;
        selectMargin(intMarginStyle);
    }else{
        divMargin.classList.add("d-none");
    }
    if(isBocelStyle == 1 || isFrameStyle == 1){
        let borderW = isFrameStyle == 1 ? BORDERFLOTANTE: BORDERBOCEL;
        let borderR = isFrameStyle == 1 ? 0: BORDERRADIUS;
        document.querySelector("#marginTitle").innerHTML = isFrameStyle == 1 ? "fondo" : "paspartú";
        document.querySelector("#colorMarginTitle").innerHTML = isFrameStyle == 1 ? "fondo" : "paspartú";
        document.querySelector("#colorBorderTitle").innerHTML = isFrameStyle == 1 ? "marco interno" : "bocel";
        divBorder.classList.remove("d-none");
        if(!document.querySelector(".color--border.element--active")){
            colorBorder.classList.add("element--active");
        }else{
            colorBorder = document.querySelector(".color--border.element--active");
        }
        document.querySelector("#borderColor").innerHTML = colorBorder.getAttribute("title");
        let bb = getComputedStyle(colorBorder.children[0]).backgroundColor;
        layoutImg.style.border=borderW+"px solid "+bb;
        layoutImg.style.borderRadius=borderR;
    }else{
        document.querySelector("#marginTitle").innerHTML = "paspartú";
        document.querySelector("#colorMarginTitle").innerHTML = "paspartú";
        document.querySelector("#colorBorderTitle").innerHTML = "bocel";
        layoutImg.style.border="none";
        divBorder.classList.add("d-none");
    }
    //calcularMarco();
}
async function calcularMarco(id=null){
    if(!document.querySelector(".frame--item.element--active")){
        return false;
    }
    if(id == null){
        id = document.querySelector(".frame--item.element--active").getAttribute("data-id");
    }
    const props = Array.from(document.querySelectorAll(".selectProp"));
    const intMargin = parseInt(props[0].getAttribute("data-margin"));
    const arrProps = [];
    props.forEach(e=>{
        arrProps.push({
            prop:e.getAttribute("data-id"),
            option_prop:e.value
        })
    });
    const formData = new FormData();
    const defaultFrame = document.querySelector(".frame--item.element--active");
    formData.append("data",JSON.stringify(arrProps));
    formData.append("id",defaultFrame.getAttribute("data-id"));
    formData.append("height",intHeight.value);
    formData.append("width",intWidth.value);
    formData.append("margin",intMargin);
    formData.append("id_config",document.querySelector("#idCategory").value);
    formData.append("orientation",document.querySelector(".orientation.element--active").getAttribute("data-name"));
    formData.append("color_frame",document.querySelector(".color--frame.element--active").getAttribute("title"));
    formData.append("color_margin",document.querySelector(".color--margin.element--active").getAttribute("title"));
    formData.append("color_border",document.querySelector(".color--border.element--active").getAttribute("title"));
    const response = await fetch(base_url+"/MarqueteriaCalculos/calcularMarcoTotal",{method:"POST",body:formData});
    const objData = await response.json();
    if(objData.status){
        arrFrame = objData.specs;
        totalFrame = objData.total_clean;
        nameTopic = objData.name;
        document.querySelector(".totalFrame").innerHTML = objData.total;
    }
}
function calcDimension(picture){
    if(uploadPicture.value !=""){
        let realHeight = picture.naturalHeight;
        let realWidth = picture.naturalWidth;
    
        let height = Math.round((realHeight*2.54)/PPI) < 10 ? 10 :  Math.round((realHeight*2.54)/PPI);
        let width = Math.round((realWidth*2.54)/PPI) < 10 ? 10 :  Math.round((realWidth*2.54)/PPI);
        PPI = height > width ? height : width;
        if(height > MAXDIMENSION){
            height = Math.round((realHeight*2.54)/PPI);
        }
        if(width > MAXDIMENSION){
            width = Math.round((realWidth*2.54)/PPI);
        }
        height = Math.round(height/10)*10;
        width = Math.round(width/10)*10;
        intHeight.value = height;
        intWidth.value = width;
        calcPpi(height,width,picture);
        resizeFrame(intWidth.value,intHeight.value);
    }
}
function calcPpi(height,width,picture){
    
    let realHeight = picture.naturalHeight;
    let realWidth = picture.naturalWidth;

    let h = Math.round((realHeight*2.54)/height);
    let w = Math.round((realWidth*2.54)/width);
    let ppi = Math.floor((h+w))/2;
    ppi = ppi >= 300 ? 300 : ppi;
    if(ppi<100){
        imgQuality.innerHTML = `Resolución ${ppi} ppi <span class="text-danger">mala calidad</span>, puedes reducir las dimensiones o cambiar de imagen`;
    }else{
        imgQuality.innerHTML = `Resolución ${ppi} ppi <span class="text-success">buena calidad</span>`;
    }

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