export default {
    template:`
        <div class="mb-3" v-if="type != 'hidden'">
            <label :for="label" class="form-label">{{label}}</label>
            <input :type="type" :placeholder="placeholder" :value="modelValue" :required=required :disabled=disabled class="form-control" :id="label" @input="$emit('update:modelValue', $event.target.value)">
        </div>
        <input v-else :type="type" :placeholder="placeholder" :value="modelValue" :required=required :disabled=disabled class="form-control" :id="label" @input="$emit('update:modelValue', $event.target.value)">
    `,
    props:{
        modelValue:[String,Number],
        label:"",
        type:"text",
        disabled:"",
        placeholder:"",
        required:false,
    },
    emits:['update:modelValue'],

}