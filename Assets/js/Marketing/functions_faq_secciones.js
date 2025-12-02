
import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import {createCommon} from "../components/variables.js";

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
            common:createCommon(),

            //Variables
            intId:0,
            strName:"",
            strRespuesta:"",
            intStatus:"",
        };
    },mounted(){
        this.search(1,"seccion");
    },methods:{
        openModal:async function(){
            this.common.showModalModule = true;
            this.common.intId =0;
            this.common.modulesTitle = "Nueva sección de FAQ";
            this.strName ="";
            this.strRespuesta= "";
            this.intStatus= 1;
            this.common.errors = "";
        },

        save: async function(){
            const formData = new FormData();
            formData.append("id",this.common.intId);
            formData.append("name",this.strName);
            formData.append("estado",this.intStatus);
            this.common.processing = true;
            const response = await fetch(base_url+"/Marketing/Faq/setSeccion",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing = false;

            if(objData.status){
                Swal.fire("Guardado!",objData.msg,"success");
                if(this.common.intId == 0){
                    this.common.intId =0;
                    this.strName ="";
                    this.intStatus= 1;
                }
                this.common.showModalModule = false;
                this.search(1,"seccion");
            }else{
              Swal.fire("Error",objData.msg,"error");
              this.common.errors = objData.errores;
            }
        },

        search:async function (page=1,strTipo = ""){
            this.common.intPage = page;
            const formData = new FormData();
            formData.append("page",this.common.intPage);
            formData.append("per_page",this.common.intPerPage);
            formData.append("search",this.common.strSearch);
            formData.append("tipo_busqueda",strTipo)
            const response = await fetch(base_url+"/Marketing/Faq/getBuscar",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.arrData = objData.data;
            this.common.intStartPage  = objData.start_page;
            this.common.intTotalButtons = objData.limit_page;
            this.common.intTotalPages = objData.total_pages;
            this.common.intTotalResults = objData.total_records;
            this.common.arrButtons = objData.buttons;
        },

        edit:async function(intId,strTipo){
            
            this.common.intId =intId;
            this.common.modulesTitle = "Editar sección de FAQ";
            const formData = new FormData();
            formData.append("id",this.common.intId);
            formData.append("tipo_busqueda",strTipo);
            const response = await fetch(base_url+"/Marketing/Faq/getDatos",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.strName= objData.data.question,
                this.intStatus= objData.data.status,
                this.common.showModalModule = true;
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        },

        del:function(intId,strTipo){
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
                  objVue.common.intId = intId;
                  const formData = new FormData();
                  formData.append("id",objVue.common.intId);
                  formData.append("tipo_busqueda",strTipo);
                  const response = await fetch(base_url+"/Marketing/Faq/delDatos",{method:"POST",body:formData});
                  const objData = await response.json();
                  if(objData.status){
                    Swal.fire("Eliminado!",objData.msg,"success");
                    objVue.search(1,"seccion");
                  }else{
                    Swal.fire("Error",objData.msg,"error");
                  }
              }else{
                objVue.search(1,"seccion");
              }
          });
        },
    }
};
const app = Vue.createApp(App);
app.mount("#app");