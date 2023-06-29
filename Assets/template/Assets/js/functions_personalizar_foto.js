const DIMENSIONDEFAULT = 4;
const MAXDIMENSION = 160;
let PPI = 100;
const rangeZoom = document.querySelector("#zoomRange");
const minusZoom = document.querySelector("#zoomMinus");
const plusZoom = document.querySelector("#zoomPlus");
const intHeight = document.querySelector("#intHeight");
const intWidth = document.querySelector("#intWidth");
const layoutImg = document.querySelector(".layout--img");
const layoutMargin = document.querySelector(".layout--margin");
const sliderLeft = document.querySelector(".slider--control-left");
const sliderRight = document.querySelector(".slider--control-right");
const sliderInner = document.querySelector(".slider--inner");
const marginRange = document.querySelector("#marginRange");
const colorMargin = document.querySelectorAll(".color--margin");
const colorBorder = document.querySelectorAll(".color--border");
const selectStyle = document.querySelector("#selectStyle");
const optionsCustom = document.querySelectorAll(".option--custom");
const btnBack = document.querySelector("#btnBack");
const btnNext = document.querySelector("#btnNext");
const pages = document.querySelectorAll(".page");
const containerFrames = document.querySelector(".select--frames");
const searchFrame = document.querySelector("#searchFrame");
const sortFrame = document.querySelector("#sortFrame");
const addFrame = document.querySelector("#addFrame");
const uploadPicture = document.querySelector("#txtPicture");
const imgQuality = document.querySelector("#imgQuality");
const layoutBorder = document.querySelector(".layout--border");
const colorFrame = document.querySelectorAll(".color--frame");
const selectGlass = document.querySelector("#selectGlass");
const closeImage = document.querySelector("#closeImg");
const framePhotos = document.querySelector("#framePhotos");
const changeImgL = document.querySelectorAll(".change__img")[0];
const changeImgR = document.querySelectorAll(".change__img")[1];
let innerP = document.querySelector(".product-image-inner");
let btnPrevP = document.querySelector(".slider-btn-left");
let btnNextP = document.querySelector(".slider-btn-right");
let indexImg = 0;
let page = 0;
const toastLiveExample = document.getElementById('liveToast');

window.addEventListener("load",function(){
    selectColorFrame();
    resizeFrame(intWidth.value, intHeight.value);
    filterProducts();
})
//----------------------------------------------
//[Change Pages]
closeImage.addEventListener("click",function(){
    framePhotos.classList.add("d-none");
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
    calcPpi(intHeight.value,intWidth.value,document.querySelector(".layout--img img"));
    //calcularMarco();
    filterProducts();
    resizeFrame(intWidth.value, intHeight.value);
    
});
intWidth.addEventListener("change",function(){
    let height = intHeight.value;
    let width = intWidth.value;
    if(intWidth.value <= 10.0){
        intWidth.value = 10.0;
    }
    if(width >= MAXDIMENSION){
        intWidth.value = MAXDIMENSION;
        intHeight.value = height >= 100 ? 100 : height;
    }else if(width > 100){
        intHeight.value = height >= 100 ? 100 : height;
    }
    calcPpi(intHeight.value,intWidth.value,document.querySelector(".layout--img img"));
    //calcularMarco();
    filterProducts();
    resizeFrame(intWidth.value, intHeight.value);
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
//[upload image]

uploadPicture.addEventListener("change",function(){
    let file = uploadPicture.files[0];
    if(uploadPicture.value !=""){
        let size = file.size;
        let kb = parseInt(size / 1024);
        let mb = parseInt(kb / 1024);
        if(mb <= 30){
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
        filterProducts();
    }, 100);
    //console.log(resolution);
});


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
                filterProducts();
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
marginRange.addEventListener("input",function(){
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        return false;
    }
    customMargin(marginRange.value);
    calcularMarco();
});
//[Select style]
selectStyle.addEventListener("change",function(){
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        selectStyle.value = 1;
        return false;
    }
    selectStyleFrame(selectStyle.value);
    filterProducts();
});
selectGlass.addEventListener("change",function(){
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        selectGlass.value = 1;
        return false;
    }
    document.querySelector("#spcGlass").innerHTML = selectGlass.options[selectGlass.selectedIndex].text;
    calcularMarco();
});
//--
//----------------------------------------------
//[Add frame]
frame.addEventListener("submit",function(e){
    e.preventDefault();
    let formData = new FormData(frame);

    if(uploadPicture.value == ""){
        Swal.fire("Error","Por favor, sube la imagen a imprimir","error");
        return false;
    }
    if(intHeight.value =="" || intWidth.value==""){
        Swal.fire("Error","Por favor, ingresa las medidas","error");
        return false;
    }
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        return false;
    }
    if(selectStyle.value == 2 || selectStyle.value == 4){
        if(!document.querySelector(".color--margin.element--active")){
            Swal.fire("Error","Por favor, elige el color del paspartú","error");
            return false;
        }
        if(!document.querySelector(".color--border.element--active")){
            Swal.fire("Error","Por favor, elige el color del bocel","error");
            return false;
        }
    }else if(selectStyle.value == 3){
        if(!document.querySelector(".color--margin.element--active")){
            Swal.fire("Error","Por favor, elige el color del paspartú","error");
            return false;
        }
    }else if(selectStyle.value == 4){
        if(!document.querySelector(".color--margin.element--active")){
            Swal.fire("Error","Por favor, elige el color del fondo","error");
            return false;
        }
        if(!document.querySelector(".color--border.element--active")){
            Swal.fire("Error","Por favor, elige el color del marco interno","error");
            return false;
        }
    }
    if(sortFrame.value == 1){
        if(!document.querySelector(".color--frame.element--active")){
            Swal.fire("Error","Por favor, elige el color del marco","error");
            return false;
        }
    }
    let margin = marginRange.value;
    let styleFrame = selectStyle.value;
    let height = intHeight.value;
    let width = intWidth.value;
    let id = document.querySelector(".frame--item.element--active").getAttribute("data-id");
    let colorMargin = document.querySelector(".color--margin.element--active") ? document.querySelector(".color--margin.element--active").getAttribute("data-id") : 0;
    let colorBorder = document.querySelector(".color--border.element--active") ? document.querySelector(".color--border.element--active").getAttribute("data-id") : 0;
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
    formData.append("glass",selectGlass.options[selectGlass.selectedIndex].text);
    formData.append("material",sortFrame.options[sortFrame.selectedIndex].text);
    formData.append("idGlass",selectGlass.value);
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
function setDefaultConfig(){
    if(!document.querySelector(".frame--item.element--active")){
        document.querySelectorAll(".frame--item")[0].classList.add("element--active");
    }
    if(!document.querySelector(".color--frame.element--active")){
        document.querySelectorAll(".color--frame")[2].classList.add("element--active");
    }else if(sortFrame.value == 1){
        let bg = getComputedStyle(document.querySelector(".color--frame.element--active").children[0]).backgroundColor;
        layoutBorder.style.outlineColor=bg;
        document.querySelector("#frameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
        document.querySelector("#spcFrameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
    }else{
        document.querySelector("#spcFrameColor").innerHTML = "N/A";
        layoutBorder.style.outlineColor="transparent";
        selectColorFrame();
    }
    document.querySelectorAll(".orientation")[0].classList.add("element--active");
    calcularMarco();
}
function filterProducts(){
    let height = parseFloat(intHeight.value);
    let width = parseFloat(intWidth.value);
    let perimetro =(height+width)*2;
    if(perimetro > 240 && selectStyle.value != 4){
        selectStyle.value = 1;
        selectStyle.options[1].setAttribute("disabled","");
        selectStyle.options[2].setAttribute("disabled","");
        selectStyleFrame(1);
    }else if(perimetro <= 240){
        selectStyle.options[1].removeAttribute("disabled","");
        selectStyle.options[2].removeAttribute("disabled","");
    }
    let formData = new FormData();
    formData.append("height",height);
    formData.append("width",width);
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
            setDefaultConfig();
        }else{
            containerFrames.innerHTML = `<p class="fw-bold text-center">${objData.data}</p>`;
        }
    });

}
function selectOrientation(element){
    if(uploadPicture.value == ""){
        Swal.fire("Error","Por favor, sube la imagen a imprimir","error");
        return false;
    }
    let items = document.querySelectorAll(".orientation");
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove("element--active");
    }
    element.classList.add("element--active");
    document.querySelectorAll(".measures--input")[0].removeAttribute("disabled");
    document.querySelectorAll(".measures--input")[1].removeAttribute("disabled");
    btnNext.classList.remove("d-none");
    document.querySelector("#spcMeasureImg").innerHTML = intWidth.value+" x "+intHeight.value+"cm";
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
    let margin = selectStyle.value == 1 ? 0  : parseInt(marginRange.value);
    height = parseFloat(height);
    width = parseFloat(width)
    document.querySelector("#spcMeasureImg").innerHTML = width+" x "+height+"cm";
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
    document.querySelector("#marginData").innerHTML= margin+" cm";
    document.querySelector("#spcMeasureP").innerHTML= margin+" cm";
    document.querySelector("#spcMeasureFrame").innerHTML = (width+(margin*2))+" x "+(height+(margin*2))+"cm";
}
function selectStyleFrame(option){
    document.querySelector(".borderColor").classList.remove("d-none");
    document.querySelector("#spanP").innerHTML="Medida del paspartú";
    document.querySelector("#spanPC").innerHTML="Elige el color del paspartú";
    document.querySelector("#spanBorde").innerHTML="Elige el color del bocel";
    selectGlass.value = 2;
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
            marginRange.setAttribute("max",5);
        }else{
            document.querySelector("#spanP").innerHTML="Medida del fondo";
            document.querySelector("#spanPC").innerHTML="Elige el color del fondo";
            document.querySelector("#spanBorde").innerHTML="Elige el color del marco interno";
            selectColors(2);
            marginRange.setAttribute("max",10);
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
        marginRange.setAttribute("max",5);
        document.querySelector("#spcColorB").innerHTML ="N/A";
        document.querySelector("#spcMeasureP").innerHTML = "1cm";
        
        if(!document.querySelector(".color--margin.element--active")){
            document.querySelectorAll(".color--margin")[2].classList.add("element--active");
            layoutMargin.style.backgroundColor = getComputedStyle(document.querySelectorAll(".color--margin")[2]).backgroundColor;
            document.querySelector("#marginColor").innerHTML = "Blanco";
            document.querySelector("#spcColorP").innerHTML = "Blanco";
        }
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
function selectColorFrame(){
    for (let i = 0; i < colorFrame.length; i++) {
        let frame = colorFrame[i];
        if(frame.className.includes("element--active")){
            frame.classList.remove("element--active");
        }
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
function selectColors(option = null){
    if(option == 1){
        layoutImg.style.border="5px solid #fff";
        layoutMargin.style.backgroundColor="#000";
    }else if(option == 2){
        layoutImg.style.border="10px solid #fff";
        layoutMargin.style.backgroundColor="#000";
    }else{
        layoutImg.style.border="none";
        layoutMargin.style.backgroundColor="#000";
    }

    for (let i = 0; i < colorMargin.length; i++) {
        let margin = colorMargin[i];
        let border = colorBorder[i];

        if(margin.className.includes("element--active")){
            margin.classList.remove("element--active");
        }
        
        if(border.className.includes("element--active")){
            border.classList.remove("element--active");
        }
        margin.addEventListener("click",function(){
            let bg = getComputedStyle(margin.children[0]).backgroundColor;
            layoutMargin.style.backgroundColor=bg;
            document.querySelector("#marginColor").innerHTML = document.querySelector(".color--margin.element--active").getAttribute("title");
            document.querySelector("#spcColorP").innerHTML = document.querySelector(".color--margin.element--active").getAttribute("title");
        });
        border.addEventListener("click",function(){
            let bc = getComputedStyle(border.children[0]).backgroundColor;
            layoutImg.style.borderColor=bc;
            document.querySelector("#borderColor").innerHTML = document.querySelector(".color--border.element--active").getAttribute("title");
            document.querySelector("#spcColorB").innerHTML = document.querySelector(".color--border.element--active").getAttribute("title");
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
    let margin = marginRange.value;
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
