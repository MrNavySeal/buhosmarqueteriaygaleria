'use strict';

//let flag = true;
let modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
let modalView = new bootstrap.Modal(document.querySelector("#modalViewElement"));
let table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Marqueteria/getProducts",
        "dataSrc":""
    },
    columns: [
        { 
            data: 'image',
            render: function (data, type, full, meta) {
                return '<img src="'+data+'" class="rounded" height="50" width="50">';
            }
        },
        { data: 'reference'},
        { data: 'type' },
        { data: 'waste' },
        { data: 'price'},
        { data: 'discount' },
        { data: 'status' },
        { data: 'options' },
    ],
    responsive: true,
    buttons: [
        {
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Excel",
            "titleAttr":"Exportar a Excel",
            "className": "btn btn-success mt-2"
        }
    ],
    order: [[1, 'asc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    let btnNew = document.querySelector("#btnNew");
    btnNew.addEventListener("click",function(){
        let form = document.querySelector("#formItem");
        let formFile = document.querySelector("#formFile");
        form.reset();
        formFile.reset();
        if(document.querySelectorAll(".upload-image")){
            let divImg = document.querySelectorAll(".upload-image");
            for (let i = 0; i < divImg.length; i++) {
                divImg[i].remove();
            }
        }
        document.querySelector(".modal-title").innerHTML = "Nueva moldura";
        document.querySelector("#idProduct").value = "";
        document.querySelector("#txtReference").value = "";
        document.querySelector("#txtWaste").value = "";
        document.querySelector("#txtDiscount").value = "";
        document.querySelector("#txtPrice").value = "";
        document.querySelector("#statusList").value = 1;
        document.querySelector("#molduraList").value = 1;
        //flag = true;
        modal.show();
    });
}

if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    let formFile = document.querySelector("#formFile");
    let parent = document.querySelector("#upload-multiple");
    let img = document.querySelector("#txtImg");
    let btnAdd = document.querySelector("#btnAdd");
    
    setImage(img,parent,1);
    delImage(parent,1);

    form.addEventListener("submit",function(e){
        e.preventDefault();
        e.stopPropagation();
        let strName = document.querySelector("#txtReference").value;
        let molduraList = document.querySelector("#molduraList").value;
        let intDiscount = document.querySelector("#txtDiscount").value;
        let intPrice = document.querySelector("#txtPrice").value;
        let intStatus = document.querySelector("#statusList").value;
        let intWaste = document.querySelector("#txtWaste").value;
        let flag = true;
        let images = document.querySelectorAll(".upload-image");
        if(strName == "" || intStatus == "" || molduraList == "" ||  intPrice=="" || intWaste==""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(images.length < 1){
            Swal.fire("Error","Debe subir al menos una imagen","error");
            return false;
        }
        if(intPrice <= 0){
            Swal.fire("Error","El costo no puede ser menor o igual que 0 ","error");
            return false;
        }
        if(intWaste <= 0){
            Swal.fire("Error","El desperdicio no puede ser menor o igual que 0 ","error");
            return false;
        }
        if(intDiscount !=""){
            if(intDiscount < 0){
                Swal.fire("Error","El descuento no puede ser inferior a 0","error");
                document.querySelector("#txtDiscount").value="";
                return false;
            }else if(intDiscount > 90){
                Swal.fire("Error","El descuento no puede ser superior al 90%.","error");
                document.querySelector("#txtDiscount").value="";
                return false;
            }
        }
        
        
        let data = new FormData(form);
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        if(flag === true){
            request(base_url+"/marqueteria/setProduct",data,"post").then(function(objData){
                
                if(objData.status){
                    Swal.fire("Guardado",objData.msg,"success");
                    modalView.hide();
                    let divImg = document.querySelectorAll(".upload-image");
                    for (let i = 0; i < divImg.length; i++) {
                        divImg[i].remove();
                    }
                    form.reset();
                    formFile.reset();
                    table.ajax.reload();
                    modal.hide();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            flag = false;
        }
    },false);
}
    
function viewItem(id){
    let formData = new FormData();
    formData.append("idProduct",id);
    request(base_url+"/marqueteria/getProduct",formData,"post").then(function(objData){
        if(objData.status){
            let images = objData.data.image;
            let html = "";
            let type = "";
            let discount =objData.data.discount;
            let status = objData.data.status;
            for (let i = 0; i < images.length; i++) {
                html+=`
                    <div class="col-6 col-lg-3 upload-image mb-3">
                        <img src="${images[i]['url']}">
                    </div>
                `;
            }
            if(discount>0){
                discount = `<span class="text-success">${discount}% OFF</span>`
            }else{
                discount = `<span class="text-danger">0%</span>`
            }
            if(status==1){
                status='<span class="badge me-1 bg-success">Activo</span>';
            }else{
                status='<span class="badge me-1 bg-danger">Inactivo</span>';
            }
            if(objData.data.type == 1){
                type = 'Madera';
            }else if(objData.data.type == 3){
                type ="Madera diseño unico";
            }else{
                type = 'Poliestireno';
            }
            document.querySelector("#imgContainer").innerHTML = html;
            document.querySelector("#strFrame").setAttribute("src",base_url+"/Assets/images/uploads/"+objData.data.frame);
            document.querySelector("#strReference").innerHTML = objData.data.reference;
            document.querySelector("#strType").innerHTML = type;
            document.querySelector("#strWaste").innerHTML = objData.data.waste+" cm";
            document.querySelector("#strDiscount").innerHTML = discount;
            document.querySelector("#strStatus").innerHTML = status;
            document.querySelector("#strPrice").innerHTML = objData.data.priceFormat;
            modalView.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
    });
}
function editItem(id){ 
    let form = document.querySelector("#formItem");
    let formFile = document.querySelector("#formFile");
    form.reset();
    formFile.reset();
    let parent = document.querySelector("#upload-multiple");
    let formData = new FormData();
    formData.append("idProduct",id);

    request(base_url+"/marqueteria/getProduct",formData,"post").then(function(objData){
        let divImg = document.querySelectorAll(".upload-image");
        for (let i = 0; i < divImg.length; i++) {
            divImg[i].remove();
        }
        let images = objData.data.image;
        document.querySelector("#idProduct").value = objData.data.id;
        document.querySelector("#txtReference").value = objData.data.reference;
        document.querySelector("#txtWaste").value = objData.data.waste;
        document.querySelector("#txtDiscount").value = objData.data.discount;
        document.querySelector("#txtPrice").value = objData.data.price;
        document.querySelector("#statusList").value = objData.data.status;
        document.querySelector("#molduraList").value = objData.data.type;
        document.querySelector(".modal-title").innerHTML = "Actualizar moldura";
        //flag = true;
        if(images[0]!=""){
            for (let i = 0; i < images.length; i++) {
                let div = document.createElement("div");
                div.classList.add("col-6","col-lg-3","upload-image","mb-3");
                div.setAttribute("data-name",images[i]['name']);
                div.innerHTML = `
                        <img>
                        <div class="deleteImg" name="delete">x</div>
                `
                div.children[0].setAttribute("src",images[i]['url']);
                parent.appendChild(div);
            }
        }
    });

    modal.show();
}
function deleteItem(id){
    Swal.fire({
        title:"¿Estás seguro de eliminarlo?",
        text:"Se eliminará para siempre...",
        icon: 'warning',
        showCancelButton:true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:"Sí, eliminar",
        cancelButtonText:"No, cancelar"
    }).then(function(result){
        if(result.isConfirmed){
            let formData = new FormData();
            formData.append("idProduct",id);
            request(base_url+"/marqueteria/delProduct",formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    table.ajax.reload();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
}
function setImage(element,parent,option){
    let formFile = document.querySelector("#formFile");
    element.addEventListener("change",function(e){
        if(element.value!=""){
            let formImg = new FormData(formFile);
            uploadMultipleImg(element,parent);
            
            formImg.append("id","");
            
            if(option == 2){
                let images = document.querySelectorAll(".upload-image").length;
                formImg.append("images",images);
                formImg.append("id",document.querySelector("#idProduct").value);  
            }
            request(base_url+"/marqueteria/setImg",formImg,"post").then(function(objData){});
        }
    });
}
function delImage(parent,option){
    parent.addEventListener("click",function(e){
        if(e.target.className =="deleteImg"){
            let divImg = document.querySelectorAll(".upload-image");
            let deleteItem = e.target.parentElement;
            let nameItem = deleteItem.getAttribute("data-name");
            let imgDel;
            for (let i = 0; i < divImg.length; i++) {
                if(divImg[i].getAttribute("data-name")==nameItem){
                    deleteItem.remove();
                    imgDel = document.querySelectorAll(".upload-image");
                }
            }
            let url = base_url+"/marqueteria/delImg";
            let formDel = new FormData();

            formDel.append("id","");
            if(option == 2){
                formDel.append("id",document.querySelector("#idProduct").value);  
            }
            formDel.append("image",nameItem);
            request(url,formDel,"post").then(function(objData){});
        }
    });
}