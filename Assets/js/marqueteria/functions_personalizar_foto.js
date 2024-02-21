const DIMENSIONDEFAULT = 4;
const MAXDIMENSION = 500;

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
    setDefaultConfig();
})
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
    let height = intHeight.value;
    let width = intWidth.value;
    if(intHeight.value <= 10.0){
        intHeight.value = 10.0;
    }
    if(height >= MAXDIMENSION){
        intHeight.value = MAXDIMENSION;
    }
    setDefaultConfig();
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
});
//----------------------------------------------



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
        request(base_url+"/marqueteria/sort",formData,"post").then(function(objData){
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
marginRange.addEventListener("input",function(){
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        return false;
    }
    document.querySelector("#marginData").innerHTML = marginRange.value+"cm";
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
selectGlass.addEventListener("change",function(){
    if(!document.querySelector(".frame--item.element--active")){
        Swal.fire("Error","Por favor, seleccione la moldura","error");
        return false;
    }
    calcularMarco();
});
//--
//----------------------------------------------
function setDefaultConfig(){
    if(!document.querySelector(".frame--item.element--active")){
        document.querySelectorAll(".frame--item")[0].classList.add("element--active");
    }
    calcularMarco();
}

function selectActive(element =null,elements=null){
    let items = document.querySelectorAll(`${elements}`);
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove("element--active");
    }
    element.classList.add("element--active");
}

function selectStyleFrame(option){
    if(option == 1){
        optionsCustom[0].classList.add("d-none");
    }else if(option == 2 || option == 4){
        optionsCustom[0].classList.remove("d-none");

    }else if(option == 3){
        optionsCustom[0].classList.remove("d-none");

    }else if(option == 5){
        document.querySelector("#glassDiv").classList.add("d-none");
        selectGlass.value = 3;
        optionsCustom[0].classList.add("d-none");
    }else{
        optionsCustom[0].classList.add("d-none");
    }

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

    request(base_url+"/marqueteria/calcularMarcoTotal",formData,"post").then(function(objData){
        if(objData.status){
            const data = objData.data;
            const cost = data.costo;
            if(selectStyle.value == 1){
                cost.paspartu = "$0";
                cost.bocel = "$0";
                cost.hijillo = "$0";
                cost.triplex = "$0";
            }else if(selectStyle.value == 2){
                cost.hijillo = "$0";
                cost.triplex = "$0";
            }else if(selectStyle.value == 3){
                cost.bocel = "$0";
                cost.hijillo = "$0";
                cost.triplex = "$0";
            }else if(selectStyle.value == 4){
                cost.paspartu = "$0";
                cost.bocel = "$0";
            }else if(selectStyle.value == 5){
                cost.paspartu = "$0";
                cost.bocel = "$0";
                cost.hijillo = "$0";
                cost.triplex = "$0";
                cost.vidrio = "$0";
            }
            document.querySelector("#costMarco").innerHTML = cost.marco;
            document.querySelector("#costMDF").innerHTML = cost.mdf;
            document.querySelector("#costPaspartu").innerHTML = cost.paspartu;
            document.querySelector("#costBocel").innerHTML = cost.bocel;
            document.querySelector("#costHijillo").innerHTML = cost.hijillo;
            document.querySelector("#costVidrio").innerHTML = cost.vidrio;
            document.querySelector("#costTriplex").innerHTML = cost.triplex;
            document.querySelector("#costImpresion").innerHTML = cost.impresion;
            document.querySelector("#costTotal").innerHTML = "-"+data.total.format;
            document.querySelector("#price").innerHTML = "+"+data.total.price;
            document.querySelector("#utilidad").innerHTML = data.total.utilidad;
        }
    });
}

