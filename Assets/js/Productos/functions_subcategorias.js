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
        "app-button-input":AppButtonInput,
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
            pagination:createCommon(),
            intStatus:1,
            strDescription:"",
            objCategory:{name:"",id:""}

        }
    },
    mounted(){
        this.search();
    },
    methods:{
        openModal:function(){
            this.common.showModalSubcategory = true;
            this.common.strName ="";
            this.common.intId =0;
            this.intStatus = 1;
            this.objCategory={name:"",id:""};
            this.common.subcategoryTitle = "Nueva subcategoría";
            this.category.modalType='';
        },
        save:async function(){
            this.category.modalType='';
            const formData = new FormData();
            formData.append("name",this.common.strName);
            formData.append("id",this.common.intId);
            formData.append("status",this.intStatus);
            formData.append("category",this.objCategory.id);
            this.common.processing =true;
            const response = await fetch(base_url+"/Productos/ProductosCategorias/setSubcategoria",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing =false;
            
            if(objData.status){
                this.common.strName ="";
                this.common.intId =0;
                this.common.showModalSubcategory = false;
                this.search(this.common.intPage);
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
                this.common.errors = objData.errors;
            }
        },
        selectItem:function(data){
            this.objCategory=data;
            this.category.showModalPaginationCategory=false
        },
        search:async function(page=1){
            const formData = new FormData();
            if(this.category.modalType == "category"){
                this.category.intPage = page;
                formData.append("page",this.category.intPage);
                formData.append("per_page",this.category.intPerPage);
                formData.append("search",this.category.strSearch);
                const response = await fetch(base_url+"/Productos/ProductosCategorias/getSelectCategorias",{method:"POST",body:formData});
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
                const response = await fetch(base_url+"/Productos/ProductosCategorias/getSubcategorias",{method:"POST",body:formData});
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
            this.common.errors = [];
            const formData = new FormData();
            formData.append("id",data.id);
            const response = await fetch(base_url+"/Productos/ProductosCategorias/getSubcategoria",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.common.strName =objData.data.name;
                this.common.intId = objData.data.id;
                this.intStatus = objData.data.status;
                this.objCategory = {
                    id:objData.data.categoryid,
                    name:objData.data.category
                };
                this.common.subcategoryTitle = "Editar subcategoría";
                this.common.showModalSubcategory = true;
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
                    const response = await fetch(base_url+"/Productos/ProductosCategorias/delSubcategoria",{method:"POST",body:formData});
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
    }
};
const app = Vue.createApp(App);
app.mount("#app");