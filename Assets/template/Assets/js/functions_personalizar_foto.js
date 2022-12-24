const DIMENSIONDEFAULT = 4;
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
let page = 0;
const toastLiveExample = document.getElementById('liveToast');


//----------------------------------------------
//[Change Pages]
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

//----------------------------------------------
//[Dimensions]
intHeight.addEventListener("change",function(){
    if(intHeight.value <= 10.0){
        intHeight.value = 10.0;
    }else if(intHeight.value >= 500.0){
        intHeight.value = 500.0;
    }
    calcPpi(intHeight.value,intWidth.value,document.querySelector(".layout--img img"));
    calcularMarco();
    resizeFrame(intWidth.value, intHeight.value);
    if(intHeight.value !="" && intWidth.value!="" && uploadPicture.value!=""){
        btnNext.classList.remove("d-none");
        let formData = new FormData();
        formData.append("height",intHeight.value);
        formData.append("width",intWidth.value);
        request(base_url+"/enmarcar/filterProducts",formData,"post").then(function(objData){
            if(objData.status){
                containerFrames.innerHTML = objData.data;
            }
        });
    }
});
intWidth.addEventListener("change",function(){
    if(intWidth.value <= 10.0){
        intWidth.value = 10.0;
    }else if(intWidth.value >= 500.0){
        intWidth.value = 500.0;
    }
    calcPpi(intHeight.value,intWidth.value,document.querySelector(".layout--img img"));
    calcularMarco();
    resizeFrame(intWidth.value, intHeight.value);
    if(intHeight.value !="" && intWidth.value!="" && uploadPicture.value!=""){
        btnNext.classList.remove("d-none");
        let formData = new FormData();
        formData.append("height",intHeight.value);
        formData.append("width",intWidth.value);
        request(base_url+"/enmarcar/filterProducts",formData,"post").then(function(objData){
            if(objData.status){
                containerFrames.innerHTML = objData.data;
            }
        });
    }
});
//----------------------------------------------
//[Zoom events]
rangeZoom.addEventListener("input",function(){
    layoutMargin.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutImg.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
}); 
minusZoom.addEventListener("click",function(){
    rangeZoom.value = parseInt(rangeZoom.value)-10;
    layoutMargin.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutImg.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
});
plusZoom.addEventListener("click",function(){
    rangeZoom.value = parseInt(rangeZoom.value)+10;
    layoutMargin.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
    layoutImg.style.transform = "translate(-50%,-50%) scale("+(rangeZoom.value/100)+")";
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
    if(uploadPicture.value !=""){
        uploadImg(uploadPicture,".layout--img img");
        if(intHeight.value !="" && intWidth.value!=""){
            btnNext.classList.remove("d-none");
        }
        if(document.querySelector(".orientation.element--active")){
            btnNext.classList.remove("d-none");
        }
    }else{
        btnNext.classList.add("d-none");
    }
    setTimeout(function() {
        calcDimension(document.querySelector(".layout--img img"));
        calcularMarco();
    }, 100);
    
    //console.log(resolution);
});


//----------------------------------------------
//[Frame custom]

searchFrame.addEventListener('input',function() {
    if(intWidth.value !="" && intHeight.value!=""){
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
        request(base_url+"/enmarcar/search",formData,"post").then(function(objData){
            if(objData.status){
                containerFrames.innerHTML = objData.data;
            }else{
                containerFrames.innerHTML = `<p class="fw-bold text-center">${objData.data}</p>`;
            }
        });
    }
});

sortFrame.addEventListener("change",function(){
    if(intWidth.value !="" && intHeight.value!=""){
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
        return false;
    }
    selectStyleFrame(selectStyle.value);
    calcularMarco();
});
//----------------------------------------------
//[Add frame]
addFrame.addEventListener("click",function(){
    let formData = new FormData(document.querySelector("#formPicture"));

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
            Swal.fire("Error","Por favor, elige el color del margen","error");
            return false;
        }
        if(!document.querySelector(".color--border.element--active")){
            Swal.fire("Error","Por favor, elige el color del borde","error");
            return false;
        }
    }else if(selectStyle.value == 3){
        if(!document.querySelector(".color--margin.element--active")){
            Swal.fire("Error","Por favor, elige el color del margen","error");
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
    formData.append("type",type);
    formData.append("idType",idType);
    formData.append("route",route);
    formData.append("orientation",orientation);


    addFrame.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    addFrame.setAttribute("disabled","");

    request(base_url+"/enmarcar/addCart",formData,"post").then(function(objData){
        addFrame.innerHTML=`Agregar`;
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

function selectOrientation(element){
    let items = document.querySelectorAll(".orientation");
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove("element--active");
    }
    element.classList.add("element--active");
    document.querySelectorAll(".measures--input")[0].removeAttribute("disabled");
    document.querySelectorAll(".measures--input")[1].removeAttribute("disabled");
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

    height = parseFloat(height) *DIMENSIONDEFAULT;
    width = parseFloat(width) *DIMENSIONDEFAULT;

    let heightM = height;
    let widthM = width;
    let margin = parseInt(marginRange.value);
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
}
function customMargin(margin){
    marginRange.value = margin;
    let marginHeight = (parseFloat(intHeight.value)*DIMENSIONDEFAULT) + (margin*10);
    let marginWidth = (parseFloat(intWidth.value)*DIMENSIONDEFAULT) + (margin*10);
    layoutMargin.style.height = `${marginHeight}px`;
    layoutMargin.style.width = `${marginWidth}px`;
    document.querySelector("#marginData").innerHTML= margin+" cm";
}
function selectStyleFrame(option){
    document.querySelector(".borderColor").classList.remove("d-none");
    if(option == 1){
        optionsCustom[0].classList.add("d-none");
        optionsCustom[1].classList.add("d-none");
        customMargin(0);
        selectColors();
    }else if(option == 2 || option == 4){
        optionsCustom[0].classList.remove("d-none");
        optionsCustom[1].classList.add("d-none");
        customMargin(1);
        if(option==2){
            selectColors(1);
        }else{
            selectColors(2);
        }
    }else if(option == 3 || option == 5){
        optionsCustom[0].classList.remove("d-none");
        optionsCustom[1].classList.add("d-none");
        document.querySelector(".borderColor").classList.add("d-none");
        customMargin(1);
        selectColors(0);
    }else{
        customMargin(0);
        selectColors();
        optionsCustom[0].classList.add("d-none");
        optionsCustom[1].classList.remove("d-none");
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
        });
        border.addEventListener("click",function(){
            let bc = getComputedStyle(border.children[0]).backgroundColor;
            layoutImg.style.borderColor=bc;
            document.querySelector("#borderColor").innerHTML = document.querySelector(".color--border.element--active").getAttribute("title");
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
    let type = document.querySelector("#enmarcarTipo").getAttribute("data-id");

    let formData = new FormData();
    formData.append("height",height);
    formData.append("width",width);
    formData.append("style",styleFrame);
    formData.append("margin",margin);
    formData.append("id",id);
    formData.append("type",type);

    document.querySelectorAll(".totalFrame")[0].innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    document.querySelectorAll(".totalFrame")[1].innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/enmarcar/calcularMarcoTotal",formData,"post").then(function(objData){
        if(objData.status){
            let data = objData.data;
            let borderImage = `url(${base_url}/Assets/images/uploads/${data.frame}) 40% repeat`;
            document.querySelector("#reference").innerHTML = "Ref: "+data.reference;
            document.querySelectorAll(".totalFrame")[0].innerHTML = data.total.format;
            document.querySelectorAll(".totalFrame")[1].innerHTML = data.total.format;
            layoutMargin.style.borderImage= borderImage;
            layoutMargin.style.borderWidth = (data.waste/1.5)+"px";
            layoutMargin.style.borderImageOutset = (data.waste/1.5)+"px";
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
    
    let realHeight = picture.naturalHeight;
    let realWidth = picture.naturalWidth;

    let height = Math.round((realHeight*2.54)/100) < 10 ? 10 :  Math.round((realHeight*2.54)/100);
    let width = Math.round((realWidth*2.54)/100) < 10 ? 10 :  Math.round((realWidth*2.54)/100);

    intHeight.value = height;
    intWidth.value = width;
    if(document.querySelector(".orientation.element--active")){
        btnNext.classList.remove("d-none");
    }
    resizeFrame(intWidth.value,intHeight.value);

    imgQuality.innerHTML = `Resolución 100 ppi <span class="text-success">buena calidad</span>`
}
function calcPpi(height,width,picture){
    
    let realHeight = picture.naturalHeight;
    let realWidth = picture.naturalWidth;

    let h = Math.round((realHeight*2.54)/height);
    let w = Math.round((realWidth*2.54)/width);
    let ppi = Math.round((h+w))/2;

    if(ppi<100){
        imgQuality.innerHTML = `Resolución ${ppi} ppi <span class="text-danger">mala calidad</span>, puedes reducir las dimensiones o cambiar de imagen`;
    }else{
        imgQuality.innerHTML = `Resolución ${ppi} ppi <span class="text-success">buena calidad</span>`;
    }

}
