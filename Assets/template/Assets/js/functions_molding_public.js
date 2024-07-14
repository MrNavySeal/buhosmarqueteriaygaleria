
const DIMENSIONDEFAULT = 4;
const MAXDIMENSION = 160;
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
const framePhotos = document.querySelector("#framePhotos");
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
let arrConfigFrame = [];
let totalFrame = 0;
let nameTopic = "";
let imageUrl ="";
let idCategory ="";
/*********************Events************************ */
window.addEventListener("load",function(){
    getConfig();
    resizeFrame(intWidth.value, intHeight.value);
})

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
intHeight.addEventListener("change",function(){
    
    let height = intHeight.value;
    let width = intWidth.value;
    if(intHeight.value <= 10.0){
        intHeight.value = 10.0;
    }
    if(height >= MAXDIMENSION){
        intHeight.value = MAXDIMENSION;
        intWidth.value = width >= 100 ? 100 : width;
    }else if(height > 100){
        intWidth.value = width >= 100 ? 100 : width;;
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
        intHeight.value = height >= 100 ? 100 : height;
    }else if(width > 100){
        intHeight.value = height >= 100 ? 100 : height;
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
        const element = sortFrame.options[sortFrame.selectedIndex];
        sortFrame.setAttribute("data-id",element.getAttribute("data-id"));
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
        if(needle.includes("madera") && !needle.includes("muestras")){
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
    const defaultFrame = document.querySelector(".frame--item.element--active");
    const imgFrame = defaultFrame.getAttribute("data-frame");
    const waste = defaultFrame.getAttribute("data-waste");
    layoutMargin.style.borderImage= imgFrame;
    layoutMargin.style.borderWidth = (waste/1.5)+"px";
    layoutMargin.style.boxShadow = `0px 0px 5px ${waste/1.6}px rgba(0,0,0,0.75)`;
    layoutMargin.style.borderImageOutset = (waste/1.6)+"px";
    layoutBorder.style.outlineWidth = (waste/1.6)+"px";
    calcularMarco(id);
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
    const strTag = element.getAttribute("data-tag");
    const strTagFrame = element.getAttribute("data-tagframe");
    select.setAttribute("data-ismargin",isMargin);
    select.setAttribute("data-iscolor",isColor);
    select.setAttribute("data-isbocel",isBocel);
    select.setAttribute("data-isframe",isFrame);
    select.setAttribute("data-margin",intMargin);
    select.setAttribute("data-max",intMarginMax);
    select.setAttribute("data-tag",strTag);
    select.setAttribute("data-tagframe",strTagFrame);
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
    const strTagStyle = selectFrameStyle.getAttribute("data-tag");
    const strTagFrameStyle = selectFrameStyle.getAttribute("data-tagframe");
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
        document.querySelector("#marginTitle").innerHTML = strTagStyle;
        document.querySelector("#colorMarginTitle").innerHTML = strTagStyle;
        document.querySelector("#colorBorderTitle").innerHTML = strTagFrameStyle;
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
        document.querySelector("#marginTitle").innerHTML = strTagStyle;
        document.querySelector("#colorMarginTitle").innerHTML = strTagStyle;
        document.querySelector("#colorBorderTitle").innerHTML = strTagFrameStyle;
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
        const arrAttributes = Array.from(e.parentElement.attributes).filter(a=>a.name.includes("data"));
        for (let i = 0; i < arrAttributes.length; i++) {
            const element = arrAttributes[i];
            if(element.value == sortFrame.value){
                arrProps.push({
                    prop:e.getAttribute("data-id"),
                    option_prop:e.value
                });
                break;
            }
        }
    });
    const formData = new FormData();
    const defaultFrame = document.querySelector(".frame--item.element--active");
    let colorFrameTitle = "";
    let colorMarginTitle = "";
    let colorBorderTitle = "";
    let colorFrameId ="";
    let colorMarginId ="";
    let colorBorderId="";
    if(document.querySelector(".color--frame")){
        colorFrameTitle = document.querySelector(".color--frame.element--active").getAttribute("title");
        colorFrameId = document.querySelector(".color--frame.element--active").getAttribute("data-id");
    }
    if(document.querySelector(".color--margin")){
        colorMarginTitle = document.querySelector(".color--margin.element--active").getAttribute("title");
        colorMarginId = document.querySelector(".color--margin.element--active").getAttribute("data-id");
    }
    if(document.querySelector(".color--border")){
        colorBorderTitle = document.querySelector(".color--border.element--active").getAttribute("title");
        colorBorderId = document.querySelector(".color--border.element--active").getAttribute("data-id");
    }
    formData.append("data",JSON.stringify(arrProps));
    formData.append("id",defaultFrame.getAttribute("data-id"));
    formData.append("height",intHeight.value);
    formData.append("width",intWidth.value);
    formData.append("margin",intMargin);
    formData.append("id_config",idCategory);
    formData.append("orientation",document.querySelector(".orientation.element--active").getAttribute("data-name"));
    formData.append("color_frame",colorFrameTitle);
    formData.append("color_margin",colorMarginTitle);
    formData.append("color_border",colorBorderTitle);
    formData.append("color_frame_id",colorFrameId);
    formData.append("color_margin_id",colorMarginId);
    formData.append("color_border_id",colorBorderId);
    formData.append("type_frame",sortFrame.getAttribute("data-id"));
    document.querySelector(".totalFrame").innerHTML="Calculando...";
    const response = await fetch(base_url+"/Enmarcar/calcularMarcoTotal",{method:"POST",body:formData});
    const objData = await response.json();
    if(objData.status){
        arrFrame = objData.specs;
        arrConfigFrame = objData.config;
        totalFrame = objData.total_clean;
        nameTopic = objData.name;
        document.querySelector(".totalFrame").innerHTML = objData.total;
        showSpecs();
    }
}
function showSpecs(){
    let html="";
    arrFrame.forEach(function(e){
        html+=`
            <tr>
                <td class="bg-light w-50" >${e.name}</td>
                <td>${e.value}</td>
            </tr>
        `;
    });
    document.querySelector("#tableSpecs").innerHTML = html;
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
async function addProduct(){
    if(intHeight.value =="" || intWidth.value==""){
        Swal.fire("Error","Por favor, ingresa las medidas","error");
        return false;
    }
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        return false;
    }
    const isPrint = document.querySelector("#isPrint").getAttribute("data-print");
    if(isPrint== 1){
        if(uploadPicture.value == ""){
            Swal.fire("Error","Por favor, sube la imagen a imprimir","error");
            return false;
        }
    }
    const props = Array.from(document.querySelectorAll(".selectProp"));
    const intMargin = parseInt(props[0].getAttribute("data-margin"));
    const arrProps = [];
    props.forEach(e=>{
        const arrAttributes = Array.from(e.parentElement.attributes).filter(a=>a.name.includes("data"));
        for (let i = 0; i < arrAttributes.length; i++) {
            const element = arrAttributes[i];
            if(element.value == sortFrame.value){
                arrProps.push({
                    prop:e.getAttribute("data-id"),
                    option_prop:e.value
                });
                break;
            }
        }
    });
    const formData = new FormData();
    const defaultFrame = document.querySelector(".frame--item.element--active");
    let colorFrameTitle = "";
    let colorMarginTitle = "";
    let colorBorderTitle = "";
    let colorFrameId ="";
    let colorMarginId ="";
    let colorBorderId="";
    if(document.querySelector(".color--frame")){
        colorFrameTitle = document.querySelector(".color--frame.element--active").getAttribute("title");
        colorFrameId = document.querySelector(".color--frame.element--active").getAttribute("data-id");
    }
    if(document.querySelector(".color--margin")){
        colorMarginTitle = document.querySelector(".color--margin.element--active").getAttribute("title");
        colorMarginId = document.querySelector(".color--margin.element--active").getAttribute("data-id");
    }
    if(document.querySelector(".color--border")){
        colorBorderTitle = document.querySelector(".color--border.element--active").getAttribute("title");
        colorBorderId = document.querySelector(".color--border.element--active").getAttribute("data-id");
    }
    formData.append("data",JSON.stringify(arrProps));
    formData.append("id",defaultFrame.getAttribute("data-id"));
    formData.append("height",intHeight.value);
    formData.append("width",intWidth.value);
    formData.append("margin",intMargin);
    formData.append("id_config",idCategory);
    formData.append("orientation",document.querySelector(".orientation.element--active").getAttribute("data-name"));
    formData.append("color_frame",colorFrameTitle);
    formData.append("color_margin",colorMarginTitle);
    formData.append("color_border",colorBorderTitle);
    formData.append("color_frame_id",colorFrameId);
    formData.append("color_margin_id",colorMarginId);
    formData.append("color_border_id",colorBorderId);
    formData.append("type_frame",sortFrame.getAttribute("data-id"));
    formData.append("img",imageUrl);
    addFrame.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    addFrame.setAttribute("disabled","");
    const response = await fetch(base_url+"/Enmarcar/addCart",{method:"POST",body:formData});
    const objData = await response.json();
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
    addFrame.innerHTML=`<i class="fas fa-shopping-cart"></i> Agregar`;
    addFrame.removeAttribute("disabled");

}
/*************************Molding functions*******************************/
async function getConfig(){
    let url = window.location.href;
    let parts = url.split("/");
    let params = parts[parts.length-1];
    
    const response = await fetch(base_url+"/Enmarcar/getConfig/"+params);
    const objData = await response.json();
    if(objData.status){
        
        const data = objData.data;
        const detail = data.detail;
        const props = detail.props;
        arrDataMolding = detail.molding;
        idCategory = data.category_id;
        const displayFrame = document.querySelector("#isFrame");
        const displayPrint = document.querySelector("#isPrint");
        const displayPrintStatus = document.querySelector("#imgQuality");
        const displayCamera = document.querySelector(".up-image");
        const framePages = Array.from(document.querySelectorAll(".page"));
        if(data.is_frame){
            displayFrame.classList.remove("d-none");
            framePages.forEach(e=>e.classList.replace("col-md-12","col-md-6"));
        }else{
            displayFrame.classList.add("d-none");
            framePages.forEach(e=>e.classList.replace("col-md-6","col-md-12"));
        }
        if(data.is_print){
            displayPrint.setAttribute("data-print",1);
            displayPrint.classList.remove("d-none");
            displayPrintStatus.classList.remove("d-none");
            displayCamera.classList.add("d-none");
        }else{
            displayPrint.setAttribute("data-print",0);
            displayPrint.classList.add("d-none");
            displayPrintStatus.classList.add("d-none");
            displayCamera.classList.remove("d-none");
        }
        showProps(props);
        showMolding(arrDataMolding,objData.color);
        showDefaultFraming(data.idCategory,params);
        document.querySelector(".layout--img img").setAttribute("src",data.url);
    }
}

function showProps(data){
    let html ="";
    if(data.length > 0){
        data.forEach(d => {
            const optionsProps = d.options;
            const attributes = d.attributes;
            let propAttributes = "";
            let selectOptions = "";
            const defaultOption = optionsProps[0];
            if(optionsProps.length>0){
                optionsProps.forEach(o=>{
                    selectOptions+=`<option value="${o.id}" data-margin="1" data-iscolor="${o.is_color}" 
                    data-isframe="${o.is_frame}" data-ismargin="${o.is_margin}" data-id="${d.prop}" data-max="${o.margin}" data-isbocel="${o.is_bocel}"
                    data-tag="${o.tag}" data-tagframe="${o.tag_frame}">${o.name}</option>`
                });
            }
            attributes.forEach(a => {
                propAttributes+=" "+a.attribute;
            });
            html+= `
                <div class="mb-3 selectPropContent" ${propAttributes}>
                    <span class="fw-bold">${d.name}</span>
                    <select class="form-select mt-3 mb-3 selectProp"  onchange="updateFramingConfig(this)" data-ismargin="${defaultOption.is_margin}" data-id="${d.prop}"
                    data-margin="0" data-max="${defaultOption.margin}" data-iscolor="${defaultOption.is_color}" data-isframe="${defaultOption.is_frame}"
                    data-isbocel="${defaultOption.is_bocel}" data-tag="${defaultOption.tag}" data-tagframe="${defaultOption.tag_frame}">${selectOptions}</select>
                </div>
            `;
            if(data[0].prop == d.prop){
                html+=`<div class="option--custom  mb-3">
                        <div class="d-none" id="isMargin" data-name="${defaultOption.is_margin}">
                            <div class="mb-3" >
                                <span class="fw-bold">Medida del <span id="marginTitle">${defaultOption.tag}</span></span>
                                <input type="range" class="form-range custom--range pe-4 ps-4 mt-2" min="1" max="${defaultOption.margin}" value="1" id="marginRange" 
                                oninput="selectMargin(this.value)">
                                <div class="fw-bold text-end pe-4 ps-4" id="marginData">1 cm</div>
                            </div>
                            <div class="mb-3">
                                <div class="fw-bold d-flex justify-content-between">
                                    <span>Elige el color del <span id="colorMarginTitle">${defaultOption.tag}</span></span>
                                    <span id="marginColor"></span>
                                </div>
                                <div class="colors mt-3" id="colorsMargin">
                                    <div class="colors--item color--margin element--hover"  title="blanco" data-id="1">
                                        <div style="background-color:#fff"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-none" id="isBorder" data-isframe="${defaultOption.is_margin}" data-isbocel="${defaultOption.is_bocel}">
                            <div class="mb-3 borderColor">
                                <div class="fw-bold d-flex justify-content-between">
                                    <span>Elige el color del <span id="colorBorderTitle">${defaultOption.tag_frame}</span></span>
                                    <span id="borderColor"></span>
                                </div>
                                <div class="colors mt-3" id="colorsBorder">
                                    <div class="colors--item color--border element--hover"  title="blanco" data-id="1">
                                        <div style="background-color:#fff"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
            }
        });
    }
    document.querySelector("#contentProps").innerHTML = html;
}
function showMolding(data,color){
    let html = "";
    let contentFrames ="";
    let colorHtml ="";
    const colorFrame = document.querySelector("#frame--color");
    if(data.length > 0){
        data.forEach(e => {
            html+=`<option value="${e.name}" data-id="${e.idsubcategory}">${e.name}</option>`
        });
        const frames = data[0].frames;
        const needle = data[0].name.toLowerCase();
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
        if(needle.includes("madera")){
            colorFrame.classList.remove("d-none");
            color.forEach(e=>{
                colorHtml +=`
                    <div class="colors--item color--frame element--hover" onclick="selectActive(this,'.color--frame');selectColorFrame(this);" title="${e.name}" data-id="${e.id}">
                        <div style="background-color:#${e.color}"></div>
                    </div>
                `;
            })
        }else{
            colorFrame.classList.add("d-none");
        }
    }
    document.querySelector("#frame--color .colors").innerHTML =colorHtml;
    document.querySelector("#colorsMargin").innerHTML = colorHtml;
    document.querySelector("#colorsBorder").innerHTML = colorHtml;
    document.querySelector("#sortFrame").innerHTML = html;
    document.querySelector(".select--frames").innerHTML = contentFrames;
    document.querySelector("#sortFrame").setAttribute("data-id",data[0].idsubcategory);

    const arrColorsMargin = Array.from(document.querySelector("#colorsMargin").children);
    const arrColorsBorder = Array.from(document.querySelector("#colorsBorder").children);
    arrColorsMargin.forEach(e => {
        e.classList.replace("color--frame","color--margin");
        e.setAttribute("onclick","selectActive(this,'.color--margin');selectColor(this,'margin')");
    });
    arrColorsBorder.forEach(e => {
        e.classList.replace("color--frame","color--border");
        e.setAttribute("onclick","selectActive(this,'.color--border');selectColor(this,'border')");
    });
    
}
async function showDefaultFraming(id,params){
    const colorFrame = document.querySelectorAll(".color--frame");
    const layoutMargin = document.querySelector(".layout--margin");
    const layoutBorder = document.querySelector(".layout--border");
    const intHeight = document.querySelector("#intHeight").value;
    const intWidth = document.querySelector("#intWidth").value;
    const orientation = Array.from(document.querySelectorAll(".orientation"));
    const props = Array.from(document.querySelectorAll(".selectProp"));
    const intMargin = parseInt(props[0].getAttribute("data-margin"));
    const arrProps = [];
    props.forEach(e=>{
        arrProps.push({
            prop:e.getAttribute("data-id"),
            option_prop:e.value
        })
    });

    orientation.forEach(e=>{
        e.setAttribute("onClick","selectOrientation(this)");
    });
    orientation[0].classList.add("element--active");
    if(!document.querySelector(".frame--item.element--active")){
        document.querySelectorAll(".frame--item")[0].classList.add("element--active");
    }
    if(document.querySelector(".color--frame") && !document.querySelector(".color--frame.element--active")){
        document.querySelectorAll(".color--frame")[0].classList.add("element--active");
        document.querySelector("#frameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
    }
    if(document.querySelector(".color--margin") && !document.querySelector(".color--margin.element--active")){
        document.querySelectorAll(".color--margin")[0].classList.add("element--active");
    }
    if(document.querySelector(".color--border") && !document.querySelector(".color--border.element--active")){
        document.querySelectorAll(".color--border")[0].classList.add("element--active");
    }
    
    let bg = colorFrame.length > 0 ? getComputedStyle(colorFrame[0].children[0]).backgroundColor : "transparent";
    const defaultFrame = document.querySelector(".frame--item.element--active");
    const imgFrame = defaultFrame.getAttribute("data-frame");
    const waste = defaultFrame.getAttribute("data-waste");
    layoutMargin.style.borderImage= imgFrame;
    layoutMargin.style.borderWidth = (waste/1.5)+"px";
    layoutMargin.style.boxShadow = `0px 0px 5px ${waste/1.6}px rgba(0,0,0,0.75)`;
    layoutMargin.style.borderImageOutset = (waste/1.6)+"px";
    layoutBorder.style.outlineWidth = (waste/1.6)+"px";
    layoutBorder.style.outlineColor=bg; 
    let colorFrameTitle = "";
    let colorMarginTitle = "";
    let colorBorderTitle = "";
    let colorFrameId ="";
    let colorMarginId ="";
    let colorBorderId="";
    if(document.querySelector(".color--frame")){
        colorFrameTitle = document.querySelector(".color--frame.element--active").getAttribute("title");
        colorFrameId = document.querySelector(".color--frame.element--active").getAttribute("data-id");
    }
    if(document.querySelector(".color--margin")){
        colorMarginTitle = document.querySelector(".color--margin.element--active").getAttribute("title");
        colorMarginId = document.querySelector(".color--margin.element--active").getAttribute("data-id");
    }
    if(document.querySelector(".color--border")){
        colorBorderTitle = document.querySelector(".color--border.element--active").getAttribute("title");
        colorBorderId = document.querySelector(".color--border.element--active").getAttribute("data-id");
    }
    const formData = new FormData();
    formData.append("data",JSON.stringify(arrProps));
    formData.append("id",defaultFrame.getAttribute("data-id"));
    formData.append("height",intHeight);
    formData.append("width",intWidth);
    formData.append("margin",intMargin);
    formData.append("id_config",id);
    formData.append("orientation",document.querySelector(".orientation.element--active").getAttribute("data-name"));
    formData.append("color_frame",colorFrameTitle);
    formData.append("color_margin",colorMarginTitle);
    formData.append("color_border",colorBorderTitle);
    formData.append("color_frame_id",colorFrameId);
    formData.append("color_margin_id",colorMarginId);
    formData.append("color_border_id",colorBorderId);
    formData.append("type_frame",sortFrame.getAttribute("data-id"));
    formData.append("img","");
    
    const response = await fetch(base_url+"/Enmarcar/getConfig/"+params);
    const objData = await response.json();
    if(objData.status){
        document.querySelector(".totalFrame").innerHTML = objData.total;
        calcularMarco();
    }
}