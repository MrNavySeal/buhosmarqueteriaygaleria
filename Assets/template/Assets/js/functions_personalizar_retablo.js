const DIMENSIONDEFAULT = 4;
const rangeZoom = document.querySelector("#zoomRange");
const minusZoom = document.querySelector("#zoomMinus");
const plusZoom = document.querySelector("#zoomPlus");
const intHeight = document.querySelector("#intHeight");
const intWidth = document.querySelector("#intWidth");
const cube = document.querySelector(".cube-container");
const colorMargin = document.querySelectorAll(".color--margin");
const addFrame = document.querySelector("#addFrame");
const uploadPicture = document.querySelector("#txtPicture");
const toastLiveExample = document.getElementById('liveToast');
const imgQuality = document.querySelector("#imgQuality");
const selectStyle = document.querySelector("#selectStyle");
let page = 0;
selectColors();

//----------------------------------------------
//[Dimensions]
intHeight.addEventListener("change",function(){
    if(intHeight.value <= 10.0){
        intHeight.value = 10.0;
    }else if(intHeight.value >= 300.0){
        intHeight.value = 300.0;
    }
    calcPpi(intHeight.value,intWidth.value,document.querySelector(".layout--face.face-front img"));
    calcularMarco();
    resizeFrame(intWidth.value, intHeight.value);
});
intWidth.addEventListener("change",function(){
    if(intWidth.value <= 10.0){
        intWidth.value = 10.0;
    }else if(intWidth.value >= 300.0){
        intWidth.value = 300.0;
    }
    calcPpi(intHeight.value,intWidth.value,document.querySelector(".layout--face.face-front img"));
    calcularMarco();
    resizeFrame(intWidth.value, intHeight.value);
});
//----------------------------------------------
//[Zoom events]
rangeZoom.addEventListener("input",function(){
    cube.style.transform = "translate(-50%, -50%) scale("+(rangeZoom.value/100)+")";
}); 
minusZoom.addEventListener("click",function(){
    rangeZoom.value = parseInt(rangeZoom.value)-10;
    cube.style.transform = "translate(-50%, -50%) scale("+(rangeZoom.value/100)+")";
});
plusZoom.addEventListener("click",function(){
    rangeZoom.value = parseInt(rangeZoom.value)+10;
    cube.style.transform = "translate(-50%, -50%) scale("+(rangeZoom.value/100)+")";
});

//----------------------------------------------
//[upload image]

uploadPicture.addEventListener("change",function(){
    if(uploadPicture.value !=""){
        uploadImg(uploadPicture,".layout--face.face-front img");
        setTimeout(function() {
            calcDimension(document.querySelector(".layout--face.face-front img"));
            calcularMarco();
        }, 100);
    }
});

//[Select style]
selectStyle.addEventListener("change",function(){
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
    let formData = new FormData(document.querySelector("#formPicture"));

    if(intHeight.value =="" || intWidth.value==""){
        Swal.fire("Error","Por favor, ingresa las medidas","error");
        return false;
    }
    if(!document.querySelector(".color--margin.element--active")){
        Swal.fire("Error","Por favor, elige el color del borde","error");
        return false;
    }
    let margin = 0;
    let styleFrame = 2;
    let height = intHeight.value;
    let width = intWidth.value;
    let id = 0;
    let colorMargin = document.querySelector(".color--margin.element--active").getAttribute("data-id");
    let route = document.querySelector("#enmarcarTipo").getAttribute("data-route");
    let type = document.querySelector("#enmarcarTipo").getAttribute("data-name");
    let orientation = "";
    let idType = document.querySelector("#enmarcarTipo").getAttribute("data-id");

    if(uploadPicture.value !=""){
        styleFrame = selectStyle.value;
        document.querySelector(".retablo").classList.remove("d-none");
    }

    formData.append("height",height);
    formData.append("width",width);
    formData.append("styleValue",styleFrame);
    formData.append("styleName",selectStyle.options[selectStyle.selectedIndex].text);
    formData.append("margin",margin);
    formData.append("qty",intFQty.value);
    formData.append("id",id);
    formData.append("colorMargin",0);
    formData.append("colorBorder",colorMargin);
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
    cube.style.height = `${height}px`;
    cube.style.width = `${width}px`;
    document.querySelector(".face-right").style.transform=`rotateY(90deg) translateZ(${width-10}px)`;
}
function selectColors(option = null){
    for (let i = 0; i < colorMargin.length; i++) {
        let margin = colorMargin[i];

        if(margin.className.includes("element--active")){
            margin.classList.remove("element--active");
        }
        margin.addEventListener("click",function(){
            let bg = getComputedStyle(margin.children[0]).backgroundColor;
            document.querySelector(".face-right").style.backgroundColor=bg;
            document.querySelector(".face-superior").style.backgroundColor=bg;
            document.querySelector("#marginColor").innerHTML = document.querySelector(".color--margin.element--active").getAttribute("title");
        });
    }
}
function calcularMarco(id=null){
    let margin = 0;
    let styleFrame = 2;
    let height = intHeight.value;
    let width = intWidth.value;
    if(uploadPicture.value !=""){
        styleFrame = selectStyle.value;
        document.querySelector(".retablo").classList.remove("d-none");
    }else{
        document.querySelector(".retablo").classList.add("d-none");
    }
    let type = document.querySelector("#enmarcarTipo").getAttribute("data-id");

    let formData = new FormData();
    formData.append("height",height);
    formData.append("width",width);
    formData.append("style",styleFrame);
    formData.append("margin",margin);
    formData.append("id",0);
    formData.append("type",type);

    document.querySelector(".totalFrame").innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    request(base_url+"/enmarcar/calcularMarcoTotal",formData,"post").then(function(objData){
        if(objData.status){
            let data = objData.data;
            let borderImage = `url(${base_url}/assets/images/uploads/${data.frame}) 40% repeat`;
            document.querySelector(".totalFrame").innerHTML = data.total.format;
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
    
        let height = Math.round((realHeight*2.54)/100) < 10 ? 10 :  Math.round((realHeight*2.54)/100);
        let width = Math.round((realWidth*2.54)/100) < 10 ? 10 :  Math.round((realWidth*2.54)/100);
    
        if(height > 300){
            height = 300;
        }
        if(width > 300){
            width = 300;
        }
        intHeight.value = height;
        intWidth.value = width;
        resizeFrame(intWidth.value,intHeight.value);
    
        imgQuality.innerHTML = `Resolución 100 ppi <span class="text-success">buena calidad</span>`
    }
}
function calcPpi(height,width,picture){
    if(uploadPicture.value !=""){
        let realHeight = picture.naturalHeight;
        let realWidth = picture.naturalWidth;
    
        let h = Math.round((realHeight*2.54)/height);
        let w = Math.round((realWidth*2.54)/width);
        let ppi = Math.round((h+w)/2);
    
        if(ppi<100){
            imgQuality.innerHTML = `Resolución ${ppi} ppi <span class="text-danger">mala calidad</span>, puedes reducir las dimensiones o cambiar de imagen`;
        }else{
            imgQuality.innerHTML = `Resolución ${ppi} ppi <span class="text-success">buena calidad</span>`;
        }
    }

}
