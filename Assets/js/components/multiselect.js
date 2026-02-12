import {filterArray} from "../utils/arrays.js";
export default{
    template:`
        <div class="mb-3" v-clickout>
            <label class="form-label" @click="show">{{title}} <span v-if="required=='true'" class="text-danger fw-bolder">*</span></label>
            <div class="select-multiple-container d-flex position-relative" @click="show">
                <div class="select-multiple" :id="'selectMultiple'+tag" > {{showData}}</div>
                <div class="position-absolute end-0"> 
                    <svg xmlns="http://www.w3.org/2000/svg" stroke-width="30" stroke="#000000" class="fs-" height="18px" viewBox="0 -960 960 960" width="18px" fill="#1f1f1f"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>
                </div>
                <ul class="select-multiple-content" v-show="isShow" @click="show">
                    <input v-show="search=='true'" type="search" style="position:sticky;top:0" placeholder="buscar" class="form-control" v-model="strSearch">
                    <li>
                        <label :for="'selectMultiple'+tag+'All'" >
                            <input type="checkbox"  v-model="intCheckAll"  :id="'selectMultiple'+tag+'All'" @click="checkAll">
                            Todos
                        </label>
                    </li>
                    <slot :options="filteredOptions"/>
                </ul>
            </div>
            <ul class="m-0 p-0 fs-22 list-style-none fw-bold"><li class="text-danger" v-for="(data,index) in errors" :key="index">{{data}}</li></ul>
        </div>
    `,
    props:{
        required:{
            type:String,
            default:'false'
        },
        title:{ //Titulo o nombre de la etiqueta
            type:String,
            default:"" 
        },
        format:{ //Separador para formatear los datos seleccionados
            type:String,
            default:"",
        },
        values:{ //Son los valores que se van tomar en cuenta al momento de actualizar la propiedad enlazada con v-model
            type:Array,
            default:[],
        },
        showup:{ //Son las llaves del arreglo que se utilizarán al mostrar el resultado de los datos seleccionados
            type:Array,
            default:[],
        },
        tag:{ //Etiqueta para diferenciar el seleccionador de los demás
            type:String,
            default:""
        },
        placeholder:{ //Texto por defecto al cargar el seleccionador
            type:String,
            default:"Seleccione"
        },
        options:{ //Son las opciones a mostrar dentro del seleccionador
            type:Array,
            default:[]
        },
        search:{ //Habilita si el seleccionador tiene buscador o no
            type:String,
            default:"",
        },
        modelValue:{ //Propiedad que se enlaza con la directiva v-model
            type:Array,
            default:[],
        },
        errors:[]
    },
    data(){
        return{
            isShow:false,
            intCheckAll:false,
            showData:"",
            valueData:"",
            strSearch:"",
            dataFiltered:[],
        }
    },
    provide(){
        //Proveeo este componente para hacer las injecciones en sus componentes hijos 
        //para utilizar todas sus propiedades y métodos
        return{
            multiSelect:this,
        }
    },
    mounted(){
        this.validSelected();
    },
    methods:{
        //Asigna valores por defecto
        setDefault:function(){
            if(this.options.length > 0){
                const defaultKey= [Object.keys(this.options[0])[0]];
                this.filteredOptions.forEach(e => {
                    if(this.modelValue.filter(function(n){return e[defaultKey] == n;}).length){
                        e.is_checked = true;
                    }
                });
                this.getChecked();
            }
        },
        //Si no hay datos seleccionados, me muestra el valor asignado en el placeholder
        validSelected:function(){
            if(this.showData == ""){
                this.showData = this.placeholder;
            }
        },
        //Muestra los datos seleccionados
        getChecked:function(){
            const format = this.format;
            const arrValues = this.values.length > 0 ? this.values : [Object.keys(this.options[0])[0]];
            const arrShowUp = this.showup.length > 0 ? this.showup : [Object.keys(this.options[0])[0]];
            const arrData = Object.values(this.options).filter(function(e){return e.is_checked});
            const arrShow = [];
            const arrResult = [];
            arrData.forEach(function(e){
                const tempValues = [];
                const tempShowUp = [];
                arrValues.forEach(function(key){tempValues.push(e[key]);});
                arrShowUp.forEach(function(key){tempShowUp.push(e[key]);});
                arrResult.push(tempValues.join(format));
                arrShow.push(tempShowUp.join(format));
            });
            this.showData = arrShow.join(",");
            this.valueData = arrResult;
            this.validSelected();
            this.$emit("update:modelValue",this.valueData);
        },
        //Selecciona todas o ninguna de las opciones
        checkAll:function(){
            this.intCheckAll = !this.intCheckAll;
            this.filteredOptions.forEach(e => {
                e.is_checked = this.intCheckAll;
            });
            this.getChecked();
        },
        //Selecciona una opción
        checkOption:function(index){
            this.filteredOptions[index].is_checked = !this.filteredOptions[index].is_checked
            this.getChecked();
            this.intCheckAll = this.filteredOptions.every(function(e){return e.is_checked});
        },
        //Muestra o no las opciones
        show:function(){
            this.isShow = !this.isShow;
        },
    },
    computed:{
        filteredOptions:function(){
            const result =  Object.values(filterArray(this.options, this.strSearch));
            this.dataFiltered = result;
            return result;
        },
    },
    watch:{
        //Se observa una vez la propiedad para monitorear cualquier cambio en la misma 
        //con el propósito de asignar los valores por defecto
        modelValue:{
            once:true,
            handler(){ this.setDefault();}
        },
        filteredOptions:{
            handler(){
                this.getChecked();
            }
        }
    },
    directives:{
        // Se crea la directiva para cerrar el contenedor de las opciones al momento de hacer
        // un click por fuera del seleccionador
        clickout:{
            mounted(el, binding) {
                document.addEventListener("click",function(e){
                    if(!el.contains(e.target)){ binding.instance.isShow = false; }
                });
            },
        }
    }

}