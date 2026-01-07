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
            intPais:"",
            intDepartamento:"",
            intCiudad:"",
            strTelefono:"",
            strDireccion:"",
            strContrasena:"",
            intEstado:"",
            intTipoDocumento:"",
            strTituloModal:"",
            intTelefonoCodigo:"",
            arrTiposDocumento:"",
            arrPaises:[],
            arrDepartamentos:[],
            arrCiudades:[],

            showPermissionModal:false,
            arrPermissions:[],
            checkR:false,
            checkW:false,
            checkU:false,
            checkD:false, 
        };
    },mounted(){
        this.search();
        this.getDatosIniciales();
    },methods:{
        getDatosIniciales:async function(){
            const response = await fetch(base_url+"/clientes/clientes/getDatosIniciales");
            const objData = await response.json();
            this.arrPaises = objData.paises;
        },
        setFiltro:async function(tipo){
            if(tipo == "paises" && this.intPais != ""){
                this.intTelefonoCodigo = this.intPais;
                const response = await fetch(base_url+"/clientes/clientes/getEstados/estado/"+this.intPais);
                const objData = await response.json();
                this.arrDepartamentos = objData;
                this.arrCiudades = [];
            }else if(tipo == "departamentos" && this.intDepartamento != ""){
                const response = await fetch(base_url+"/clientes/clientes/getEstados/ciudad/"+this.intDepartamento);
                const objData = await response.json();
                this.arrCiudades = objData;
            }
        },
        openModal:async function(){
            await this.getDatosIniciales();
            this.common.showModalModule = true;
            this.common.intId =0;
            this.common.modulesTitle = "Nuevo cliente";
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
            await this.setFiltro("paises");
        },
        save: async function(){
            if(this.strNombre == "" || this.strApellido == "" || this.strTelefono == ""   || this.intPais == "" 
                || this.intDepartamento == "" || this.intCiudad == "" || this.intRol ==""
            ){
                Swal.fire("Error","Todos los campos marcados con (*) son obligatorios","error");
                return false;
            }
            if(this.strContrasena !=""){
                if(this.strContrasena.length < 8){
                    Swal.fire("Error","La contraseña debe tener al menos 8 caracteres","error");
                    return false;
            }
            }
            if(!fntEmailValidate(this.strCorreo) && this.strCorreo!=""){
                Swal.fire("Error","El email es invalido","error");
                return false;
            }
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
            formData.append("fecha",this.strFecha);

            this.common.processing = true;
            const response = await fetch(base_url+"/clientes/clientes/setUsuario",{method:"POST",body:formData});
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
                    this.intTelefonoCodigo="";
                    this.intEstado= 1;
                }
                this.common.showModalModule = false
                this.search();
            }else{
              Swal.fire("Error",objData.msg,"error");
            }
        },
        search:async function (page=1){
            this.common.intPage = page;
            const formData = new FormData();
            formData.append("page",this.common.intPage);
            formData.append("per_page",this.common.intPerPage);
            formData.append("search",this.common.strSearch);
            const response = await fetch(base_url+"/clientes/clientes/getBuscar",{method:"POST",body:formData});
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
          const response = await fetch(base_url+"/clientes/clientes/getDatos",{method:"POST",body:formData});
          const objData = await response.json();
          if(objData.status){
                
                this.strImgUrl= objData.data.url;
                this.strNombre= objData.data.firstname;
                this.strApellido= objData.data.lastname;
                this.strDocumento= objData.data.identification;
                this.strCorreo= objData.data.email;
                this.intPais= objData.data.countryid;
                this.strFecha = objData.data.date;
                await this.setFiltro("paises");
                this.intDepartamento= objData.data.stateid,
                await this.setFiltro("departamentos");
                this.intCiudad= objData.data.cityid;
                this.strTelefono= objData.data.phone;
                this.strDireccion= objData.data.address;
                this.intTipoDocumento=objData.data.typeid;
                this.intTelefonoCodigo = objData.data.phone_country;
                this.intEstado= objData.data.status;
                this.intRol = objData.data.roleid;
                this.strContrasena="";
                this.common.intId = objData.data.id;
                this.common.showModalModule = true;
                this.common.modulesTitle = "Editar usuario";
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
                  const response = await fetch(base_url+"/clientes/clientes/delDatos",{method:"POST",body:formData});
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