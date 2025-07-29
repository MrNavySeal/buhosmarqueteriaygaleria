export default {
    template:`
        <div class="mb-3">
            <label :for="label" class="form-label">{{label != '' && title =='' ? label : title}} <span class="text-danger" v-if="required == 'true'">*</span></label>
            <div class="input-group">
                <slot name="left"></slot>
                <select class="form-control" :placeholder="placeholder" :required="required=='true' ? true : false" :value = "modelValue" :id="label" @change="$emit('update:modelValue', $event.target.value)">
                    <option v-if="placeholder=='Seleccione'" value="" disabled selected>{{placeholder}}</option>
                    <slot name="options"></slot>
                </select>
                <slot name="right"></slot>
            </div>
            <ul>
                <li class="text-danger" v-for="(data,index) in errors">{{data}}<br></li>
            </ul>
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
        errors:{
            type:Array,
            default:[],
        },
    },
    emits:['update:modelValue'],
}