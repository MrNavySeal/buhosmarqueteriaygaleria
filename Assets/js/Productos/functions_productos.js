import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppTextArea from "../components/textarea.js"
import {createCommon} from "../components/variables.js";
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
            common:createCommon(),
            category:createCommon(),
            subcategory:createCommon(),
            objCategory:{name:"Todo",id:""},
            objSubcategory:{name:"Todo",id:"",categoryid:""},
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
            this.common.showModalProduct = true;
            this.common.strName ="";
            this.common.intId =0;
            this.common.productTitle = "Nuevo producto";
        },
        save:async function(){
            const formData = new FormData();
            formData.append("name",this.common.strName);
            formData.append("id",this.common.intId);
            formData.append("status",this.intStatus);
            formData.append("visible",this.intVisible);
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
            const response = await fetch(base_url+"/Productos/Productos/getProductos",{method:"POST",body:formData});
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
                this.common.modulesTitle = "Nueva categoría";
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
        view:async function(data,type=""){
            if(type=="shop"){
                window.open(base_url+"/tienda/producto/"+data.route,"_blank");
            }
        },
        changeCategory:function (type){
            if(type == "subcategory"){
                this.subcategory.modalType=type;
                this.subcategory.showModalPaginationSubcategory=true;
            }else{
                this.subcategory.modalType='';
                this.category.modalType=type;
                this.category.showModalPaginationCategory=true;
            }
            this.search();
        },
    }
};
const app = Vue.createApp(App);
app.mount("#app");
/* 'use strict';

if(document.querySelector("#btnNew")){
    document.querySelector("#btnNew").classList.remove("d-none");
}
let arrData = [];
const searchHtml = document.querySelector("#txtSearch");
const perPage = document.querySelector("#perPage");
const initialDateHtml = document.querySelector("#txtInitialDate");
const finallDateHtml = document.querySelector("#txtFinalDate");

window.addEventListener("load",function(){
    getData();
});
searchHtml.addEventListener("input",function(){getData();});
perPage.addEventListener("change",function(){getData();});

async function getData(page = 1){
    const formData = new FormData();
    formData.append("page",page);
    formData.append("perpage",perPage.value);
    formData.append("search",searchHtml.value);
    const response = await fetch(base_url+"/Productos/Productos/getProducts",{method:"POST",body:formData});
    const objData = await response.json();
    arrData = objData.data;
    tableData.innerHTML =objData.html;
    document.querySelector("#pagination").innerHTML = objData.html_pages;
    document.querySelector("#totalRecords").innerHTML = `<strong>Total de registros: </strong> ${objData.total_records}`;
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
            request(base_url+"/Productos/Productos/delProduct",formData,"post").then(function(objData){
                if(objData.status){
                    Swal.fire("Eliminado",objData.msg,"success");
                    getData();
                }else{
                    Swal.fire("Error",objData.msg,"error");
                }
            });
        }
    });
} */