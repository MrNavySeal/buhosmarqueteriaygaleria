export default {
    template:`
        <div class="mb-3">
            <label :for="label" class="form-label">{{label != '' && title =='' ? label : title}} <span class="text-danger" v-if="required == 'true'">*</span></label>
            <div class="input-group">
                <slot name="left"></slot>
                <input  type="text" :placeholder="placeholder" required="required=='true' ? true : false" :disabled=disabled :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" class="form-control" :value="value" aria-label="Example text with button addon" aria-describedby="button-addon1">
                <slot name="right"></slot>
            </div>
            <ul>
                <li class="text-danger" v-for="(data,index) in errors">{{data}}<br></li>
            </ul>
        </div>
    `,
    props:{
        modelValue:[String,Number],
        errors:{
            type:Array,
            default:[]
        },
        value:"",
        label:{
            type:String,
            default:"",
        },
        title:{
            type:String,
            default:"",
        },
        required:true,
        disabled:"",
        placeholder:"",
        
    },
    emits:['update:modelValue'],
}