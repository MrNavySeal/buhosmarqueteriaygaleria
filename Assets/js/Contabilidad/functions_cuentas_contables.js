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
        search:function(){
             
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
            const response = await fetch(base_url+"/Contabilidad/CuentasContables/setDatos",{method:"POST",body:formData});
            const objData = await response.json();
            this.errors = {};
            if(objData.status){
                this.common.showModalModule = false;
                Swal.fire("Guardado!",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
                this.errors = objData.errors;
            }
            console.log(objData);
        }
    }
};
const app = Vue.createApp(App);
app.mount("#app");