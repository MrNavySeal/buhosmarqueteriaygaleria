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
            concepto:createCommon(),
            errores:[],
            intEstado:1,
            intPorcentaje:0,
            intTotal:0,
            intFiltroTipo:"",
            strTipo:"valor",
            strNombre:"",
            objValor:{valor:0,valor_formato:formatNumber(0)},
            objConcepto:{name:""},
            arrDetalle:[],
            arrTipos:[],
            currentController:null,
            strClase:"retencion",
            strFiltroClase:""
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
            this.strNombre ="";
            this.arrDetalle = [];
            this.common.title = "Nueva retención";
        },

        save:async function(){
            const data = {
                "tipo":this.strTipo,
                "clase":this.strClase,
                "nombre":this.strNombre,
                "estado":this.intEstado,
                "detalle":this.arrDetalle,
                "total":this.intTotal,
                "id":this.common.intId
            }
            
            this.common.processing =true;
            const response = await fetch(base_url+"/Tesoreria/Retenciones/setDatos",{method:"POST",body:JSON.stringify(data)});
            const objData = await response.json();
            this.common.processing =false;
            if(objData.status){
                this.common.intId =0;
                this.common.showModal = false;
                this.objConcepto = {}
                this.arrDetalle = [];
                this.strNombre = "";
                this.strTipo = "valor";
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
            if(type=="concepto"){
                this.concepto.intPage = page;
                formData.append("type",type);
                formData.append("filter_type",this.intFiltroTipo);
                formData.append("page",this.concepto.intPage);
                formData.append("per_page",this.concepto.intPerPage);
                formData.append("search",this.concepto.strSearch);
                const response = await fetch(base_url+"/Tesoreria/Retenciones/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.concepto.arrData = objData.data;
                this.concepto.intStartPage  = objData.start_page;
                this.concepto.intTotalButtons = objData.limit_page;
                this.concepto.intTotalPages = objData.total_pages;
                this.concepto.intTotalResults = objData.total_records;
                this.concepto.arrButtons = objData.buttons;
                this.arrTipos = objData.tipos;
            }else{
                this.common.intPage = page;
                formData.append("filter_class",this.strFiltroClase);
                formData.append("page",this.common.intPage);
                formData.append("per_page",this.common.intPerPage);
                formData.append("search",this.common.strSearch);
                const response = await fetch(base_url+"/Tesoreria/Retenciones/getBuscar",{method:"POST",body:formData,signal});
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
            if(this.objConcepto.name == ""){
                Swal.fire("Atención!","Debe seleccionar un concepto","warning");
                return false;
            }
            const data = this.objConcepto;
            const valid = this.arrDetalle.filter(function(e){return data.id == e.id});
            if(valid.length > 0){
                Swal.fire("Atención!","Este concepto ya fue agregado","warning");
                return false;
            }
            data['valor'] = this.objValor;
            data['porcentaje'] = this.intPorcentaje;
            this.arrDetalle.push(JSON.parse(JSON.stringify(data)));
        },

        delItem:function(data){
            const index = this.arrDetalle.findIndex(function(e){return e.id == data.id});
            this.arrDetalle.splice(index,1);
        },

        edit:async function(data){
            const formData = new FormData();
            formData.append("id",data.id);
            const response = await fetch(base_url+"/Tesoreria/Retenciones/getDatos",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.strNombre =objData.data.name;
                this.common.intId = objData.data.id;
                this.intEstado = objData.data.status;
                this.strTipo = objData.data.type;
                this.strClase = objData.data.kind;
                this.arrDetalle = objData.data.detalle;
                this.common.title = "Editar retención";
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
                    const response = await fetch(base_url+"/Tesoreria/Retenciones/delDatos",{method:"POST",body:formData});
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
            this.objConcepto = data;
            this.concepto.showModal = false;
        },

        formatInputNumber(data,conFormato, sinFormato) {
            data[conFormato] = formatNumber(cleanFormatNumber(data[conFormato]));
            data[sinFormato] = cleanFormatNumber(data[conFormato]);
        },
    },
    computed:{
        totalValor:function(){
            let total = 0;
            let tipo = this.strTipo;
            this.arrDetalle.forEach(e => {
                if(tipo=="valor"){
                    total+=parseFloat(e.valor.valor);
                }else{
                    total+=parseFloat(e.porcentaje);
                }
            });
            this.intTotal = total;
            if(tipo == "porcentaje"){
                if(total > 100 || total < 0){
                    Swal.fire("Atención!","El porcentaje total no debe ser mayor a 100 o menor a 0","warning");
                    return 0;
                }
                total = total+"%";
            }else{
                total = formatNumber(total);
            }
            return total;
        }
    }
};

const App = Vue.createApp(app);
App.config.globalProperties.$formatNumber = formatNumber;
App.config.globalProperties.$cleanFormatNumber = cleanFormatNumber;
App.mount("#app");