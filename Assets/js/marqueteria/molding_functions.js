import * as props from './molding_props.js';

export function setDefaultConfig(){
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
    //document.querySelectorAll(".orientation")[0].classList.add("element--active");
    calcularMarco();
}
export function filterProducts(){
    let height = parseFloat(props.intHeight.value);
    let width = parseFloat(props.intWidth.value);
    let perimetro =(height+width)*2;
    if(perimetro > 240 && props.selectStyle.value != 4){
        props.selectStyle.value = 1;
        props.selectStyle.options[1].setAttribute("disabled","");
        props.selectStyle.options[2].setAttribute("disabled","");
        selectStyleFrame(1);
    }else if(perimetro <= 240){
        props.selectStyle.options[1].removeAttribute("disabled","");
        props.selectStyle.options[2].removeAttribute("disabled","");
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
export function selectOrientation(element){
    if(isPrint.getAttribute("data-print") != 0){
        if(uploadPicture.value == ""){
            Swal.fire("Error","Por favor, sube la imagen a imprimir","error");
            return false;
        }
    }
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
export function selectActive(element =null,elements=null){
    let items = document.querySelectorAll(`${elements}`);
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove("element--active");
    }
    element.classList.add("element--active");
}
export function resizeFrame(width,height){
    let selectStyle = props.selectStyle[0];
    console.log(selectStyle);
    let margin = 0;
    if(selectStyle.getAttribute("data-ismargin") != 0){
        margin = parseInt(selectStyle.value);
    }
    height = parseFloat(height);
    width = parseFloat(width)

    height = height *props.DIMENSIONDEFAULT;
    width = width *props.DIMENSIONDEFAULT;

    let heightM = height;
    let widthM = width;
    let styleMargin = getComputedStyle(props.layoutMargin).height;
    let styleImg = getComputedStyle(props.layoutImg).height;
    styleMargin = parseInt(styleMargin.replace("px",""));
    styleImg = parseInt(styleImg.replace("px",""));
    if(styleMargin > styleImg){
        heightM = heightM +(margin*10);
        widthM = widthM +(margin*10); 
    }

    props.layoutImg.style.height = `${height}px`;
    props.layoutImg.style.width = `${width}px`;
    props.layoutMargin.style.height = `${heightM}px`;
    props.layoutMargin.style.width = `${widthM}px`;
    props.layoutBorder.style.height = `${heightM}px`;
    props.layoutBorder.style.width = `${widthM}px`;
}
export function customMargin(margin){
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
export function selectStyleFrame(option){
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
export function selectColorFrame(){
    for (let i = 0; i < props.colorFrame.length; i++) {
        let frame = props.colorFrame[i];
        if(frame.className.includes("element--active")){
            frame.classList.remove("element--active");
        }
        frame.addEventListener("click",function(){
            if(!document.querySelector(".frame--item.element--active")){
                Swal.fire("Error","Por favor, seleccione la moldura","error");
                return false;
            }
            let bg = getComputedStyle(frame.children[0]).backgroundColor;
            props.layoutBorder.style.outlineColor=bg;
            document.querySelector("#frameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
            document.querySelector("#spcFrameColor").innerHTML = document.querySelector(".color--frame.element--active").getAttribute("title");
        });
    }
}
export function selectColors(option = null){
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
export function calcularMarco(id=null){
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
export function uploadImg(img,location){
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
export function calcDimension(picture){
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
export function calcPpi(height,width,picture){
    
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
export function showImages(images){
    let html="";
    for (let i = 0; i < images.length; i++) {
        html+=`<div class="product-image-item"><img src="${images[i]}" alt=""></div>`;
    }
    return html;
}
export function clickShowImages(){
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