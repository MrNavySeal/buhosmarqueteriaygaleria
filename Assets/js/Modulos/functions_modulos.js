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
    data(){
        return {
            common:common,
        }
    },
    mounted(){
        this.search();
    },
    methods:{
        openModal:function(){
            this.common.showModalModule = true;
            this.common.strName ="";
            this.common.intId =0;
            this.common.modulesTitle = "Nuevo modulo";
        },
        save:async function(){
            const formData = new FormData();
            formData.append("name",this.common.strName);
            formData.append("id",this.common.intId);
            this.common.processing =true;
            const response = await fetch(base_url+"/Modulos/Modulos/setModulo",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.strName ="";
            this.common.intId =0;
            this.common.processing =false;
            this.common.showModalModule = false;
            if(objData.status){
                this.search(this.common.intPage);
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        },
        search:async function(page=1){
            const formData = new FormData();
            this.common.intPage = page;
            formData.append("page",this.common.intPage);
            formData.append("per_page",this.common.intPerPage);
            formData.append("search",this.common.strSearch);
            const response = await fetch(base_url+"/Modulos/Modulos/getModulos",{method:"POST",body:formData});
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
            const response = await fetch(base_url+"/Modulos/Modulos/getModulo",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.common.strName =objData.data.name;
                this.common.intId = objData.data.id;
                this.common.showModalModule = true;
                this.common.modulesTitle = "Editar modulo";
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
                    const response = await fetch(base_url+"/Modulos/Modulos/delModulo",{method:"POST",body:formData});
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
            
        }
    }
};
const app = Vue.createApp(App);
app.mount("#app");