let modalFrame = "";
let objProduct = {};
const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
const modalView = document.querySelector("#modalElementView") ? new bootstrap.Modal(document.querySelector("#modalElementView")) :"";
const table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/MarqueteriaEjemplos/getExamples",
        "dataSrc":""
    },
    columns: [
        { data: 'id'},
        { 
            data: 'img',
            render: function (data, type, full, meta) {
                return '<img src="'+data+'" class="rounded" height="50" width="50">';
            }
        },
        { data: 'category'},
        { data: 'name'},
        { data: 'total'},
        { data: 'date'},
        { data: 'order_view'},
        { data: 'is_visible'},
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
    order: [[0, 'desc']],
    pagingType: 'full',
    scrollY:'400px',
    //scrollX: true,
    "aProcessing":true,
    "aServerSide":true,
    "iDisplayLength": 10,
});

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
    const btnNew = document.querySelector("#btnNew");
    const modalFrameCategory = new bootstrap.Modal(document.querySelector("#modalFrameSetExample"));
    modalFrame = new bootstrap.Modal(document.querySelector("#modalFrame"));
    const tableMolding = new DataTable("#tableMolding",{
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/MarqueteriaEjemplos/getMoldingProducts",
            "dataSrc":""
        },
        "initComplete":function( settings, json){
            //arrProducts = json;
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'options' },
        ],
        responsive: true,
        order: [[0, 'asc']],
        pagingType: 'full',
        scrollY:'400px',
        //scrollX: true,
        "aProcessing":true,
        "aServerSide":true,
        "iDisplayLength": 10,
    });
    btnNew.addEventListener("click",function(){
        document.querySelector("#idExample").value = 0;
        document.querySelector("#modalTitleFrame").innerHTML = "Nuevo ejemplo";
        document.querySelector("#strDate").value = new Date().toISOString().split('T')[0];
        document.querySelector(".uploadImg img").setAttribute("src",base_url+"/assets/images/uploads/category.jpg");
        document.querySelector("#orderList").value = 5;
        document.querySelector("#isVisible").checked = 0;
        document.querySelector("#strReview").value = "";
        document.querySelector("#strName").value = "";
        modalFrameCategory.show();
    });
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    let img = document.querySelector("#txtImg");
    let imgLocation = ".uploadImg img";
    img.addEventListener("change",function(){
        uploadImg(img,imgLocation);
    });
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strName = document.querySelector("#strName").value;
        let intId = document.querySelector("#idExample").value;
        let isVisible = document.querySelector("#isVisible").checked;
        if(strName == ""){
            Swal.fire("Error","Los campos con (*) son obligatorios","error");
            return false;
        }
        if(intId == 0){
            if(!objProduct.name){
                Swal.fire("Error","Debe realizar una emarcación para guardar el ejemplo","error");
                return false;
            }
        }
        let url = base_url+"/MarqueteriaEjemplos/setExample";
        let formData = new FormData(form);
        formData.append("frame",JSON.stringify(objProduct));
        formData.append("is_visible",isVisible ? 1 : 0);
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        request(url,formData,"post").then(function(objData){
            btnAdd.innerHTML=`<i class="fas fa-save"></i> Guardar`;
            btnAdd.removeAttribute("disabled");
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
                table.ajax.reload();
                form.reset();
                modal.hide();
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        });
    })
}
function addProduct(product={},topic=1){
    const defaultFrame = document.querySelector(".frame--item.element--active");
    const props = Array.from(document.querySelectorAll(".selectProp"));
    const intMargin = parseInt(props[0].getAttribute("data-margin"));
    let colorFrameId ="";
    let colorMarginId ="";
    let colorBorderId="";
    if(document.querySelector(".color--frame")){
        colorFrameId = document.querySelector(".color--frame.element--active").getAttribute("data-id");
    }
    if(document.querySelector(".color--margin")){
        colorMarginId = document.querySelector(".color--margin.element--active").getAttribute("data-id");
    }
    if(document.querySelector(".color--border")){
        colorBorderId = document.querySelector(".color--border.element--active").getAttribute("data-id");
    }
    objProduct.price_sell =totalFrame;
    objProduct.config = arrConfigFrame.props;
    objProduct.data = {name:nameTopic,detail:product};
    objProduct.name =nameTopic;
    objProduct.img = imageUrl;
    objProduct.id=defaultFrame.getAttribute("data-id");
    objProduct.height = intHeight.value;
    objProduct.width = intWidth.value;
    objProduct.margin = intMargin;
    objProduct.id_config = document.querySelector("#idCategory").value;
    objProduct.orientation = document.querySelector(".orientation.element--active").getAttribute("data-name");
    objProduct.color_frame_id = colorFrameId;
    objProduct.color_margin_id = colorMarginId;
    objProduct.color_border_id = colorBorderId;
    objProduct.type_frame = sortFrame.getAttribute("data-id");
    const specs = product;
    let html="";
    specs.forEach(e => {
        html+=`
        <div class="col-md-4">
            <div class="mb-3">
                <label for="" class="form-label fw-bold">${e.name}</label>
                <p class="text-break" id="strDate">${e.value}</p>
            </div>
        </div>
        `
    });
    document.querySelector("#frameDescription").innerHTML = html;
    document.querySelector("#strType").innerHTML = objProduct.name;
    modal.show();
}
function viewItem(id){
    let formData = new FormData();
    formData.append("id",id);
    request(base_url+"/MarqueteriaEjemplos/getExample",formData,"post").then(function(objData){
        const data = objData.data;
        const specs = data.specs;
        let html="";
        specs.detail.forEach(e => {
            html+=`
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="" class="form-label fw-bold">${e.name}</label>
                    <p class="text-break" id="strDate">${e.value}</p>
                </div>
            </div>
            `
        });
        document.querySelector("#imgExampleView").setAttribute("src",data.img);
        document.querySelector("#strNameView").innerHTML = data.name;
        document.querySelector("#strDateView").innerHTML = data.date;
        document.querySelector("#strTypeView").innerHTML = specs.name;
        document.querySelector("#statusListView").innerHTML = data.status == 1 ? `<span class="badge me-1 bg-success">Activo</span>`: `<span class="badge me-1 bg-danger">Inactivo</span>` ;
        document.querySelector("#orderListView").innerHTML = data.order_view;
        document.querySelector("#isVisibleView").innerHTML = data.is_visible ? "Si" : "No";
        document.querySelector("#strReviewView").innerHTML = data.description;
        document.querySelector("#frameDescriptionView").innerHTML = html;
        document.querySelector("#modalTitleFrameView").innerHTML = "Datos de enmarcación";
        modalView.show();
    });
}
function editItem(id){
    let formData = new FormData();
    formData.append("id",id);
    request(base_url+"/MarqueteriaEjemplos/getExample",formData,"post").then(function(objData){
        const data = objData.data;
        const specs = data.specs;
        let html="";
        specs.detail.forEach(e => {
            html+=`
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="" class="form-label fw-bold">${e.name}</label>
                    <p class="text-break" id="strDate">${e.value}</p>
                </div>
            </div>
            `
        });
        objProduct.price_sell =data.total;
        objProduct.config = data.props;
        objProduct.data = specs;
        objProduct.id=data.frame;
        objProduct.height = data.height;
        objProduct.width = data.width;
        objProduct.margin = data.margin;
        objProduct.id_config = data.config;
        objProduct.orientation = data.orientation;
        objProduct.color_frame_id = data.color_frame;
        objProduct.color_margin_id = data.color_margin;
        objProduct.color_border_id = data.color_border;
        objProduct.type_frame = data.type_frame;
        document.querySelector("#idExample").value = data.id;
        document.querySelector(".uploadImg img").setAttribute("src",data.img);
        document.querySelector("#strName").value = data.name;
        let arrDate = new String(data.date).split("/");
        document.querySelector("#strDate").valueAsDate = new Date(arrDate[2]+"-"+arrDate[1]+"-"+arrDate[0]);
        document.querySelector("#strType").innerHTML = specs.name;
        document.querySelector("#statusList").value = data.status;
        document.querySelector("#orderList").value = data.order_view;
        document.querySelector("#isVisible").checked = data.is_visible;
        document.querySelector("#strReview").value = data.description;
        document.querySelector("#frameDescription").innerHTML = html;
        document.querySelector("#modalTitleFrame").innerHTML = "Actualizar ejemplo";
        modal.show();
    });
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
            let url = base_url+"/MarqueteriaEjemplos/delExample"
            let formData = new FormData();
            formData.append("id",id);
            request(url,formData,"post").then(function(objData){
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