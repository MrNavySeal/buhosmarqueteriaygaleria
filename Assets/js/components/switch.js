export default {
    template:`
        <div class="form-check form-switch">
            <label :for="label" class="form-check-input">{{label}} <span class="text-danger" v-if="required == 'true'">*</span></label>
            <textarea  :rows="rows" :placeholder="placeholder" :value="modelValue" :required="required=='true' ? true : false" :disabled=disabled class="form-check-label" :id="label" @input="$emit('update:modelValue', $event.target.value)"></textarea>
        </div>
    `,
    props:{
        modelValue:[String,Number],
        label:"",
        disabled:"",
        placeholder:"",
        required:false,
        rows:"5"
    },
    emits:['update:modelValue'],

}