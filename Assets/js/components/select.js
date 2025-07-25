export default {
    template:`
        <div class="mb-3">
            <label :for="label" class="form-label">{{label != '' && title =='' ? label : title}} <span class="text-danger" v-if="required == 'true'">*</span></label>
            <select class="form-control" :placeholder="placeholder" :required="required=='true' ? true : false" :value = "modelValue" :id="label" name="perPage" @change="$emit('update:modelValue', $event.target.value)">
                <option v-if="placeholder==''" value="" disabled selected>{{placeholder}}</option>
                <slot></slot>
            </select>
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
        type:"text",
        disabled:"",
        placeholder:{
            type:String,
            default:"Seleccione"
        },
        required:"",
    },
    emits:['update:modelValue'],

}