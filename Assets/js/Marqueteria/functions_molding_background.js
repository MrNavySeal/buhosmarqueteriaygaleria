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
            strIcon:"",
            intLevel:1,
            intStatus:1,
            strImgUrl:base_url+'/Assets/images/uploads/category.jpg',
            strImage:"",
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
            this.intStatus = 1;
            this.strImage ="";
            this.strImgUrl = base_url+'/Assets/images/uploads/category.jpg';
            this.common.modulesTitle = "Nuevo fondo";
        },

        save:async function(){
            const formData = new FormData();
            formData.append("id",this.common.intId);
            formData.append("status",this.intStatus);
            formData.append("image",this.strImage);
            this.common.processing =true;
            const response = await fetch(base_url+"/Marqueteria/MarqueteriaFondos/setFondo",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing =false;
            if(objData.status){
                this.common.strName ="";
                this.common.intId =0;
                this.common.showModalModule = false;
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
            const response = await fetch(base_url+"/Marqueteria/MarqueteriaFondos/getFondos",{method:"POST",body:formData});
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
            const response = await fetch(base_url+"/Marqueteria/MarqueteriaFondos/getFondo",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                this.common.intId = objData.data.id;
                this.intStatus = objData.data.status;
                this.intLevel = objData.data.level;
                this.strImgUrl = objData.data.url;
                this.common.showModalModule = true;
                this.common.modulesTitle = "Editar fondo";
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
                    const response = await fetch(base_url+"/Marqueteria/MarqueteriaFondos/delFondo",{method:"POST",body:formData});
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

        uploadImagen:function(e){
            this.strImage = e.target.files[0];
            let type = this.strImage.type;
            if(type != "image/png" && type != "image/jpg" && type != "image/jpeg" && type != "image/gif"){
                Swal.fire("Error","Solo se permite imágenes.","error");
            }else{
                let objectUrl = window.URL || window.webkitURL;
                let route = objectUrl.createObjectURL(this.strImage);
                this.strImgUrl = route;
            }
        },
    }
};
const app = Vue.createApp(App);
app.mount("#app");