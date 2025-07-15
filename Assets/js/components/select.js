export default {
    template:`
        <div class="mb-3">
            <label :for="label" class="form-label">{{label}}</label>
            <select class="form-control" :placeholder="placeholder" :id="label" name="perPage" @change="$emit('update:modelValue', $event.target.value)">
                <option v-if="placeholder!=''" value="" disabled selected>{{placeholder}}</option>
                <slot></slot>
            </select>
        </div>
    `,
    props:{
        modelValue:[String,Number],
        label:"",
        type:"text",
        disabled:"",
        placeholder:""
    },
    emits:['update:modelValue'],

}