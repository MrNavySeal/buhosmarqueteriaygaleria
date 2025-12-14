
let currentController = null;
const DIMENSIONDEFAULT = 4;
const MAXDIMENSION = 180;
const BORDERBOCEL = 3;
const BORDERFLOTANTE = 10;
const BORDERRADIUS = 2;
const rangeZoom = document.querySelector("#zoomRange");
const minusZoom = document.querySelector("#zoomMinus");
const plusZoom = document.querySelector("#zoomPlus");
const intHeight = document.querySelector("#intHeight");
const intWidth = document.querySelector("#intWidth");
const layoutImg = document.querySelector(".layout--img");
const layoutContainer = document.querySelector(".layout--container");
const layoutMargin = document.querySelector(".layout--margin");
const layoutBorder = document.querySelector(".layout--border");
const selectStyle = document.querySelectorAll(".selectProp");

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

const frameContainer = document.querySelector('.layout--container');
let isDragging = false;
const position = { x: 0, y: 0 }

interact(frameContainer).draggable({
  listeners: {
    move (event) {
      position.x += event.dx
      position.y += event.dy

      event.target.style.transform =
        `translate(${position.x}px, ${position.y}px)`
    },
  }
})


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
        }else{
            Swal.fire("Error","La imagen supera los 30MB, optimiza o cambia de imagen","error");
            uploadPicture.value ="";
            return false;
        }
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
    selectTypeFrame();
});

containerFrames.addEventListener("click",function(e){
    let id = e.target.parentElement.getAttribute("data-id");
    selectFrame(id);
});

/*********************FUNCTIONS************************ */
function selectFrame(id,flag=true){
    const defaultFrame = document.querySelector(".frame--item.element--active");
    const imgFrame = defaultFrame.getAttribute("data-frame");
    const waste = defaultFrame.getAttribute("data-waste");
    layoutMargin.style.borderImage= imgFrame;
    layoutMargin.style.borderWidth = (waste/1.5)+"px";
    layoutMargin.style.boxShadow = `0px 0px 5px ${waste/1.6}px rgba(0,0,0,0.75)`;
    layoutMargin.style.borderImageOutset = (waste/1.6)+"px";
    layoutBorder.style.outlineWidth = (waste/1.6)+"px";
    if(flag)calcularMarco(id);
}

function setBackground(element){
    const layout = document.querySelector(".layout");
    const elements = Array.from(document.querySelectorAll(".img-frame-bg"));
    elements.forEach(e => {
        e.style.border = "1px solid #dee2e6";
    });

    element.style.border = "1px solid #E05A10";

    if(element.getAttribute("src")){
        layout.style.backgroundImage = "url("+element.getAttribute("src")+")";
        layout.style.backgroundSize = "cover";
    }else{
        layout.style.backgroundImage = "none";
    }
}

function uploadBackground(){
    const layout = document.querySelector(".layout");
    const img = document.querySelector("#frameBgImg");
    let imgUpload = img.value;
    let fileUpload = img.files;
    let type = fileUpload[0].type;
    if(type != "image/png" && type != "image/jpg" && type != "image/jpeg" && type != "image/gif"){
        imgUpload ="";
        Swal.fire("Error","Solo se permite imágenes.","error");
    }else{
        let objectUrl = window.URL || window.webkitURL;
        let route = objectUrl.createObjectURL(fileUpload[0]);
        layout.style.backgroundImage = "url("+route+")";
        layout.style.backgroundSize = "cover";
    }
}

function updateFramingConfig(select,flag=true){ 
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
        setDefaultConfig(false);
    }
    if(flag)calcularMarco();
    
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

function selectOrientation(element,type=""){
    let items = document.querySelectorAll(".orientation");
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove("element--active");
    }
    element.classList.add("element--active");
    document.querySelectorAll(".measures--input")[0].removeAttribute("disabled");
    document.querySelectorAll(".measures--input")[1].removeAttribute("disabled");

    let height = intHeight.value;
    let width = intWidth.value;

    if(type=="horizontal"){
        intHeight.value = height;
        intWidth.value = width;
        
    }else{
        intHeight.value = width;
        intWidth.value = height;
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

function selectMargin(value,flag=true){
    const selectFrameStyle = document.querySelectorAll(".selectProp")[0];
    selectFrameStyle.setAttribute("data-margin",value);
    document.querySelector("#marginRange").setAttribute("max",selectFrameStyle.getAttribute("data-max"));
    document.querySelector("#marginRange").value = value;
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
    if(flag)calcularMarco();
}

function resizeFrame(width,height,flag=true){
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
    if(flag)calcularMarco();
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
    const containterColorMargin = document.querySelector("#containterColorMargin");
    const divTagColorMargin = document.querySelector("#marginColor");
    const isMarginStyle = selectFrameStyle.getAttribute("data-ismargin");
    const isColorStyle = selectFrameStyle.getAttribute("data-iscolor");
    const isBocelStyle = selectFrameStyle.getAttribute("data-isbocel");
    const isFrameStyle = selectFrameStyle.getAttribute("data-isframe");
    const intMarginStyle = selectFrameStyle.getAttribute("data-margin");
    const strTagStyle = selectFrameStyle.getAttribute("data-tag");
    const strTagFrameStyle = selectFrameStyle.getAttribute("data-tagframe");

    disabledProps(selectFrameStyle);
    selectMargin(0);

    if(isMarginStyle == 1){
        divMargin.classList.remove("d-none");
        containterColorMargin.classList.add("d-none");
        divTagColorMargin.classList.add("d-none");
        layoutMargin.style.backgroundColor="transparent";
        if(isColorStyle == 1){
            containterColorMargin.classList.remove("d-none");
            divTagColorMargin.classList.remove("d-none");
            if(!document.querySelector(".color--margin.element--active")){
                colorMargin.classList.add("element--active");
            }else{
                colorMargin = document.querySelector(".color--margin.element--active");
            }
            divTagColorMargin.innerHTML = colorMargin.getAttribute("title");
            let bm = getComputedStyle(colorMargin.children[0]).backgroundColor;
            layoutMargin.style.backgroundColor=bm;
        }
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

function disabledProps(prop){
    const arrProps = Array.from(document.querySelectorAll(".selectProp"));
    const selectedOption = prop.options[prop.selectedIndex];
    const selectDisabledProps = selectedOption.getAttribute("data-disabled");
    let arrDisabledProps = [];

    if(selectDisabledProps != ""){
        arrDisabledProps = JSON.parse(JSON.parse(selectDisabledProps));
    }
    arrProps.forEach(element => {
        let idProp = element.getAttribute("data-id");
        if(arrDisabledProps.length > 0){
            arrDisabledProps.filter(function(e){return e.id == idProp});
            if(arrDisabledProps.filter(function(e){return e.id == idProp}).length > 0){
                element.parentElement.classList.add("d-none");
            }else{
                element.parentElement.classList.remove("d-none");
            }
        }
    });
}

async function calcularMarco(id=null){
    if (currentController) {
        currentController.abort();
    }

    currentController = new AbortController();
    const { signal } = currentController;

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
    document.querySelector(".totalFrame").innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    const response = await fetch(base_url+"/Enmarcar/calcularMarcoTotal",{method:"POST",body:formData,signal});
    const objData = await response.json();
    if(objData.status){
        arrFrame = objData.specs;
        arrConfigFrame = objData.config;
        totalFrame = objData.total_clean;
        nameTopic = objData.name;
        document.querySelector(".totalFrame").innerHTML = objData.total;
        document.querySelector("#tableFrameDiscounts").innerHTML = objData.html_wholesale;
        showSpecs();
    }
}

function selectTypeFrame(flag = true){
    if(intWidth.value !="" && intHeight.value!=""){
        const element = sortFrame.options[sortFrame.selectedIndex];
        sortFrame.setAttribute("data-id",element.getAttribute("data-id"));
        const arrPropContent = Array.from(document.querySelectorAll(".selectPropContent"));
        const colorFrame = document.querySelector("#frame--color");
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

        let bgSelectedFrame = "transparent";
        if(needle.includes("madera") && !needle.includes("muestras")){
            const colorSelectedFrame = document.querySelector(".color--frame.element--active");
            bgSelectedFrame = getComputedStyle(colorSelectedFrame.children[0]).backgroundColor;
            colorFrame.classList.remove("d-none");
            document.querySelector("#frameColor").innerHTML = colorSelectedFrame.getAttribute("title");
        }else{
            colorFrame.classList.add("d-none");
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
        if(flag)calcularMarco();
    }
}

function copyStyle(data,flag){
    const frame = data.frame;
    const typeFrame = data.type_frame;
    const props = data.props;
    const margin = data.margin;
    const height = data.height;
    const width = data.width;
    const orientation = data.orientation;
    const colorFrame = data.color_frame;
    const colorMargin = data.color_margin;
    const colorBorder = data.color_border;
    sortFrame.value=typeFrame;
    selectTypeFrame(false);
    let frames = Array.from(document.querySelectorAll(".frame--item"));
    let arrColorFrame = Array.from(document.querySelectorAll(".color--frame"));
    let arrColorMargin = Array.from(document.querySelectorAll(".color--margin"));
    let arrColorBorder = Array.from(document.querySelectorAll(".color--border"));
    let arrProps = Array.from(document.querySelectorAll(".selectProp"));

    frames.forEach(function(e){e.classList.remove("element--active");});
    arrColorFrame.forEach(function(e){e.classList.remove("element--active");});
    arrColorMargin.forEach(function(e){e.classList.remove("element--active");});
    arrColorBorder.forEach(function(e){e.classList.remove("element--active");});

    let selectedFrame = frames.filter(function(e){return e.getAttribute("data-id") == frame})[0];
    let selectedColorFrame = arrColorFrame.filter(function(e){return e.getAttribute("data-id") == colorFrame})[0];
    let selectedColorMargin = arrColorMargin.filter(function(e){return e.getAttribute("data-id") == colorMargin})[0];
    let selectedColorBorder= arrColorBorder.filter(function(e){return e.getAttribute("data-id") == colorBorder})[0];
    
    selectedFrame.classList.add("element--active");
    selectedColorFrame.classList.add("element--active");
    selectedColorMargin.classList.add("element--active");
    selectedColorBorder.classList.add("element--active");

    selectColor(selectedColorMargin,"margin");
    selectColor(selectedColorBorder,"border");

    arrProps.forEach(function(e){
        props.forEach(f => {
            if(e.getAttribute("data-id") == f.prop){
                e.value = f.option_prop;
                updateFramingConfig(e,false);
            }
        });
    });
    
    if(flag){
        intHeight.value = height;
        intWidth.value = width;
        let items = Array.from(document.querySelectorAll(".orientation"));
        items.forEach(function(e){e.classList.remove("element--active");});
        if(orientation == "horizontal"){
            document.querySelectorAll(".orientation")[0].classList.add("element--active");
        }else{
            document.querySelectorAll(".orientation")[1].classList.add("element--active");
        }
        resizeFrame(intWidth.value, intHeight.value,false);
    }
    if(sortFrame.value == "Molduras En Madera"){
        selectColorFrame(selectedColorFrame);
    }
    selectFrame(frame,false);
    selectMargin(margin,false);
    calcularMarco(frame);
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
    formData.append("qty",document.querySelector("#txtQtyFrame") ? document.querySelector("#txtQtyFrame").value : 1 );
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
                    let objDisabledProps = o.disabled_props != "" ? JSON.stringify(o.disabled_props).replace(/"/g, '&quot;') : "";
                    selectOptions+=`<option value="${o.id}" data-margin="1" data-iscolor="${o.is_color}" 
                    data-isframe="${o.is_frame}" data-ismargin="${o.is_margin}" data-disabled='${objDisabledProps}' data-id="${d.prop}" data-max="${o.margin}" data-isbocel="${o.is_bocel}"
                    data-tag="${o.tag}" data-tagframe="${o.tag_frame}">${o.name}</option>`
                });
            }
            attributes.forEach(a => {
                propAttributes+=" "+a.attribute;
            });
            html+= `
                <div class="mb-3 selectPropContent" ${propAttributes}>
                    <span >${d.name}</span>
                    <select class="form-select mt-3 mb-3 selectProp"  onchange="updateFramingConfig(this)" data-ismargin="${defaultOption.is_margin}" data-id="${d.prop}"
                    data-margin="0" data-max="${defaultOption.margin}" data-iscolor="${defaultOption.is_color}" data-isframe="${defaultOption.is_frame}"
                    data-isbocel="${defaultOption.is_bocel}" data-tag="${defaultOption.tag}" data-tagframe="${defaultOption.tag_frame}">${selectOptions}</select>
                </div>
            `;
            if(data[0].prop == d.prop){
                html+=`<div class="option--custom  mb-3">
                        <div class="d-none" id="isMargin" data-name="${defaultOption.is_margin}">
                            <div class="mb-3" >
                                <span >Medida del <span id="marginTitle">${defaultOption.tag}</span></span>
                                <input type="range" class="form-range custom--range pe-4 ps-4 mt-2" min="1" max="${defaultOption.margin}" value="1" id="marginRange" 
                                oninput="selectMargin(this.value)">
                                <div class="fw-bold text-end pe-4 ps-4" id="marginData">1 cm</div>
                            </div>
                            <div class="mb-3" id="containterColorMargin">
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


$(".slider-examples").owlCarousel({
    autoplay:true,
    autoplayTimeout:5000,
    autoplayHoverPause:true,
    loop:true,
    margin:10,
    nav:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
});
