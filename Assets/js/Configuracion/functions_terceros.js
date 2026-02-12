import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import {common} from "../components/variables.js";

const App = {
    components:{
        "app-button":AppButton,
        "app-input":AppInput,
        "app-select":AppSelect,
        "app-pagination":AppPagination,
        "app-modal":AppModal
    },
    data() {
        return {
            //Modales
            modal:"",
            common:common,

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

            //Variables
            intId:0,
            strImgUrl:base_url+'/Assets/images/uploads/user.jpg',
            strImagen:"",
            strNombre:"",
            strApellido:"",
            strDocumento:"",
            strCorreo:"",
            intPais:47,
            intDepartamento:"",
            intCiudad:"",
            strTelefono:"",
            strDireccion:"",
            strContrasena:"",
            intEstado:"",
            intTipoDocumento:13,
            intTipoPersona:2,
            intTipoRegimen:2,
            strDv:"",
            strTituloModal:"",
            intTelefonoCodigo:"",
            arrTipoDocumento:[],
            arrTipoRegimen:[],
            arrTipoPersona:[],
            arrPaises:[],
            arrDepartamentos:[],
            arrCiudades:[],
            errores:[],
            intCheckCliente:false,
            intCheckProveedor:false,
            intCheckOtro:false,
            intCheckUsuario:false,
        };
    },mounted(){
        this.search();
        this.getDatosIniciales();
    },methods:{
        getDatosIniciales:async function(){
            const response = await fetch(base_url+"/configuracion/terceros/getDatosIniciales");
            const objData = await response.json();
            this.arrPaises = objData.paises;
            this.arrTipoDocumento = objData.tipo_identificacion;
            this.arrTipoPersona = objData.tipo_persona; 
            this.arrTipoRegimen = objData.tipo_regimen;
        },
        setFiltro:async function(tipo){
            if(tipo == "paises" && this.intPais != ""){
                this.intTelefonoCodigo = this.getPhoneCode;
                const response = await fetch(base_url+"/configuracion/terceros/getEstados/estado/"+this.intPais);
                const objData = await response.json();
                this.arrDepartamentos = objData;
                this.arrCiudades = [];
            }else if(tipo == "departamentos" && this.intDepartamento != ""){
                const response = await fetch(base_url+"/configuracion/terceros/getEstados/ciudad/"+this.intDepartamento);
                const objData = await response.json();
                this.arrCiudades = objData;
            }
        },
        openModal:async function(){
            await this.getDatosIniciales();
            this.common.showModalModule = true;
            this.common.intId =0;
            this.common.modulesTitle = "Nuevo tercero";
            this.strImgUrl= base_url+'/Assets/images/uploads/user.jpg';
            this.strImagen= "";
            this.strNombre ="";
            this.strApellido= "";
            this.strDocumento= "";
            this.strCorreo= "";
            this.strTelefono= "";
            this.strDireccion="";
            this.strContrasena="";
            this.intEstado= 1;
            this.intPais= 47;
            this.intCheckCliente=false;
            this.intCheckProveedor = false;
            this.intCheckOtro = false;
            this.intTelefonoCodigo="";
            this.strDv="";
            this.intTipoDocumento=13;
            this.intTipoRegimen=2;
            this.intTipoPersona=2;
            await this.setFiltro("paises");
        },
        save: async function(){
            const formData = new FormData();
            formData.append("id",this.common.intId);
            formData.append("imagen",this.strImagen);
            formData.append("nombre",this.strNombre);
            formData.append("apellido",this.strApellido);
            formData.append("documento",this.strDocumento);
            formData.append("correo",this.strCorreo);
            formData.append("pais",this.intPais);
            formData.append("departamento",this.intDepartamento);
            formData.append("ciudad",this.intCiudad);
            formData.append("telefono",this.strTelefono);
            formData.append("direccion",this.strDireccion);
            formData.append("contrasena",this.strContrasena);
            formData.append("estado",this.intEstado);
            formData.append("is_cliente",this.intCheckCliente ? 1 : 0);
            formData.append("is_proveedor",this.intCheckProveedor ? 1 : 0);
            formData.append("is_otro",this.intCheckOtro ? 1 : 0);
            formData.append("is_usuario",this.intCheckUsuario ? 1 : 0);
            formData.append("indicativo",this.intTelefonoCodigo);
            formData.append("digito_verificacion",this.strDv);
            formData.append("tipo_documento",this.intTipoDocumento);
            formData.append("tipo_regimen",this.intTipoRegimen);
            formData.append("tipo_persona",this.intTipoPersona);
            formData.append("fecha",this.strFecha);

            this.common.processing = true;
            const response = await fetch(base_url+"/configuracion/terceros/setDatos",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing = false;
            if(objData.status){
                Swal.fire("Guardado!",objData.msg,"success");
                if(this.intId == 0){
                    this.strImgUrl= base_url+'/Assets/images/uploads/user.jpg';
                    this.strImagen= "";
                    this.strApellido= "";
                    this.strDocumento= "";
                    this.strCorreo= "";
                    this.intPais= "";
                    this.intDepartamento= "";
                    this.intCiudad= "";
                    this.strTelefono= "";
                    this.strDireccion="";
                    this.strContrasena="";
                    this.intTipoDocumento="";
                    this.intEstado= 1;
                    this.intCheckCliente=false;
                    this.intCheckProveedor = false;
                    this.intCheckOtro = false;
                    this.intTelefonoCodigo="";
                    this.strDv="";
                    this.intTipoDocumento=13;
                    this.intTipoRegimen=2;
                    this.intTipoPersona=2;
                }
                this.common.showModalModule = false
                this.search();
            }else{
              Swal.fire("Error",objData.msg,"error");
              this.errores = objData.errores ? objData.errores : [];
            }
        },

        search:async function (page=1){
            this.common.intPage = page;
            const formData = new FormData();
            formData.append("page",this.common.intPage);
            formData.append("per_page",this.common.intPerPage);
            formData.append("search",this.common.strSearch);
            const response = await fetch(base_url+"/configuracion/terceros/getBuscar",{method:"POST",body:formData});
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
          const response = await fetch(base_url+"/configuracion/terceros/getDatos",{method:"POST",body:formData});
          const objData = await response.json();
          if(objData.status){
                this.strImgUrl= objData.data.url;
                this.strNombre= objData.data.firstname;
                this.strApellido= objData.data.lastname;
                this.strDocumento= objData.data.identification;
                this.strCorreo= objData.data.email;
                this.intPais= objData.data.countryid;
                this.strFecha = objData.data.date;
                this.intDepartamento= objData.data.stateid,
                this.intCiudad= objData.data.cityid;
                this.strTelefono= objData.data.phone;
                this.strDireccion= objData.data.address;
                this.intTipoDocumento=objData.data.typeid;
                this.intEstado= objData.data.status;
                this.intRol = objData.data.roleid;
                this.strContrasena="";
                this.intCheckCliente=objData.data.is_client;
                this.intCheckProveedor =objData.data.is_supplier;
                this.intCheckOtro =objData.data.is_other;
                this.intCheckUsuario = objData.data.is_user;
                this.strDv=objData.data.dv;
                this.intTipoDocumento=objData.data.identification_type;
                this.intTipoRegimen=objData.data.regimen_type;
                this.intTipoPersona=objData.data.person_type;
                this.arrDepartamentos = objData.departamentos;
                this.arrCiudades = objData.ciudades;
                this.arrPaises = objData.paises;
                this.intTelefonoCodigo = this.getPhoneCode;
                this.common.intId = objData.data.id;
                this.common.showModalModule = true;
                this.common.modulesTitle = "Editar tercero";
          }else{
                Swal.fire("Error",objData.msg,"error");
          }
        },

        openBotones:function(tipo,dato){ 
            if(tipo == "correo")window.open('mailto:'+dato);
            if(tipo == "llamar")window.open('tel:'+dato);
            if(tipo == "wpp")window.open('https://wa.me/'+dato);
        },

        uploadImagen:function(e){
            this.strImagen = e.target.files[0];
            let type = this.strImagen.type;
            if(type != "image/png" && type != "image/jpg" && type != "image/jpeg" && type != "image/gif"){
                Swal.fire("Error","Solo se permite imágenes.","error");
            }else{
                let objectUrl = window.URL || window.webkitURL;
                let route = objectUrl.createObjectURL(this.strImagen);
                this.strImgUrl = route;
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
                  const response = await fetch(base_url+"/configuracion/terceros/delDatos",{method:"POST",body:formData});
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

    },computed:{
        getPhoneCode(){
            const code = this.intPais;
            return this.arrPaises.filter(function(e){return e.id == code})[0]['phonecode']
        }
    }
};
const app = Vue.createApp(App);
app.mount("#app");