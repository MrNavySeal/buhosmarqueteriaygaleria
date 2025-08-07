export default {
    template:`
        <div class="mb-3">
            <label :for="label" class="form-label">{{label != '' && title =='' ? label : title}} <span class="text-danger" v-if="required == 'true'">*</span></label>
            <textarea  :rows="rows" :placeholder="placeholder" :value="modelValue" :required="required=='true' ? true : false" :disabled=disabled class="form-control" :id="label" @input="$emit('update:modelValue', $event.target.value)"></textarea>
        </div>
    `,
    props:{
        modelValue:[String,Number],
        label:{
            type:String,
            default:"",
        },
        title:{
            type:String,
            default:"",
        },
        label:"",
        disabled:"",
        placeholder:"",
        required:false,
        rows:"5"
    },
    emits:['update:modelValue'],

}