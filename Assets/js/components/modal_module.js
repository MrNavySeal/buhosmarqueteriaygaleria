import AppButton from "./button.js"; 
import AppModal from "./modal.js"; 
import AppInput from "./input.js";
import {common} from "./variables.js";

export default {
    components:{
        "app-button":AppButton,
        "app-modal":AppModal,
        "app-input":AppInput,
    },
    template:`
        <app-modal :title="common.modulesTitle" id="modalModules" v-model="common.showModalModule">
            <template #body>
                <app-input label="" type="hidden"  v-model="common.intId"></app-input>
                <app-input label="Nombre" type="text" required v-model="common.strName"></app-input>
            </template>
            <template #footer>
                <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled=common.processing></app-button>
            </template>
        </app-modal>
    `,
    data(){
        return {
            common:common,
        }
    },
    methods:{
        save:async function(){
            const formData = new FormData();
            formData.append("name",this.common.strName);
            formData.append("id",this.common.intId);
            this.common.processing =true;
            const response = await fetch(base_url+"/Modulos/setModulo",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.strName ="";
            this.common.intId =0;
            this.common.processing =false;
            this.common.showModalModule = false;
            if(objData.status){
                this.$emit("saved","");
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                Swal.fire("Error",objData.msg,"error");
            }
        }
    }
};