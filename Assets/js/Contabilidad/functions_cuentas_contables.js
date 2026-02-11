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
            common:common,
            arrAccounts: [],
            objAccount:{},
            errors:{}
        };
    },mounted(){
        this.getData();
    },methods:{
        search:async function(){
            const formData = new FormData();
            formData.append("search",this.common.strSearch);
            const response = await fetch(base_url+"/Contabilidad/CuentasContables/getBuscar",{method:"POST",body:formData});
            const objData = await response.json();
            this.arrAccounts = objData;
        },

        openModal:async function(account,type="new"){
            const formData = new FormData();
            formData.append("account",JSON.stringify(account));
            formData.append("type",type)
            const response = await fetch(base_url+"/Contabilidad/CuentasContables/getCuentasPadres",{method:"POST",body:formData});
            const objData = await response.json();
            this.objAccount = objData;

            this.common.showModalModule = true;
            this.common.intId =0;
            this.common.modulesTitle = type == "new" ? "Nueva cuenta contable" : "Editar cuenta contable";
        },

        getData:async function(){
            const response = await fetch(base_url+"/Contabilidad/CuentasContables/getDatosIniciales");
            const objData = await response.json();
            this.arrAccounts = objData;
        },

        save:async function(){
            const formData = new FormData();
            this.objAccount['status'] = this.common.intStatus;
            formData.append("account",JSON.stringify(this.objAccount));
            this.common.processing =true;
            const response = await fetch(base_url+"/Contabilidad/CuentasContables/setDatos",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing =false;
            this.errors = {};
            if(objData.status){
                this.common.showModalModule = false;
                this.getData();
                Swal.fire("Guardado!",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
                this.errors = objData.errors;
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
                    const response = await fetch(base_url+"/Contabilidad/CuentasContables/delDatos",{method:"POST",body:formData});
                    const objData = await response.json();
                    if(objData.status){
                        Swal.fire("Eliminado!",objData.msg,"success");
                        objVue.getData();
                    }else{
                        Swal.fire("Error",objData.msg,"error");
                    }
                }else{
                    objVue.getData();
                }
            });
            
        }
    }
};
const app = Vue.createApp(App);
app.mount("#app");