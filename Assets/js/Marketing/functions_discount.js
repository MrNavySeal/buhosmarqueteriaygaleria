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
            currentController:null,
            common:createCommon(),
            category:createCommon(),
            subcategory:createCommon(),
            objCategory:{name:"",id:""},
            objSubcategory:{name:"",id:"",categoryid:""},
            errors:[],
            arrWholesalePrices:[],
            intWholeSalePercent:"",
            intWholeSaleMaxQty:"",
            intWholeSaleMinQty:"",
            intStatus:1,
            intType:1,
            intDiscount:"",
            intLimit:0,
            strInitialDate:new Date().toISOString().split("T")[0],
            strFinalDate:new Date().toISOString().split("T")[0],
        }
    },
    mounted(){
        this.search();
    },
    methods:{
        openModal:function(){
            this.common.showModalModule = true;
            this.common.intId =0;
            this.objCategory={name:"Todo",id:""};
            this.objSubcategory={name:"Todo",id:"",categoryid:""};
            this.intStatus=1,
            this.arrWholesalePrices = [];
            this.intWholeSaleMinQty="";
            this.intWholeSaleMaxQty="";
            this.intWholeSalePercent ="";
            this.intDiscount ="";
            this.errors = [];
            this.intType = 1;
            this.intLimit = 0;
            this.strInitialDate = new Date().toISOString().split("T")[0];
            this.strFinalDate = new Date().toISOString().split("T")[0];
            this.common.modulesTitle = "Nuevo descuento";

        },

        save:async function(){
            const formData = new FormData();
            const arrData = {
                "status":this.intStatus,
                "id":this.common.intId,
                "wholesale_discount":this.arrWholesalePrices,
                "from_date":this.strInitialDate,
                "to_date":this.strFinalDate,
                "time_limit":this.intLimit,
                "category":this.objCategory.id,
                "subcategory":this.objSubcategory.id,
                "discount":this.intDiscount,
                "type":this.intType,
            }
            formData.append("data",JSON.stringify(arrData));
            this.common.processing =true;
            const response = await fetch(base_url+"/Marketing/Descuentos/setDatos",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing =false;
            if(objData.status){
                this.common.strName ="";
                this.common.intId =0;
                this.common.showModalModule = false;
                this.subcategory.modalType='';
                this.category.modalType='';
                this.common.modalType='products';
                this.search(this.common.intPage);
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                this.errors = objData.errors;
                Swal.fire("Error",objData.msg,"error");
            }
        },

        search:async function(page=1){

            if (this.currentController) {
                this.currentController.abort();
            }

            this.currentController = new AbortController();
            const { signal } = this.currentController;

            const formData = new FormData();
            if(this.subcategory.modalType=='subcategory'){
                this.subcategory.intPage = page;
                formData.append("id",this.objCategory.id);
                formData.append("page",this.subcategory.intPage);
                formData.append("per_page",this.subcategory.intPerPage);
                formData.append("search",this.subcategory.strSearch);
                const response = await fetch(base_url+"/Marketing/Descuentos/getSelectSubcategorias",{method:"POST",body:formData, signal});
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
                const response = await fetch(base_url+"/Marketing/Descuentos/getSelectCategorias",{method:"POST",body:formData, signal});
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
                formData.append("type","products");

                const response = await fetch(base_url+"/Marketing/Descuentos/getDescuentos",{method:"POST",body:formData,signal},);
                const objData = await response.json();

                this.common.arrData = objData.data;
                this.common.intStartPage  = objData.start_page;
                this.common.intTotalButtons = objData.limit_page;
                this.common.intTotalPages = objData.total_pages;
                this.common.intTotalResults = objData.total_records;
                this.common.arrButtons = objData.buttons;
            }
        },

        edit:async function(id){
            const formData = new FormData();
            formData.append("id",id);
            const response = await fetch(base_url+"/Marketing/Descuentos/getDescuento",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                const data = objData.data;
                this.common.intId =data.id_discount;
                this.objCategory={name:data.categoryid != 0 ? data.category : "Todo",id:data.categoryid};
                this.objSubcategory={name:data.subcategoryid != 0 ? data.subcategory : "Todo" ,id:data.subcategoryid,categoryid:data.categoryid};
                this.intStatus=data.status;
                this.arrWholesalePrices = data.wholesale;
                this.strInitialDate = data.from_date,
                this.strFinalDate = data.to_date;
                this.intDiscount = data.discount;
                this.intType = data.type;
                this.intLimit = data.time_limit;

                if(this.arrWholesalePrices.length > 0){
                    this.intWholeSaleMinQty = parseFloat(this.arrWholesalePrices[this.arrWholesalePrices.length-1].max)+1;
                }

                this.common.modulesTitle = "Editar descuento";
                this.common.showModalModule = true;
                this.errors = [];
            }else{
                Swal.fire("Error",objData.msg,"error");
            } 
        },

        del:async function(id){
            const objVue = this;
            this.subcategory.modalType='';
            this.category.modalType='';
            this.common.modalType='discounts';
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
                    formData.append("id",id);
                    const response = await fetch(base_url+"/Marketing/Descuentos/delDescuento",{method:"POST",body:formData});
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

        delItem:function(type="",data=""){
            if(type=="subcategory"){
                this.objSubcategory = {name:"",id:"",categoryid:""};
            }else if(type=="category"){
                this.objCategory = {name:"",id:"",};
                this.objSubcategory = {name:"",id:"",categoryid:""};
            }else if(type=="price"){
                this.arrWholesalePrices.splice(data,1);
            }
        },

        addItem:function(type="",data=""){
            if( this.intWholeSaleMinQty == "" || this.intWholeSaleMaxQty=="" || this.intWholeSalePercent==""){
                Swal.fire("Atención!","Los campos no pueden estar vacíos.","warning");
                return false;
            }

            this.intWholeSaleMinQty = parseFloat(this.intWholeSaleMinQty);
            this.intWholeSaleMaxQty = parseFloat(this.intWholeSaleMaxQty);
            this.intWholeSalePercent = parseFloat(this.intWholeSalePercent);

            if(this.intWholeSalePercent > 100 || this.intWholeSalePercent < 0){
                Swal.fire("Atención!","El porcentaje no puede ser menor a 0 ni mayor a 100.","warning");
                return false;
            }else if(this.intWholeSaleMinQty < 0){
                Swal.fire("Atención!","La cantidad mínima no puede ser menor a 0.","warning");
                return false;
            }else if(this.intWholeSaleMaxQty < 0){
                Swal.fire("Atención!","La cantidad máximna no puede ser menor a 0.","warning");
                return false;
            }

            let obj = {
                min:this.intWholeSaleMinQty,
                max:this.intWholeSaleMaxQty,
                percent:this.intWholeSalePercent
            }
            
            this.intWholeSaleMaxQty ="";
            this.intWholeSalePercent = "";
            this.arrWholesalePrices.push(obj);

            const total = this.arrWholesalePrices.length;
            this.intWholeSaleMinQty ="";
            if(total > 0){
                this.intWholeSaleMinQty = parseFloat(this.arrWholesalePrices[total-1].max) + 1;
            }
            this.changeWholeSaleMaxQty();
            
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

        changeWholeSaleMaxQty:function(){
            const root = this.arrWholesalePrices[0];
            const rootMin = parseFloat(root.min);
            const rootMax = parseFloat(root.max);
            root.max = rootMin >= rootMax ? rootMin+1 : rootMax;
            this.arrWholesalePrices[0] = root;

            for (let i = 1; i < this.arrWholesalePrices.length; i++) {
                const before = this.arrWholesalePrices[i-1];
                const current = this.arrWholesalePrices[i];
                const currentMax = parseFloat(current.max);
                const newMin =  parseFloat(before.max) + 1;
                current.min = newMin;
                current.max = newMin >= currentMax ? newMin+1 : currentMax;
                this.arrWholesalePrices[i] = current;
            }
            this.intWholeSaleMinQty = parseFloat(this.arrWholesalePrices[this.arrWholesalePrices.length-1].max) + 1;
        },

        changeWholeSalePercent:function(index){
            const root = this.arrWholesalePrices[index];
            const percent = parseFloat(root.percent);
            if(percent > 100){
                root.percent = 100;
            }else if(percent < 0){
                root.percent = 0;
            }
            this.arrWholesalePrices[index] = root;
        },
    },
};
const app = Vue.createApp(App);
app.mount("#app");