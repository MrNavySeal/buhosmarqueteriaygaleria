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
            showPermissionModal:false,
            arrPermissions:[],
            intIdRol:0,
            checkR:false,
            checkW:false,
            checkU:false,
            checkD:false,
            strRol:"",   
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
            this.common.modulesTitle = "Nuevo rol";
        },
        savePermissions:async function(){
            const formData = new FormData();
            formData.append("data",JSON.stringify(this.arrPermissions));
            formData.append("id",this.intIdRol);
            this.common.processing =true;
            const response = await fetch(base_url+"/Sistema/Roles/setPermisos",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing =false;
            this.showPermissionModal = false;
            if(objData.status){
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
            
        },
        save:async function(){
            const formData = new FormData();
            formData.append("name",this.common.strName);
            formData.append("id",this.common.intId);
            this.common.processing =true;
            const response = await fetch(base_url+"/Sistema/Roles/setRol",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.strName ="";
            this.common.intId =0;
            this.common.processing =false;
            this.common.showModalModule = false;
            if(objData.status){
                this.search();
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
            const response = await fetch(base_url+"/Sistema/Roles/getRoles",{method:"POST",body:formData});
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
            const response = await fetch(base_url+"/Sistema/Roles/getRol",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.common.strName =objData.data.name;
                this.common.intId = objData.data.id;
                this.common.showModalModule = true;
                this.common.modulesTitle = "Editar rol";
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
                    const response = await fetch(base_url+"/Sistema/Roles/delRol",{method:"POST",body:formData});
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
        permissions:async function(data){
            this.intIdRol = data.id;
            const formData = new FormData();
            formData.append("id",data.id);
            const response = await fetch(base_url+"/Sistema/Roles/getPermisos",{method:"POST",body:formData});
            const objData = await response.json();
            this.showPermissionModal = true;
            this.arrPermissions = objData.data;
            this.checkR = objData.r;
            this.checkW = objData.w;
            this.checkU = objData.u;
            this.checkD = objData.d;
            this.strRol = data.name;
        },
        setPermission:function(type="module",data=[]){
            if(type=="all"){
                for (let i = 0; i < this.arrPermissions.length; i++) {
                    const module = this.arrPermissions[i];
                    module.r = this.checkR;
                    module.w = this.checkW;
                    module.u = this.checkU;
                    module.d = this.checkD;
                    module.options.forEach(option=>{
                        option.r = module.r;
                        option.w = module.w;
                        option.u = module.u;
                        option.d = module.d;
                    });
                    module.sections.forEach(section=>{
                        section.r = module.r;
                        section.w = module.w;
                        section.u = module.u;
                        section.d = module.d;
                        section.options.forEach(option=>{
                            option.r = module.r;
                            option.w = module.w;
                            option.u = module.u;
                            option.d = module.d;
                        });
                    });
                }
            }else if(type=="module"){
                data.options.forEach(option=>{
                    option.r = data.r;
                    option.w = data.w;
                    option.u = data.u;
                    option.d = data.d;
                });
                data.sections.forEach(section=>{
                    section.r = data.r;
                    section.w = data.w;
                    section.u = data.u;
                    section.d = data.d;
                    section.options.forEach(option=>{
                        option.r = data.r;
                        option.w = data.w;
                        option.u = data.u;
                        option.d = data.d;
                    });
                });
            }else{
                data.options.forEach(option=>{
                    option.r = data.r;
                    option.w = data.w;
                    option.u = data.u;
                    option.d = data.d;
                });
            }
            
        }
    }
};
const app = Vue.createApp(App);
app.mount("#app");