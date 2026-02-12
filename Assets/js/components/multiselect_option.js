export default{
    template:`
        <li>
            <label :for="'selectMultipleOption'+tag">
                <input type="checkbox" :id="'selectMultipleOption'+tag" :checked="checked" @change="checkOption" >
                <slot/>
            </label>
        </li>
    `,
    inject:['multiSelect'],
    props:{
        tag:{
            type:[String,Number],
            default:"",
        },
        data:{
            type:Object,
            default:{}
        },
        checked:{
            type:Boolean,
            default:false
        },
    },
    methods:{
        //Capturo la opción seleccionada y utilizo el componente injectado para utilizar
        // su método, enviándole el index del seleccionado
        checkOption:function(){
            const key = this.multiSelect.values.length > 0 ? this.multiSelect.values[0] : Object.keys(this.data)[0];
            const optionKey = this.multiSelect.values.length > 0 ? this.multiSelect.values[0] : Object.keys(this.multiSelect.dataFiltered[0])[0];
            const id = this.data[key];
            const data =this.multiSelect.dataFiltered;
            const index = data.findIndex(e=>e[optionKey] == id);
            this.multiSelect.checkOption(index);
        }
    }
}