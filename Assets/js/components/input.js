export default {
    template:`
        <div class="mb-3" v-if="type != 'hidden' && type != 'switch'">
            <label :for="label" class="form-label">{{label != '' && title =='' ? label : title}} <span class="text-danger" v-if="required == 'true'">*</span></label>
            <input :type="type" :placeholder="placeholder" :value="modelValue" :required="required=='true' ? true : false" :disabled=disabled class="form-control" :id="label" @input="$emit('update:modelValue', $event.target.value)">
            <ul>
                <li class="text-danger" v-for="(data,index) in errors">{{data}}<br></li>
            </ul>
        </div>
        <div v-else-if="type=='switch'">
            <div  class="form-check form-switch">
                <label :for="label" class="form-check-label">{{title}} <span class="text-danger" v-if="required == 'true'">*</span></label>
                <input role="switch" type="checkbox" :checked="modelValue" :required="required=='true' ? true : false" :disabled=disabled class="form-check-input" :id="label" @input="$emit('update:modelValue', $event.target.checked)"></input>
            </div>
            <ul class="m-0">
                <li class="text-danger" v-for="(data,index) in errors">{{data}}<br></li>
            </ul>
        </div>
        <input v-else :type="type" :placeholder="placeholder" :value="modelValue" :required=required :disabled=disabled class="form-control" :id="label" @input="$emit('update:modelValue', $event.target.value)">
        
    `,
    props:{
        modelValue:[String,Number,Boolean],
        label:{
            type:String,
            default:"",
        },
        type:{
            type:String,
            default:"text",
        },
        title:{
            type:String,
            default:"",
        },
        errors:{
            type:Array,
            default:[],
        },
        disabled:"",
        placeholder:"",
        required:false,
    },
    emits:['update:modelValue'],

}