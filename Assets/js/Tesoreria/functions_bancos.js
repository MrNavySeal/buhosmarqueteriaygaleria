import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppButtonInput from "../components/button_input.js"
import AppButtonSelect from "../components/button_select.js"
import {createCommon} from "../components/variables.js";
const app = {
    components:{
        "app-button":AppButton,
        "app-input":AppInput,
        "app-select":AppSelect,
        "app-pagination":AppPagination,
        "app-modal":AppModal,
        "app-button-input":AppButtonInput,
        "app-button-select":AppButtonSelect,
    },
    data(){
        return {
            common:createCommon(),
            terceros:createCommon(),
            cuentas:createCommon(),
            errores:[],
            intEstado:1,
            strTipo:"",
            strNombre:"",
            objCuenta:{id:"",code:"",name:"",nature:""},
            objTercero:{nombre:""},
            arrCuentas:[],
            strCuenta:"",
            currentController:null,
        }
    },
    mounted(){
        this.search();
    },
    methods:{
        openModal:function(){
            this.common.showModal = true;
            this.common.strName ="";
            this.common.intId =0;
            this.intEstado = 1;
            this.objCuenta = {}
            this.objTercero = {};
            this.strNombre = "";
            this.strTipo = "";
            this.strCuenta = "";
            this.common.title = "Nuevo banco";
        },

        save:async function(){
            const data = {
                "tipo":this.strTipo,
                "estado":this.intEstado,
                "cuenta_banco":this.strCuenta,
                "cuenta":this.objCuenta.id,
                "tercero":this.objTercero.id,
                "id":this.common.intId
            }
            
            this.common.processing =true;
            const response = await fetch(base_url+"/Tesoreria/Bancos/setDatos",{method:"POST",body:JSON.stringify(data)});
            const objData = await response.json();
            this.common.processing =false;
            if(objData.status){
                this.common.intId =0;
                this.common.showModal = false;
                this.objCuenta = {}
                this.objTercero = {};
                this.strTipo = "";
                this.strCuenta = "";
                this.intEstado = 1;
                this.search(this.common.intPage);
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
                this.errores = objData.errores ? objData.errores : [];
            }
        },

        search:async function(page=1,type=""){
            if (this.currentController) {
                this.currentController.abort();
            }

            this.currentController = new AbortController();
            const { signal } = this.currentController;

            const formData = new FormData();
            formData.append("type",type);
            if(type=="cuentas"){
                formData.append("type","cuentas");
                formData.append("search",this.cuentas.strSearch);
                const response = await fetch(base_url+"/Tesoreria/Bancos/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.arrCuentas = objData;
            }else if(type=="terceros"){
                this.terceros.intPage = page;
                formData.append("page",this.terceros.intPage);
                formData.append("per_page",this.terceros.intPerPage);
                formData.append("search",this.terceros.strSearch);
                const response = await fetch(base_url+"/Tesoreria/Bancos/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.terceros.arrData = objData.data;
                this.terceros.intStartPage  = objData.start_page;
                this.terceros.intTotalButtons = objData.limit_page;
                this.terceros.intTotalPages = objData.total_pages;
                this.terceros.intTotalResults = objData.total_records;
                this.terceros.arrButtons = objData.buttons;
            }else{
                this.common.intPage = page;
                formData.append("page",this.common.intPage);
                formData.append("per_page",this.common.intPerPage);
                formData.append("search",this.common.strSearch);
                const response = await fetch(base_url+"/Tesoreria/Bancos/getBuscar",{method:"POST",body:formData,signal});
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
            const response = await fetch(base_url+"/Tesoreria/Bancos/getDatos",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.common.intId = objData.data.id;
                this.intEstado = objData.data.status;
                this.strTipo = objData.data.type;
                this.strCuenta = objData.data.bank_account;
                this.objCuenta = {name:objData.data.code+" - "+objData.data.account,id:objData.data.account_id}
                this.objTercero = {nombre:objData.data.nombre,id:objData.data.person_id};
                this.common.title = "Editar banco";
                this.common.showModal = true;
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
                    const response = await fetch(base_url+"/Tesoreria/Bancos/delDatos",{method:"POST",body:formData});
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

        selectItem:function(data,type=""){
            if(type=="cuentas"){
                this.objCuenta = {id:data.id,code:data.code,name:data.code+"-"+data.name,nature:data.nature};
                this.cuentas.showModal = false;
            }else{
                this.objTercero = data;
                this.terceros.showModal = false;
            }
        },

    }
};

const App = Vue.createApp(app);
App.mount("#app");