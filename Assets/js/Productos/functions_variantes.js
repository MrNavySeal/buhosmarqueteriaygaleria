'use strict';
/* import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppTextArea from "../components/textarea.js"
import {common} from "../components/variables.js";
const App = {
    components:{
        "app-button":AppButton,
        "app-input":AppInput,
        "app-textarea":AppTextArea,
        "app-select":AppSelect,
        "app-pagination":AppPagination,
        "app-modal":AppModal
    },
    data(){
        return {
            common:common,
            intStatus:1,
            intVisible:false,
            strDescription:"",
            strImgUrl:base_url+'/Assets/images/uploads/category.jpg',
            strImage:"",
        }
    },
    mounted(){
        this.search();
    },
    methods:{
        openModal:function(){
            this.common.showModalModule = true;
            this.common.strName ="";
            this.common.intId =0;
            this.intStatus = 1;
            this.intVisible = false;
            this.strDescription ="";
            this.strImage ="";
            this.strImgUrl = base_url+'/Assets/images/uploads/category.jpg';
            this.common.modulesTitle = "Nueva categoría";
        },
        save:async function(){
            const formData = new FormData();
            formData.append("name",this.common.strName);
            formData.append("id",this.common.intId);
            formData.append("status",this.intStatus);
            formData.append("visible",this.intVisible? 1 : 0);
            formData.append("description",this.strDescription);
            formData.append("image",this.strImage);
            this.common.processing =true;
            const response = await fetch(base_url+"/Productos/ProductosCategorias/setCategoria",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing =false;
            if(objData.status){
                this.common.strName ="";
                this.common.intId =0;
                this.common.showModalModule = false;
                this.search(this.common.intPage);
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        },
        search:async function(page=1){
            const formData = new FormData();
            this.common.intPage = page;
            formData.append("page",this.common.intPage);
            formData.append("per_page",this.common.intPerPage);
            formData.append("search",this.common.strSearch);
            const response = await fetch(base_url+"/Productos/ProductosCategorias/getCategorias",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.arrData = objData.data;
            this.common.intStartPage  = objData.start_page;
            this.common.intTotalButtons = objData.limit_page;
            this.common.intTotalPages = objData.total_pages;
            this.common.intTotalResults = objData.total_records;
            this.common.arrButtons = objData.buttons;
        },
        edit:async function(data){
            const formData = new FormData();
            formData.append("id",data.id);
            const response = await fetch(base_url+"/Productos/ProductosCategorias/getCategoria",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.common.strName =objData.data.name;
                this.common.intId = objData.data.idcategory;
                this.intStatus = objData.data.status;
                this.intVisible = objData.data.is_visible;
                this.strDescription =objData.data.description;
                this.strImgUrl = objData.data.url;
                this.common.modulesTitle = "Editar categoría";
                this.common.showModalModule = true;
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        },
        del:async function(data){
            const objVue = this;
            Swal.fire({
                title:"¿Esta seguro de eliminar?",
                text:"Se eliminará para siempre...",
                icon: 'warning',
                showCancelButton:true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText:"Sí, eliminar",
                cancelButtonText:"No, cancelar"
            }).then(async function(result){
                if(result.isConfirmed){
                    const formData = new FormData();
                    formData.append("id",data.id);
                    const response = await fetch(base_url+"/Productos/ProductosCategorias/delCategoria",{method:"POST",body:formData});
                    const objData = await response.json();
                    if(objData.status){
                        Swal.fire("Eliminado!",objData.msg,"success");
                        objVue.search(objVue.common.intPage);
                    }else{
                        Swal.fire("Error",objData.msg,"error");
                    }
                }else{
                    objVue.search(objVue.common.intPage);
                }
            });
            
        },
        uploadImagen:function(e){
            this.strImage = e.target.files[0];
            let type = this.strImage.type;
            if(type != "image/png" && type != "image/jpg" && type != "image/jpeg" && type != "image/gif"){
                Swal.fire("Error","Solo se permite imágenes.","error");
            }else{
                let objectUrl = window.URL || window.webkitURL;
                let route = objectUrl.createObjectURL(this.strImage);
                this.strImgUrl = route;
            }
        },
    }
};
const app = Vue.createApp(App);
app.mount("#app"); */

let arrOptions = [];
const tableData = document.querySelector("#tableVariants");
const modal = document.querySelector("#modalElement") ? new bootstrap.Modal(document.querySelector("#modalElement")) :"";
const table = new DataTable("#tableData",{
    "dom": 'lfBrtip',
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"/Productos/ProductosOpciones/getVariants",
        "dataSrc":""
    },
    columns: [
        { data: 'id_variation'},
        { data: 'name' },
        { data: 'qty' },
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
function openModal(){
    document.querySelector(".modal-title").innerHTML = "Nueva variante";
    document.querySelector("#txtName").value = "";
    document.querySelector("#statusList").value = 1;
    document.querySelector("#id").value ="";
    modal.show();
    arrOptions = [];
    tableData.innerHTML ="";
}
if(document.querySelector("#formItem")){
    let form = document.querySelector("#formItem");
    form.addEventListener("submit",function(e){
        e.preventDefault();
        let strName = document.querySelector("#txtName").value;
        let arrOptions = getVariants();
        if(strName == ""){
            Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
            return false;
        }
        if(arrOptions.length == 0){
            Swal.fire("Error","Debe crear al menos una opción","error");
            return false;
        }
        let formData = new FormData(form);
        formData.append("options",JSON.stringify(arrOptions));
        let btnAdd = document.querySelector("#btnAdd");
        btnAdd.innerHTML=`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btnAdd.setAttribute("disabled","");
        request(base_url+"/Productos/ProductosOpciones/setVariant",formData,"post").then(function(objData){
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
function addVariant(){
    const name = document.querySelector("#txtNameVariant");
    if(name.value==""){
        Swal.fire("Error","Para agregar una opción, debe escribir el nombre.","error");
        return false;
    }
    const html = `
    <div class="d-flex align-items-center">
        <input type="text" class="form-control" value="${name.value}">
        <button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteVariant(this)"><i class="fas fa-trash-alt"></i></button>
    </div>
    `;
    let el = document.createElement("div");
    el.classList.add("data-item","w-100");
    el.innerHTML = html;
    tableData.appendChild(el);
    name.value ="";
}
function getVariants(){
    let variants = document.querySelectorAll(".data-item");
    arrOptions = [];
    variants.forEach(el=>{
        let val = el.children[0].children[0].value;
        if(val !=""){
            arrOptions.push(val);
        }
    });
    arrOptions = [... new Set(arrOptions)];
    console.log(arrOptions);
    return arrOptions;
}
function deleteVariant(item){
    item.parentElement.parentElement.remove();
}
function editItem(id){
    let url = base_url+"/Productos/ProductosOpciones/getVariant";
    let formData = new FormData();
    formData.append("id",id);
    request(url,formData,"post").then(function(objData){
        if(objData.status){
            const options = objData.data.options;
            let html="";
            document.querySelector("#txtName").value = objData.data.name;
            document.querySelector("#statusList").value = objData.data.status;
            document.querySelector("#id").value = objData.data.id_variation;
            document.querySelector(".modal-title").innerHTML = "Actualizar variante";
            options.forEach(el=>{
                arrOptions.push(el.name);
                html+=`
                <div class="data-item w-100">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control" value="${el.name}">
                        <button class="btn btn-danger m-1" type="button" title="Eliminar" onclick="deleteVariant(this)"><i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                    </div>
                </div>
                `;
            });
            tableData.innerHTML = html;
            modal.show();
        }else{
            Swal.fire("Error",objData.msg,"error");
        }
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
            let url = base_url+"/Productos/ProductosOpciones/delVariant"
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
