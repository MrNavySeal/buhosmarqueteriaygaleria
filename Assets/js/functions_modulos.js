import ModalModule from "./components/modal_module.js"; 
import AppButton from "./components/button.js"; 
import AppInput from "./components/input.js";
import AppSelect from "./components/select.js";
import AppPagination from "./components/pagination.js"
import {common} from "./components/variables.js";
const App = {
    components:{
        "modal-module":ModalModule,
        "app-button":AppButton,
        "app-input":AppInput,
        "app-select":AppSelect,
        "app-pagination":AppPagination,
    },
    data(){
        return {
            common:common
        }
    },
    mounted(){
        this.search();
    },
    methods:{
        search:async function(page=1){
            const formData = new FormData();
            this.common.intPage = page;
            formData.append("page",this.common.intPage);
            formData.append("per_page",this.common.intPerPage);
            formData.append("search",this.common.strSearch);
            const response = await fetch(base_url+"/Modulos/getModulos",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.arrData = objData.data;
            this.common.intStartPage  = objData.start_page;
            this.common.intTotalButtons = objData.limit_page;
            this.common.intTotalPages = objData.total_pages;
            this.common.intTotalResults = objData.total_records;
            this.common.arrButtons = objData.buttons;
            
        }
    }
};
const app = Vue.createApp(App);
app.mount("#app");