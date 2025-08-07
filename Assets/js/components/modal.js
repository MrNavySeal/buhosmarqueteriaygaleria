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
                    'modal-dialog-scrollable': scroll=='true'
                }
            ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{title}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"  @click="close"></button>
                    </div>
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                        <app-button btn="secondary" @click="close" title="Cerrar"></app-button>
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
        scroll:"",
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