export default {
    template:`
        <button :class="{ 
            'btn btn-primary': btn=='primary',
            'btn btn-secondary': btn=='secondary',
            'btn btn-info': btn=='info',
            'btn btn-warning': btn=='warning',
            'btn btn-danger': btn=='danger',
            'btn btn-success': btn=='success'
        }" :disabled = processing :type="type"
        >
            <div v-show="!processing" class="d-flex align-items-center gap-2">
                {{title}}
                <div v-show="icon!=''">
                    <i v-show="icon == 'back'" class="fas fa-reply"></i>
                    <i v-show="icon == 'search'" class="fas fa-search"></i>
                    <i v-show="icon == 'duplicate_window'" class="fas fa-window-restore"></i>
                    <i v-show="icon == 'new'" class="fas fa-plus"></i>
                    <i v-show="icon == 'save'" class="fas fa-save"></i>
                    <i v-show="icon == 'watch'" class="fas fa-eye"></i>
                    <i v-show="icon == 'edit'" class="fas fa-pencil-alt"></i>
                    <i v-show="icon == 'delete'" class="fas fa-trash-alt"></i>
                </div>
            </div> 
            <div v-show="processing"><span  class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>
        </button>
    `,
    props:{
        btn:{
            type:String,
            default:"primary"
        },
        icon:{
            type:String,
            default:"",
        },
        type:{
            type:String,
            default:"button",
        },
        title:""
    },
    data(){
        return {
            processing:false,
        };
    }
}