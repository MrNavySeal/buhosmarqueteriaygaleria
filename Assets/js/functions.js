'use strict'
function printDiv(element,title=null){
    
    let div = document.querySelector("#"+element);
    let style =`
    background-color:#fff;
    height:100vh;
    width:100vw;
    position:absolute !important;
    top:0;
    left:0;
    z-index:9999;
    margin:0;
    padding:0;
    page-break-after: always;
    `;
    
    div.setAttribute("style",style);
    document.querySelector("body").innerHTML = div.innerHTML;
    if(title!=null){
        document.querySelector("title").innerHTML = title;
    }
    window.print();
    window.location.reload();
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
function exportToExcel(id){
    var wb = XLSX.utils.table_to_book(document.getElementById(id));
    XLSX.writeFile(wb, id+".xlsx");
}
function setTinymce(selectorId,height=null){
    tinymce.remove();
    document.addEventListener('focusin', (e) => {
        if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
            e.stopImmediatePropagation();
        }
    });
    if(height==null){
        height = 400;
    }

    tinymce.init({
        relative_urls: false,
        remove_script_host: false,
        selector: selectorId,
        height: height,
        images_upload_url: base_url+'/UploadImages/UploadImages',
        images_upload_handler:image_upload_handler_callback,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'code image'
        ],
        toolbar: 'undo redo | image code | blocks | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
        force_br_newlines : false
    });
    
}
const image_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.withCredentials = false;
    xhr.open('POST', base_url+'/UploadImages/UploadImages');
    
    xhr.upload.onprogress = (e) => {
        progress(e.loaded / e.total * 100);
    };
    
    xhr.onload = () => {
        if (xhr.status === 403) {
            reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
            return;
        }
      
        if (xhr.status < 200 || xhr.status >= 300) {
            reject('HTTP Error: ' + xhr.status);
            return;
        }
      
        const json = JSON.parse(xhr.responseText);
      
        if (!json || typeof json.location != 'string') {
            reject('Invalid JSON: ' + xhr.responseText);
            return;
        }
      
        resolve(json.location);
    };
    
    xhr.onerror = () => {
      reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
    };
    
    const formData = new FormData();
    formData.append('file', blobInfo.blob(), blobInfo.filename());
    
    xhr.send(formData);
});
function uploadMultipleImg(img,parent){
    let value = img.value;
    let files = img.files;
    for (let i = 0; i < files.length; i++) {
        if(files[i].type != "image/png" && files[i].type != "image/jpg" && files[i].type != "image/jpeg" && files[i].type != "image/gif"){
            Swal.fire("Error","Solo se permite imágenes","error");
            value ="";
        }else{
            let div = document.createElement("div");
            div.classList.add("col-6","col-lg-3","upload-image","mb-3");
            div.setAttribute("data-name",files[i].name);
            div.innerHTML = `
                    <img>
                    <div class="deleteImg" name="delete">x</div>
            `
            let objectUrl = window.URL || window.webkitURL;
            let route = objectUrl.createObjectURL(files[i]);
            div.children[0].setAttribute("src",route);
            parent.appendChild(div);
        }   
    }
    document.querySelector("#formFile").reset();
}
function formatNum(num,mil){
    let numero = num;
    let format = mil;

    const noTruncarDecimales = {maximumFractionDigits: 20};

    if(format == ","){
        format = numero.toLocaleString('en-US', noTruncarDecimales);
    }else if(mil == "."){
        format  = numero.toLocaleString('es', noTruncarDecimales);
    }
    return format;   
}
async function request(url,requestData,option){
    let data ="";
    option.toLowerCase();
    if(option=='post'){
        option = {
            cors: 'same-origin',
            method: 'post',
            body:requestData,
            cache: 'no-cache'
        }
    }else{
        option = {
            method: 'get',
            cache: 'no-cache'
        }
    }
    try {
        let request = await fetch(url,option);
        data = await request.json();
        return data;
    } catch (error) {
        console.log("There was a problem with the request: "+error.message);
    }
}
function controlTag(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; 
    else if (tecla==0||tecla==9)  return true;
    patron =/[0-9\s]/;
    n = String.fromCharCode(tecla);
    return patron.test(n); 
}
function testText(txtString){
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]+$/);
    if(stringText.test(txtString)){
        return true;
    }else{
        return false;
    }
}
function testEntero(intCant){
    var intCantidad = new RegExp(/^([0-9])*$/);
    if(intCantidad.test(intCant)){
        return true;
    }else{
        return false;
    }
}
function fntEmailValidate(email){
    var stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})$/);
    if (stringEmail.test(email) == false){
        return false;
    }else{
        return true;
    }
}
function fntValidText(){
	let validText = document.querySelectorAll(".validText");
    validText.forEach(function(validText) {
        validText.addEventListener('keyup', function(){
			let inputValue = this.value;
			if(!testText(inputValue)){
				this.classList.add('is-invalid');
			}else{
				this.classList.remove('is-invalid');
			}				
		});
	});
}
function fntValidNumber(){
	let validNumber = document.querySelectorAll(".validNumber");
    validNumber.forEach(function(validNumber) {
        validNumber.addEventListener('keyup', function(){
			let inputValue = this.value;
			if(!testEntero(inputValue)){
				this.classList.add('is-invalid');
			}else{
				this.classList.remove('is-invalid');
			}				
		});
	});
}
function fntValidEmail(){
	let validEmail = document.querySelectorAll(".validEmail");
    validEmail.forEach(function(validEmail) {
        validEmail.addEventListener('keyup', function(){
			let inputValue = this.value;
			if(!fntEmailValidate(inputValue)){
				this.classList.add('is-invalid');
			}else{
				this.classList.remove('is-invalid');
			}				
		});
	});
}
if(document.querySelector("#exportExcel")){
    document.querySelector("#exportExcel").addEventListener("click",function(){
        let id = document.querySelector("#exportExcel").getAttribute('data-name');
        exportToExcel(id);
    })
}
window.addEventListener('load', function() {
	fntValidText();
	fntValidEmail(); 
	fntValidNumber();
}, false);