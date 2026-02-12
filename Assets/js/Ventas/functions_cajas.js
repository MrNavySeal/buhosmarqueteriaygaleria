import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppButtonInput from "../components/button_input.js"
import AppButtonSelect from "../components/button_select.js"
import {createCommon} from "../components/variables.js";
import AppMultiselectOption from "../components/multiselect_option.js";
import AppMultiselect from "../components/multiselect.js";
const App = {
    components:{
        "app-button":AppButton,
        "app-input":AppInput,
        "app-select":AppSelect,
        "app-pagination":AppPagination,
        "app-modal":AppModal,
        "app-button-input":AppButtonInput,
        "app-button-select":AppButtonSelect,
        "app-multiselect":AppMultiselect,
        "app-multiselect-option":AppMultiselectOption,
    },
    data(){
        return {
            currentController:null,
            common:createCommon(),
            sucursales:createCommon(),
            arrDetalle:[],
            arrMetodos:[],
            errores:[],
            intEstado:1,
            strNombre:"",
            errores:{},
            objSucursal:{name:""},
            objCajero:{name:""},
            selectMetodos:[],
        }
    },
    mounted(){
        this.search();
    },
    methods:{
        getDatosIniciales:async function(){
            const response = await fetch(base_url+"/Ventas/Cajas/getDatosIniciales");
            const objData = await response.json();
            this.arrMetodos = objData.metodos;
        },

        setFiltro:async function(tipo="paises"){
            if(tipo == "paises" && this.intPais != ""){
                const response = await fetch(base_url+"/Ventas/Sucursales/getEstados/estado/"+this.intPais);
                const objData = await response.json();
                this.arrDepartamentos = objData;
                this.arrCiudades = [];
            }else if(tipo == "departamentos" && this.intDepartamento != ""){
                const response = await fetch(base_url+"/Ventas/Sucursales/getEstados/ciudad/"+this.intDepartamento);
                const objData = await response.json();
                this.arrCiudades = objData;
            }
        },

        openModal:async function(){
            await this.getDatosIniciales();
            this.common.showModal = true;
            this.strNombre ="";
            this.intEstado = 1;
            this.common.intId =0;
            this.common.title = "Nueva caja";
            this.errores = {}
        },

        save:async function(){
            const obj = {
                "id":this.common.intId,
                "nombre":this.strNombre,
                "pais":this.intPais,
                "departamento":this.intDepartamento,
                "ciudad":this.intCiudad,
                "telefono":this.strTelefono,
                "direccion":this.strDireccion,
                "estado":this.intEstado,
            }

            this.common.processing =true;
            const response = await fetch(base_url+"/Ventas/Sucursales/setDatos",{method:"POST",body:JSON.stringify(obj)});
            const objData = await response.json();
            this.common.processing =false;
            if(objData.status){
                this.common.strName ="";
                this.common.intId =0;
                this.common.showModal = false;
                this.search(this.common.intPage);
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
                this.errores = objData.errores ? objData.errores : {};
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
            if(type=="sucursales"){
                this.sucursales.intPage = page;
                formData.append("page",this.sucursales.intPage);
                formData.append("per_page",this.sucursales.intPerPage);
                formData.append("search",this.sucursales.strSearch);
                const response = await fetch(base_url+"/Ventas/Cajas/getBuscar",{method:"POST",body:formData,signal});
                const objData = await response.json();
                this.sucursales.arrData = objData.data;
                this.sucursales.intStartPage  = objData.start_page;
                this.sucursales.intTotalButtons = objData.limit_page;
                this.sucursales.intTotalPages = objData.total_pages;
                this.sucursales.intTotalResults = objData.total_records;
                this.sucursales.arrButtons = objData.buttons;
            }else{
                this.common.intPage = page;
                formData.append("page",this.common.intPage);
                formData.append("per_page",this.common.intPerPage);
                formData.append("search",this.common.strSearch);
                const response = await fetch(base_url+"/Ventas/Cajas/getBuscar",{method:"POST",body:formData,signal});
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
            if(this.objSucursal.name == ""){
                Swal.fire("Atención!","Debe seleccionar un cajero","warning");
                return false;
            }
            const data = this.objSucursal;
            const valid = this.arrDetalle.filter(function(e){return data.id == e.id});
            if(valid.length > 0){
                Swal.fire("Atención!","Este cajero ya fue agregado","warning");
                return false;
            }
            this.arrDetalle.push(JSON.parse(JSON.stringify(data)));
        },

        delItem:function(data){
            const index = this.arrDetalle.findIndex(function(e){return e.id == data.id});
            this.arrDetalle.splice(index,1);
        },

        selectItem:function(data,type=""){
            if(type=="sucursales"){
                this.objSucursal = data;
                this.sucursales.showModal = false;
            }
        },

        edit:async function(data){
            const formData = new FormData();
            formData.append("id",data.id);
            const response = await fetch(base_url+"/Ventas/Sucursales/getDatos",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.common.intId = objData.data.id;
                this.strNombre =objData.data.name;
                this.intPais = objData.data.country_id;
                this.intDepartamento = objData.data.state_id;
                this.intCiudad = objData.data.city_id;
                this.strTelefono = objData.data.phone;
                this.strDireccion = objData.data.address;
                this.intEstado = objData.data.status;
                this.arrDepartamentos = objData.departamentos;
                this.arrCiudades = objData.ciudades;
                this.arrPaises = objData.paises;
                this.common.title = "Editar sucursal";
                this.common.showModal = true;
                this.errores = {}
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
                    const response = await fetch(base_url+"/Ventas/Sucursales/delDatos",{method:"POST",body:formData});
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