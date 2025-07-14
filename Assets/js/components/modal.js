import AppButton from "./button.js";
export default {
    components:{
        "app-button":AppButton
    },
    template:`
        <div class="modal fade" :id="id">
            <div class="modal-dialog modal-dialog-centered" :class="{
                    'modal-sm': size=='sm',
                    'modal-md': size=='md',
                    'modal-lg': size=='lg',
                    'modal-xl': size=='xl',
                    'modal-fullscreen': size=='full',
                }
            ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{title}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"  @click="close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formItem" name="formItem" class="mb-4">
                            <input type="hidden" id="idRol" name="idRol" value="">
                            <div class="mb-3">
                                <label for="txtName" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="txtName" name="txtName">
                            </div>
                            <div class="modal-footer">
                                <app-button btn="primary" icon="save" title="Guardar"></app-button>
                                <app-button btn="secondary" @click="close" title="Cerrar"></app-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `,
    props:{
        title:"",
        id:"",
        size:"", //modal-sm,modal-lg,modal-xl, modal-fullscreen
        modelValue: Boolean,
    },
    data(){
        return{
            modal:null,
        }
    },
    mounted(){
        this.modal = new bootstrap.Modal(document.getElementById(this.id));
        const modalElement = document.getElementById(this.id);
        modalElement.addEventListener('hidden.bs.modal', () => {
            this.$emit('update:modelValue',false);
        });
        modalElement.addEventListener('shown.bs.modal', () => {
            this.$emit('update:modelValue',true);
        });
    },
    watch:{
        modelValue:function(value){
            if(value){
                this.modal.show();
            }else{
                this.modal.hide();
            }
        }
    },
    methods:{
        close:function(){
            this.modal.hide();
        }

    }
}