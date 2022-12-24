const DIMENSIONDEFAULT = 4;
const rangeZoom = document.querySelector("#zoomRange");
const minusZoom = document.querySelector("#zoomMinus");
const plusZoom = document.querySelector("#zoomPlus");
const intHeight = document.querySelector("#intHeight");
const intWidth = document.querySelector("#intWidth");
const layoutImg = document.querySelector(".layout--img");
const layoutMargin = document.querySelector(".layout--margin");
const selectStyle = document.querySelector("#selectStyle");
const btnBack = document.querySelector("#btnBack");
const btnNext = document.querySelector("#btnNext");
const pages = document.querySelectorAll(".page");
const containerFrames = document.querySelector(".select--frames");
const searchFrame = document.querySelector("#searchFrame");
const sortFrame = document.querySelector("#sortFrame");
const addFrame = document.querySelector("#addFrame");
const toastLiveExample = document.getElementById('liveToast');
let page = 0;

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
    calcularMarco();
    resizeFrame(intWidth.value, intHeight.value);
    if(intHeight.value !="" && intWidth.value!=""){
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
    calcularMarco();
    resizeFrame(intWidth.value, intHeight.value);
    if(intHeight.value !="" && intWidth.value!=""){
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
//[Select style]
selectStyle.addEventListener("change",function(){
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        return false;
    }
    calcularMarco();
});

//----------------------------------------------
//[Quantity btns]

const btnFPlus = document.querySelector("#btnIncrement");
const btnFMinus = document.querySelector("#btnDecrement");
const intFQty = document.querySelector("#txtQty");

btnFPlus.addEventListener("click",function(){
    if(intFQty.value >=99){
        intFQty.value = 99;
    }else{
        ++intFQty.value; 
    }
});
btnFMinus.addEventListener("click",function(){
    if(intFQty.value <=1){
        intFQty.value = 1;
    }else{
        --intFQty.value; 
    }
});

intFQty.addEventListener("input",function(){
    if(intFQty.value >= 99){
        intFQty.value= 99;
    }else if(intFQty.value <= 1){
        intFQty.value= 1;
    }
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

    let margin = 0;
    let styleFrame = selectStyle.value;
    let height = intHeight.value;
    let width = intWidth.value;
    let id = document.querySelector(".frame--item.element--active").getAttribute("data-id");
    let colorMargin = 0;
    let colorBorder = 0;
    let route = document.querySelector("#enmarcarTipo").getAttribute("data-route");
    let type = document.querySelector("#enmarcarTipo").getAttribute("data-name");
    let orientation = document.querySelector(".orientation.element--active").getAttribute("data-name");
    let idType = document.querySelector("#enmarcarTipo").getAttribute("data-id");
    formData.append("height",height);
    formData.append("width",width);
    formData.append("styleValue",styleFrame);
    formData.append("styleName",selectStyle.options[selectStyle.selectedIndex].text);
    formData.append("margin",margin);
    formData.append("qty",intFQty.value);
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

    height = parseFloat(height) *DIMENSIONDEFAULT;
    width = parseFloat(width) *DIMENSIONDEFAULT;

    let heightM = height;
    let widthM = width;
    let margin = 0;
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
