import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppButtonInput from "../components/button_input.js"
import AppButtonSelect from "../components/button_select.js"
import {createCommon} from "../components/variables.js";

const App = {
    components:{
        "app-button":AppButton,
        "app-input":AppInput,
        "app-button-input":AppButtonInput,
        "app-button-select":AppButtonSelect,
        "app-select":AppSelect,
        "app-pagination":AppPagination,
        "app-modal":AppModal
    },
    data() {
        return {
            //Modales
            common:createCommon(),
            cuentas:createCommon(),

            //Paginacion y filtros
            intPagina:1,
            intInicioPagina:1,
            intTotalPaginas:1,
            intTotalBotones:1,
            intPorPagina:25,
            intTotalResultados:0,
            arrData:[],
            strFecha:new Date().toISOString().split("T")[0], 
            arrBotones:[],
            strBuscar:"",
            intFiltroTipo:"",
            
            //Variables
            intEstado:1,
            strNombre:"",
            strNaturaleza:"debito",
            intTipo:"",
            objCuenta:{id:"",code:"",name:"",nature:""},
            arrTipos:[],
            arrDetalle:[],
            arrCuentas:[],
            errores:[],
            
        };
    },mounted(){
        this.search();
    },methods:{
        getDatosIniciales:async function(){
            const response = await fetch(base_url+"/Contabilidad/ConceptosContables/getDatosIniciales");
            const objData = await response.json();
            this.arrTipos = objData.tipos;
            this.arrCuentas = objData.cuentas;
        },

        openModal:async function(){
            await this.getDatosIniciales();
            this.common.showModal = true;
            this.common.modalType = 'conceptos';
            this.common.intId =0;
            this.common.title = "Nuevo concepto contable";
            this.strNombre ="";
            this.intTipo="";
        },

        save: async function(){
            const obj = {
                "id":this.common.intId,
                "nombre":this.strNombre,
                "tipo":this.intTipo,
                "estado":this.intEstado,
                "detalle":this.arrDetalle
            }

            const formData = new FormData();
            formData.append("data",JSON.stringify(obj));

            this.common.processing = true;
            const response = await fetch(base_url+"/Contabilidad/ConceptosContables/setDatos",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing = false;
            if(objData.status){
                Swal.fire("Guardado!",objData.msg,"success");
                if(this.common.intId == 0){
                    this.common.modalType = 'conceptos';
                    this.common.intId =0;
                    this.strNombre ="";
                    this.intTipo="";
                }
                this.common.showModal = false
                this.search();
            }else{
              Swal.fire("Error",objData.msg,"error");
              this.errores = objData.errores ? objData.errores : [];
            }
        },

        search:async function(page=1){
            if (this.currentController) {
                this.currentController.abort();
            }

            this.currentController = new AbortController();
            const { signal } = this.currentController;
            const formData = new FormData();
            if(this.cuentas.modalType == "cuentas"){
                formData.append("type","cuentas");
                formData.append("search",this.cuentas.strSearch);
                const response = await fetch(base_url+"/Contabilidad/ConceptosContables/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.arrCuentas = objData;
            }else{
                this.common.intPage = page;
                formData.append("type","buscar");
                formData.append("page",this.common.intPage);
                formData.append("per_page",this.common.intPerPage);
                formData.append("search",this.common.strSearch);
                formData.append("filter_type",this.intFiltroTipo);
                const response = await fetch(base_url+"/Contabilidad/ConceptosContables/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.common.arrData = objData.data;
                this.common.intStartPage  = objData.start_page;
                this.common.intTotalButtons = objData.limit_page;
                this.common.intTotalPages = objData.total_pages;
                this.common.intTotalResults = objData.total_records;
                this.common.arrButtons = objData.buttons;
                this.arrTipos = objData.tipos;
            }
            
        },

        selectItem:function(data,type=""){
            this.objCuenta = {id:data.id,code:data.code,name:data.name,nature:data.nature};
            this.cuentas.showModal = false;
        },

        addItem:function(){
            if(this.objCuenta.id == ""){
                Swal.fire("Atención!","Debe seleccionar una cuenta","warning");
                return false;
            }
            const cuenta = this.objCuenta;
            const valid = this.arrDetalle.filter(function(e){return cuenta.id == e.id});
            if(valid.length > 0){
                Swal.fire("Atención!","Esta cuenta ya fue agregada","warning");
                return false;
            }

            this.arrDetalle.push(JSON.parse(JSON.stringify(cuenta)));
        },

        delItem:function(data){
            const index = this.arrDetalle.findIndex(function(e){return e.id == data.id});
            this.arrDetalle.splice(index,1);
        },

        edit:async function(data){
          const formData = new FormData();
          formData.append("id",data.id);
          const response = await fetch(base_url+"/Contabilidad/ConceptosContables/getDatos",{method:"POST",body:formData});
          const objData = await response.json();
          if(objData.status){
                this.common.intId = objData.data.id;
                this.common.showModal = true;
                this.common.modulesTitle = "Editar concepto contable";
                this.strNombre = objData.data.name
                this.intTipo = objData.data.type
                this.intEstado = objData.data.status
                this.arrDetalle = objData.data.detail
                this.arrTipos = objData.tipos;
                this.arrCuentas = objData.cuentas;
          }else{
                Swal.fire("Error",objData.msg,"error");
          }
        },

        del:function(data){
            const objVue = this;
            Swal.fire({
              title:"¿Esta seguro de eliminarlo?",
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
                  const response = await fetch(base_url+"/Contabilidad/ConceptosContables/delDatos",{method:"POST",body:formData});
                  const objData = await response.json();
                  if(objData.status){
                    Swal.fire("Eliminado!",objData.msg,"success");
                    objVue.search();
                  }else{
                    Swal.fire("Error",objData.msg,"error");
                  }
              }else{
                objVue.search();
              }
          });
        },
    }
};
const app = Vue.createApp(App);
app.mount("#app");