import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppTextArea from "../components/textarea.js"
import AppButtonInput from "../components/button_input.js"
import AppButtonSelect from "../components/button_select.js";
import {createCommon} from "../components/variables.js";
const App = {
    components:{
        "app-button":AppButton,
        "app-input":AppInput,
        "app-button-input":AppButtonInput,
        "app-button-select":AppButtonSelect,
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
            objCategory:{name:"",id:""},
            objSubcategory:{name:"",id:"",categoryid:""},
            arrMeasures:[],
            arrSpecs:[],
            arrVariants:[],
            arrImages:[],
            arrSpecsAdded:[],
            intStatus:1,
            strName:"",
            strReference:"",
            strShortDescription:"",
            intCheckProduct:false,
            intCheckIngredient:false,
            intCheckRecipe:false,
            intCheckStock:false,
            intCheckVariant:false,
            intStock:0,
            intMinStock:0,
            intTax:0,
            intPurchasePrice:0,
            intSellPrice:0,
            intOfferPrice:0,
            intFraming:2,
            intVisible:true,
            intMeasure:"",
            intSpec:"",
            strImgUrl:base_url+'/Assets/images/uploads/category.jpg',
            strImage:"",
        }
    },
    mounted(){
        this.getData();
        this.search();
    },
    methods:{
        getData:async function(){
            const response = await fetch(base_url+"/Productos/Productos/getData");
            const objData = await response.json();
            this.arrSpecs = objData.specs;
            this.arrMeasures = objData.measures;
            this.arrVariants = objData.variants;
            this.intMeasure = objData.measures[0].id;
        },
        openModal:function(){
            document.querySelector("#txtDescription").value ="";
            setTinymce("#txtDescription",400);
            this.common.showModalProduct = true;
            this.common.strName ="";
            this.common.intId =0;
            this.objCategory={name:"",id:""},
            this.objSubcategory={name:"",id:"",categoryid:""},
            this.intStatus=1,
            this.strName="",
            this.strReference="",
            this.strShortDescription="",
            this.intCheckProduct=false,
            this.intCheckIngredient=false,
            this.intCheckRecipe=false,
            this.intUnit=0,
            this.intCheckStock=false,
            this.intStock=0,
            this.intMinStock=0,
            this.intTax=0,
            this.intPurchasePrice=0,
            this.intSellPrice=0,
            this.intOfferPrice=0,
            this.intFraming=2,
            this.intVisible=true,
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
            if(this.subcategory.modalType=='subcategory'){
                this.subcategory.intPage = page;
                formData.append("id",this.objCategory.id);
                formData.append("page",this.subcategory.intPage);
                formData.append("per_page",this.subcategory.intPerPage);
                formData.append("search",this.subcategory.strSearch);
                const response = await fetch(base_url+"/Productos/ProductosMasivos/getSelectSubcategorias",{method:"POST",body:formData});
                const objData = await response.json();
                this.subcategory.arrData = objData.data;
                this.subcategory.intStartPage  = objData.start_page;
                this.subcategory.intTotalButtons = objData.limit_page;
                this.subcategory.intTotalPages = objData.total_pages;
                this.subcategory.intTotalResults = objData.total_records;
                this.subcategory.arrButtons = objData.buttons;
            }else if(this.subcategory.modalType == "category"){
                this.category.intPage = page;
                formData.append("page",this.category.intPage);
                formData.append("per_page",this.category.intPerPage);
                formData.append("search",this.category.strSearch);
                const response = await fetch(base_url+"/Productos/ProductosMasivos/getSelectCategorias",{method:"POST",body:formData});
                const objData = await response.json();
                this.category.arrData = objData.data;
                this.category.intStartPage  = objData.start_page;
                this.category.intTotalButtons = objData.limit_page;
                this.category.intTotalPages = objData.total_pages;
                this.category.intTotalResults = objData.total_records;
                this.category.arrButtons = objData.buttons;
            }else{
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
            }
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
        delItem:function(type="",data=""){
            if(type=="subcategory"){
                this.objSubcategory = {name:"",id:"",categoryid:""};
            }else if(type=="category"){
                this.objCategory = {name:"",id:"",};
                this.objSubcategory = {name:"",id:"",categoryid:""};
            }else if(type=="image"){
                const index =this.arrImages.findIndex(function(e){return e.name==data});
                this.arrImages.splice(index,1);
            }else if(type=="spec"){
                const index =this.arrSpecsAdded.findIndex(function(e){return e.id==data.id});
                this.arrSpecsAdded.splice(index,1);
            }
        },
        addItem:function(type="",data=""){
            if(type=="spec"){
                let idSpec = this.intSpec;
                const arr = this.arrSpecsAdded.filter(function(e){return e.id == idSpec});
                if(this.intSpec == ""){
                    Swal.fire("Atención!","Seleccione una caracterítica.","warning");
                    return false;
                }
                if(arr.length > 0){
                    Swal.fire("Atención!","La característica ya ha sido agregada, seleccione otra.","warning");
                    return false;
                }
                const arrSpecs = [...this.arrSpecs];
                data = arrSpecs.filter(function(e){return e.id == idSpec})[0];
                data.value ="";
                this.arrSpecsAdded.push(data);
                this.intSpec="";
            }
        },
        selectItem:function(data,type=""){
            if(type=="subcategory"){
                this.objSubcategory=data;
                this.subcategory.showModalPaginationSubcategory=false
            }else if(type=="category"){
                this.objCategory=data; 
                if(this.objSubcategory.categoryid != this.objCategory.id){
                    this.objSubcategory = {name:"",id:"",categoryid:""};
                }
                this.category.showModalPaginationCategory=false
            }
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
                this.subcategory.modalType='category';
                this.category.modalType=type;
                this.category.showModalPaginationCategory=true;
            }
            this.search();
        },
        changeArticleType:function(){
            if(this.intCheckRecipe){
                this.intCheckProduct = false;
                this.intCheckIngredient = false;
            }else if(this.intCheckProduct || this.intCheckIngredient){
                this.intCheckRecipe = false;
            }
        },
        uploadMultipleImage:function(e){
            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                const f = files[i];
                if(f.type != "image/png" && f.type != "image/jpg" && f.type != "image/jpeg" && f.type != "image/gif"){
                    Swal.fire("Error","Solo se permite imágenes","error");
                    return false;
                }else{
                    let objectUrl = window.URL || window.webkitURL;
                    let route = objectUrl.createObjectURL(f);
                    f.route =route;
                    this.arrImages.push(f);
                }   
            }
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