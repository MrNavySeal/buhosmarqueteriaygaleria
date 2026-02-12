import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppButtonInput from "../components/button_input.js"
import AppButtonSelect from "../components/button_select.js"
import {formatNumber, cleanFormatNumber} from "../utils/general.js";
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
            retenciones:createCommon(),
            cuentas:createCommon(),
            errores:[],
            intEstado:1,
            intPorcentaje:0,
            intFiltroTipo:"",
            intTipoRetencion:"",
            strTipo:"",
            strNombre:"",
            objRetencion:{name:""},
            objIngreso:{name:""},
            objCuenta:{id:"",code:"",name:"",nature:""},
            arrDetalle:[],
            arrTipos:[],
            arrRelaciones:[],
            arrCuentas:[],
            strRelacion:"",
            currentController:null
        }
    },
    mounted(){
        this.search();
    },
    methods:{
        getData:async function(){
            const response = await fetch(base_url+"/Tesoreria/FormaPago/getDatosIniciales");
            const objData = await response.json();
            this.arrTipos = objData.tipo_pago;
            this.arrRelaciones = objData.relacion_pago;
        },

        openModal:function(){
            this.getData();
            this.common.showModal = true;
            this.common.strName ="";
            this.common.intId =0;
            this.intEstado = 1;
            this.objCuenta = {}
            this.arrDetalle = [];
            this.strNombre = "";
            this.strTipo = "";
            this.strRelacion = "";
            this.common.title = "Nueva forma de pago";
        },

        save:async function(){
            const data = {
                "tipo":this.strTipo,
                "relacion":this.strRelacion,
                "nombre":this.strNombre,
                "estado":this.intEstado,
                "detalle":this.arrDetalle,
                "cuentas":this.objCuenta.id,
                "id":this.common.intId
            }
            
            this.common.processing =true;
            const response = await fetch(base_url+"/Tesoreria/FormaPago/setDatos",{method:"POST",body:JSON.stringify(data)});
            const objData = await response.json();
            this.common.processing =false;
            if(objData.status){
                this.common.intId =0;
                this.common.showModal = false;
                this.objCuenta = {}
                this.arrDetalle = [];
                this.strNombre = "";
                this.strTipo = "";
                this.strRelacion = "";
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
            if(type=="retenciones"){
                this.retenciones.intPage = page;
                formData.append("filter_type",this.intFiltroTipo);
                formData.append("page",this.retenciones.intPage);
                formData.append("per_page",this.retenciones.intPerPage);
                formData.append("search",this.retenciones.strSearch);
                const response = await fetch(base_url+"/Tesoreria/FormaPago/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.retenciones.arrData = objData.data;
                this.retenciones.intStartPage  = objData.start_page;
                this.retenciones.intTotalButtons = objData.limit_page;
                this.retenciones.intTotalPages = objData.total_pages;
                this.retenciones.intTotalResults = objData.total_records;
                this.retenciones.arrButtons = objData.buttons;
            }else if(type=="cuentas"){
                formData.append("type","cuentas");
                formData.append("search",this.cuentas.strSearch);
                const response = await fetch(base_url+"/Tesoreria/FormaPago/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.arrCuentas = objData;
            }else{
                this.common.intPage = page;
                formData.append("page",this.common.intPage);
                formData.append("per_page",this.common.intPerPage);
                formData.append("search",this.common.strSearch);
                const response = await fetch(base_url+"/Tesoreria/FormaPago/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.common.arrData = objData.data;
                this.common.intStartPage  = objData.start_page;
                this.common.intTotalButtons = objData.limit_page;
                this.common.intTotalPages = objData.total_pages;
                this.common.intTotalResults = objData.total_records;
                this.common.arrButtons = objData.buttons;
            }
        },

        addItem:function(){
            if(this.objRetencion.name == ""){
                Swal.fire("Atención!","Debe seleccionar un descuento","warning");
                return false;
            }
            const data = this.objRetencion;
            const valid = this.arrDetalle.filter(function(e){return data.id == e.id});
            if(valid.length > 0){
                Swal.fire("Atención!","Este descuento ya fue agregado","warning");
                return false;
            }
            this.arrDetalle.push(JSON.parse(JSON.stringify(data)));
        },

        delItem:function(data){
            const index = this.arrDetalle.findIndex(function(e){return e.id == data.id});
            this.arrDetalle.splice(index,1);
        },

        edit:async function(data){
            const formData = new FormData();
            formData.append("id",data.id);
            const response = await fetch(base_url+"/Tesoreria/FormaPago/getDatos",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.strNombre =objData.data.name;
                this.common.intId = objData.data.id;
                this.intEstado = objData.data.status;
                this.strTipo = objData.data.type;
                this.strRelacion = objData.data.relation;
                this.objCuenta = {name:objData.data.code+" - "+objData.data.withholding,id:objData.data.withholding_id}
                this.arrDetalle = objData.data.detalle;
                this.arrTipos = objData.tipo_pago;
                this.arrRelaciones = objData.relacion_pago;
                this.common.title = "Editar forma de pago";
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
                    const response = await fetch(base_url+"/Tesoreria/FormaPago/delDatos",{method:"POST",body:formData});
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
                if(this.intTipoRetencion == "retencion"){
                    this.objRetencion = data;
                }else{
                    this.objIngreso = data;
                }
                this.retenciones.showModal = false;
            }
        },

    }
};

const App = Vue.createApp(app);
App.mount("#app");