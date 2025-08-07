
import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppTextArea from "../components/textarea.js"
import AppButtonInput from "../components/button_input.js"
import {createCommon} from "../components/variables.js";
const App = {
    components:{
        "app-button":AppButton,
        "app-input":AppInput,
        "app-button-input":AppButtonInput,
        "app-textarea":AppTextArea,
        "app-select":AppSelect,
        "app-pagination":AppPagination,
        "app-modal":AppModal
    },
    data(){
        return {
            category:createCommon(),
            subcategory:createCommon(),
            objCategory:{name:"Todo",id:""},
            objSubcategory:{name:"Todo",id:"",categoryid:""},
            strFile:"",
        }
    },
    methods:{
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
            }else{
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
        selectItem:function(data,type=""){
            if(type=="subcategory"){
                this.objSubcategory=data;
                this.subcategory.showModalPaginationSubcategory=false
            }else if(type=="category"){
                this.objCategory=data; 
                if(this.objSubcategory.categoryid != this.objCategory.id){
                    this.objSubcategory = {name:"Todo",id:"",categoryid:""};
                }
                this.category.showModalPaginationCategory=false
            }
        },
        setFile:function(event){
            this.strFile = event.target.files;
        },
        uploadFile:async function(type){
            const strFile = this.strFile;
            if(strFile.length == 0){
                Swal.fire("Error","Debe subir la plantilla.","error");
                return false;
            }
            let file = strFile[0];
            let extension = file.name.split(".")[1];
            if(extension != "xlsx"){
                Swal.fire("Error","El archivo es incorrecto; por favor, utiliza nuestra plantilla.","error");
                return false;
            }
            this.strFile = file;
            let formData = new FormData();
            formData.append("template",file);
            formData.append("type",type);
            formData.append("extension",extension);
            this.category.processing = true;
            const response = await fetch(base_url+"/Productos/ProductosMasivos/uploadProducts",{method:"POST",body:formData});
            const objData = await response.json();
            this.category.processing = false;
            if(objData.status){
                Swal.fire("",objData.msg,"success");
            }else{
                Swal.fire("",objData.msg,"error");
            }
        },
        del:function(type=""){
            if(type!=""){
                this.objSubcategory = {name:"Todo",id:"",categoryid:""};
            }else{
                this.objCategory = {name:"Todo",id:"",categoryid:""};
                this.objSubcategory = {name:"Todo",id:"",categoryid:""};
            }
        },
        download:function(type=""){
            if(type!=""){
                let data = "action=editar&category="+this.objCategory.id+"&subcategory="+this.objSubcategory.id;
                window.open(base_url+"/Productos/ProductosMasivos/plantilla?"+data,"_blank");
            }else{
                window.open(base_url+"/Productos/ProductosMasivos/plantilla","_blank");
            }
        }
    }
};
const app = Vue.createApp(App);
app.mount("#app");