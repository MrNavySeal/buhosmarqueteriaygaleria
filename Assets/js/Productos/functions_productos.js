import AppButton from "../components/button.js"; 
import AppModal from "../components/modal.js"; 
import AppInput from "../components/input.js";
import AppSelect from "../components/select.js";
import AppPagination from "../components/pagination.js"
import AppTextArea from "../components/textarea.js"
import AppButtonInput from "../components/button_input.js"
import AppButtonSelect from "../components/button_select.js";
import {createCommon} from "../components/variables.js";
const App = {
    components:{
        "app-button":AppButton,
        "app-input":AppInput,
        "app-button-input":AppButtonInput,
        "app-button-select":AppButtonSelect,
        "app-textarea":AppTextArea,
        "app-select":AppSelect,
        "app-pagination":AppPagination,
        "app-modal":AppModal
    },
    data(){
        return {
            common:createCommon(),
            category:createCommon(),
            subcategory:createCommon(),
            ingredients:createCommon(),
            objCategory:{name:"",id:""},
            objSubcategory:{name:"",id:"",categoryid:""},
            errors:[],
            arrMeasures:[],
            arrSpecs:[],
            arrVariants:[],
            arrImages:[],
            arrSpecsAdded:[],
            arrIngredients:[],
            arrVariantsAdded:[],
            arrVariantsToMix:[],
            arrVariantsMixed:[],
            arrCombination:[],
            arrIngredientsAdded:[],
            intStatus:1,
            strName:"",
            strReference:"",
            strShortDescription:"",
            intCheckProduct:false,
            intCheckIngredient:false,
            intCheckRecipe:false,
            intCheckStock:false,
            intCheckVariant:false,
            intStock:0,
            intMinStock:0,
            intTax:0,
            intPurchasePrice:0,
            intSellPrice:0,
            intOfferPrice:0,
            intFraming:2,
            intVisible:true,
            intMeasure:"",
            intSpec:"",
            intVariant:"",
            strImgUrl:base_url+'/Assets/images/uploads/category.jpg',
            strImage:"",
        }
    },
    mounted(){
        this.search();
    },
    methods:{
        getData:async function(){
            const response = await fetch(base_url+"/Productos/Productos/getData");
            const objData = await response.json();
            this.arrSpecs = objData.specs;
            this.arrMeasures = objData.measures;
            this.arrVariants = objData.variants;
            this.intMeasure = objData.measures[0].id;
            const ingredients = objData.ingredients;
            this.ingredients.modalType = "ingredients";
            this.ingredients.arrData = ingredients.data;
            this.ingredients.intStartPage  = ingredients.start_page;
            this.ingredients.intTotalButtons = ingredients.limit_page;
            this.ingredients.intTotalPages = ingredients.total_pages;
            this.ingredients.intTotalResults = ingredients.total_records;
            this.ingredients.arrButtons = ingredients.buttons;
        },

        openModal:function(){
            this.getData();
            document.querySelector("#txtDescription").value ="";
            setTinymce("#txtDescription",400);
            this.common.showModalProduct = true;
            this.common.intId =0;
            this.objCategory={name:"",id:""};
            this.objSubcategory={name:"",id:"",categoryid:""};
            this.intStatus=1,
            this.strName="";
            this.strReference="";
            this.strShortDescription="";
            this.intCheckProduct=false;
            this.intCheckIngredient=false;
            this.intCheckRecipe=false;
            this.intCheckStock=false;
            this.intStock=0;
            this.intMinStock=0;
            this.intTax=0;
            this.intPurchasePrice=0;
            this.intSellPrice=0;
            this.intOfferPrice=0;
            this.intFraming=2;
            this.intVisible=true;
            this.arrImages=[];
            this.intMeasure="";
            this.strImage="";
            this.strImgUrl ="";
            this.intCheckVariant = false;
            this.strName="";
            this.arrSpecsAdded = [];
            this.strReference="";
            this.arrCombination = [];
            this.arrVariantsToMix = [];
            this.arrVariantsAdded = [];
            this.arrIngredientsAdded = [];
            this.errors = [];
            this.intCheckStock = false;
            this.common.productTitle = "Nuevo producto";

        },

        save:async function(){
            tinymce.triggerSave();
            const strDescription = document.querySelector("#txtDescription").value;
            const formData = new FormData();
            const arrData = {
                "images":this.arrImages.filter(function(e){return e.rename && e.rename!=""}),
                "is_visible":this.intVisible,
                "status":this.intStatus,
                "id":this.common.intId,
                "subcategory":this.objSubcategory.id,
                "category":this.objCategory.id,
                "framing_mode":this.intFraming,
                "measure":this.intMeasure,
                "import":this.intTax,
                "is_product":this.intCheckProduct,
                "is_ingredient":this.intCheckIngredient,
                "is_combo":this.intCheckRecipe,
                "is_stock":this.intCheckStock,
                "price_purchase":this.intPurchasePrice,
                "price_sell":this.intSellPrice,
                "price_offer":this.intOfferPrice,
                "product_type":this.intCheckVariant,
                "stock":this.intStock,
                "min_stock":this.intMinStock,
                "short_description":this.strShortDescription,
                "description":strDescription,
                "name":this.strName,
                "specs":this.arrSpecsAdded,
                "reference":this.strReference,
                "combinations": this.arrCombination,
                "variants":this.arrVariantsToMix,
                "is_stock":this.intCheckStock,
                "ingredients":this.arrIngredientsAdded
            }
            formData.append("data",JSON.stringify(arrData));
            formData.append("images[]",[]);
            formData.append("image",this.strImage);
            if(this.arrImages.length > 0){
                this.arrImages.forEach(function(e){
                    formData.append("images[]",e);
                });
            }
            this.common.processing =true;
            const response = await fetch(base_url+"/Productos/Productos/setProduct",{method:"POST",body:formData});
            const objData = await response.json();
            this.common.processing =false;
            if(objData.status){
                this.common.strName ="";
                this.common.intId =0;
                this.common.showModalProduct = false;
                this.subcategory.modalType='';
                this.category.modalType='';
                this.ingredients.modalType='';
                this.common.modalType='products';
                this.search(this.common.intPage);
                Swal.fire("Guardado",objData.msg,"success");
            }else{
                this.errors = objData.errors;
                Swal.fire("Error",objData.msg,"error");
            }
        },

        search:async function(page=1){
            const formData = new FormData();
            if(this.subcategory.modalType=='subcategory'){
                this.subcategory.intPage = page;
                formData.append("id",this.objCategory.id);
                formData.append("page",this.subcategory.intPage);
                formData.append("per_page",this.subcategory.intPerPage);
                formData.append("search",this.subcategory.strSearch);
                const response = await fetch(base_url+"/Productos/ProductosMasivos/getSelectSubcategorias",{method:"POST",body:formData});
                const objData = await response.json();
                this.subcategory.arrData = objData.data;
                this.subcategory.intStartPage  = objData.start_page;
                this.subcategory.intTotalButtons = objData.limit_page;
                this.subcategory.intTotalPages = objData.total_pages;
                this.subcategory.intTotalResults = objData.total_records;
                this.subcategory.arrButtons = objData.buttons;
            }else if(this.subcategory.modalType == "category"){
                this.category.intPage = page;
                formData.append("page",this.category.intPage);
                formData.append("per_page",this.category.intPerPage);
                formData.append("search",this.category.strSearch);
                const response = await fetch(base_url+"/Productos/ProductosMasivos/getSelectCategorias",{method:"POST",body:formData});
                const objData = await response.json();
                this.category.arrData = objData.data;
                this.category.intStartPage  = objData.start_page;
                this.category.intTotalButtons = objData.limit_page;
                this.category.intTotalPages = objData.total_pages;
                this.category.intTotalResults = objData.total_records;
                this.category.arrButtons = objData.buttons;
            }else if(this.ingredients.modalType == "ingredients"){
                this.ingredients.intPage = page;
                formData.append("page",this.ingredients.intPage);
                formData.append("per_page",this.ingredients.intPerPage);
                formData.append("search",this.ingredients.strSearch);
                formData.append("type","ingredients");
                formData.append("id",this.common.intId);
                const response = await fetch(base_url+"/Productos/Productos/getProductos",{method:"POST",body:formData});
                const objData = await response.json();
                this.ingredients.arrData = objData.data;
                this.ingredients.intStartPage  = objData.start_page;
                this.ingredients.intTotalButtons = objData.limit_page;
                this.ingredients.intTotalPages = objData.total_pages;
                this.ingredients.intTotalResults = objData.total_records;
                this.ingredients.arrButtons = objData.buttons;
            }else{
                this.common.intPage = page;
                formData.append("page",this.common.intPage);
                formData.append("per_page",this.common.intPerPage);
                formData.append("search",this.common.strSearch);
                formData.append("type","products");
                this.$refs.btnGenerate.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
                this.$refs.btnGenerate.setAttribute("disabled","");

                const response = await fetch(base_url+"/Productos/Productos/getProductos",{method:"POST",body:formData});
                const objData = await response.json();

                this.$refs.btnGenerate.innerHTML=`Buscar`;
                this.$refs.btnGenerate.removeAttribute("disabled");

                this.common.arrData = objData.data;
                this.common.intStartPage  = objData.start_page;
                this.common.intTotalButtons = objData.limit_page;
                this.common.intTotalPages = objData.total_pages;
                this.common.intTotalResults = objData.total_records;
                this.common.arrButtons = objData.buttons;
            }
        },

        edit:async function(data,type="edit"){
            const formData = new FormData();
            formData.append("id",data.idproduct);
            const response = await fetch(base_url+"/Productos/Productos/getProduct",{method:"POST",body:formData});
            const objData = await response.json();
            if(objData.status){
                const data = objData.data;
                if(type=="edit"){
                    this.common.showModalProduct = true;
                    this.common.productTitle = "Editar producto";
                    setTinymce("#txtDescription",400);
                    document.querySelector("#txtDescription").value = data.description;
                }else{
                    this.common.showModalViewProduct = true;
                    this.common.productTitle = "Ver producto";
                    setTinymce("#txtViewDescription",400);
                    document.querySelector("#txtViewDescription").value = data.description;
                }
                this.common.intId =data.idproduct;
                this.objCategory={name:data.category,id:data.categoryid};
                this.objSubcategory={name:data.subcategory,id:data.subcategoryid,categoryid:data.categoryid};
                this.intStatus=data.status;
                this.strName=data.name;
                this.strReference=data.reference;
                this.strShortDescription=data.shortdescription;
                this.intCheckProduct=data.is_product;
                this.intCheckIngredient=data.is_ingredient;
                this.intCheckRecipe=data.is_combo;
                this.intCheckStock=data.is_stock;
                this.intCheckVariant = data.product_type;
                this.intStock=data.stock;
                this.intMinStock=data.min_stock;
                this.intTax=data.import;
                this.intPurchasePrice=data.price_purchase;
                this.intSellPrice=data.price_sell;
                this.intOfferPrice=data.price_offer;
                this.intFraming=data.framing_mode;
                this.intVisible=data.is_visible;
                this.arrImages=data.image;
                this.strImgUrl=data.framing_url;
                this.intMeasure=data.measure;
                this.arrSpecsAdded = data.specs;
                this.strReference=data.reference;
                this.arrSpecs = data.info_specs;
                this.arrMeasures = data.info_measures;
                this.arrVariants = data.info_variants;
                this.arrIngredientsAdded = data.ingredients;

                this.ingredients.arrData = data.info_ingredients.data;
                this.ingredients.intStartPage  = data.info_ingredients.start_page;
                this.ingredients.intTotalButtons = data.info_ingredients.limit_page;
                this.ingredients.intTotalPages = data.info_ingredients.total_pages;
                this.ingredients.intTotalResults = data.info_ingredients.total_records;
                this.ingredients.arrButtons = data.info_ingredients.buttons;

                if(this.intCheckVariant){
                    const arrVariants = this.arrVariants;
                    const arrVariations = data.variation.variation;
                    const arrVariantsAdded = [];
                    arrVariations.forEach(e => {
                        const arrVariant = arrVariants.filter(function(variant){
                            return variant.id == e.id; 
                        });

                        if(arrVariant.length > 0){
                            const variant = arrVariant[0];
                            variant.options.forEach(op => {
                                const options = e.options.filter(function(vop){
                                    return vop == op.name
                                });
                                if(options.length > 0){ op.checked=true; }else{op.checked=false}
                            });
                            arrVariantsAdded.push(variant);
                        }
                    });
                    this.arrVariantsAdded = arrVariantsAdded;
                    this.changeVariant();
                    this.arrCombination = data.options;
                }
                this.errors = [];
                
            }else{
                Swal.fire("Error",objData.msg,"error");
            } 
        },

        del:async function(data){
            const objVue = this;
            this.subcategory.modalType='';
            this.category.modalType='';
            this.ingredients.modalType='';
            this.common.modalType='products';
            Swal.fire({
                title:"¿Esta seguro de eliminar?",
                text:"Se eliminará para siempre...",
                icon: 'warning',
                showCancelButton:true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText:"Sí, eliminar",
                cancelButtonText:"No, cancelar"
            }).then(async function(result){
                if(result.isConfirmed){
                    const formData = new FormData();
                    formData.append("id",data.idproduct);
                    const response = await fetch(base_url+"/Productos/Productos/delProduct",{method:"POST",body:formData});
                    const objData = await response.json();
                    if(objData.status){
                        Swal.fire("Eliminado!",objData.msg,"success");
                        objVue.search(objVue.common.intPage);
                    }else{
                        Swal.fire("Error",objData.msg,"error");
                    }
                }else{
                    objVue.search(objVue.common.intPage);
                }
            });
            
        },

        delItem:function(type="",data=""){
            if(type=="subcategory"){
                this.objSubcategory = {name:"",id:"",categoryid:""};
            }else if(type=="category"){
                this.objCategory = {name:"",id:"",};
                this.objSubcategory = {name:"",id:"",categoryid:""};
            }else if(type=="image"){
                const index =this.arrImages.findIndex(function(e){return e.name==data});
                this.arrImages.splice(index,1);
            }else if(type=="spec"){
                const index =this.arrSpecsAdded.findIndex(function(e){return e.id==data.id});
                this.arrSpecsAdded.splice(index,1);
            }else if(type=="variant"){
                const index =this.arrVariantsAdded.findIndex(function(e){return e.id==data.id});
                this.arrVariantsAdded.splice(index,1);
                this.changeVariant();
            }else if(type=="ingredient"){
                const index =this.arrIngredientsAdded.findIndex(function(e){
                    if(e.variant_name!=""){
                        return e.variant_name==data.variant_name && e.id == data.id;
                    }else{
                        return e.id==data.id;
                    }
                });
                this.arrIngredientsAdded.splice(index,1);
            }
        },

        addItem:function(type="",data=""){
            if(type=="spec"){
                let id = this.intSpec;
                const arr = this.arrSpecsAdded.filter(function(e){return e.id == id});
                if(this.intSpec == ""){
                    Swal.fire("Atención!","Seleccione una característica.","warning");
                    return false;
                }
                if(arr.length > 0){
                    Swal.fire("Atención!","La característica ya ha sido agregada, seleccione otra.","warning");
                    return false;
                }
                const arrData = [...this.arrSpecs];
                data = arrData.filter(function(e){return e.id == id})[0];
                data.value ="";
                this.arrSpecsAdded.push(data);
                this.intSpec="";
            }else if(type=="variant"){
                let id = this.intVariant;
                const arr = this.arrVariantsAdded.filter(function(e){return e.id == id});
                if(this.intVariant == ""){
                    Swal.fire("Atención!","Seleccione una variante.","warning");
                    return false;
                }
                if(arr.length > 0){
                    Swal.fire("Atención!","La variante ya ha sido agregada, seleccione otra.","warning");
                    return false;
                }
                const arrData = [...this.arrVariants];
                data = arrData.filter(function(e){return e.id == id})[0];
                this.arrVariantsAdded.push(data);
                this.intVariant="";
            }else if(type=="ingredient"){
                let flag = false;
                for (let i = 0; i < this.arrIngredientsAdded.length; i++) {
                    const e = this.arrIngredientsAdded[i];
                    if(data.variant_name != "" && data.variant_name == e.variant_name && data.id == e.id){
                        flag = true;
                        break;
                    }else if(data.variant_name == "" && data.id == e.id){
                        flag = true;
                        break;
                    }
                }

                if(!flag){
                    data.qty = 0;
                    data.subtotal = 0;
                    this.arrIngredientsAdded.push(data);
                }else{
                    Swal.fire("Atención!","Este insumo ya fue agregado, intente con otro","warning");
                }
            }
        },

        selectItem:function(data,type=""){
            if(type=="subcategory"){
                this.objSubcategory=data;
                this.subcategory.showModalPaginationSubcategory=false
            }else if(type=="category"){
                this.objCategory=data; 
                if(this.objSubcategory.categoryid != this.objCategory.id){
                    this.objSubcategory = {name:"",id:"",categoryid:""};
                }
                this.category.showModalPaginationCategory=false
            }
        },

        view:async function(data){
            window.open(base_url+"/tienda/producto/"+data.route,"_blank");
        },

        changeVariant:function(){
            const existingCombos = {};
            this.arrCombination.forEach(combo => {
                let key = combo.name.split("-");
                key = key.sort().join("|");
                existingCombos[key] = { ...combo };
            });

            const arrComb= [];
            this.arrCombination = [];
            this.arrVariantsToMix = [];
            for (let i = 0; i < this.arrVariantsAdded.length; i++) {
                const e =  this.arrVariantsAdded[i];
                const optionsToMix = [];
                const options = e.options;
                options.forEach(op => {
                    if(op.checked) optionsToMix.push(op.name);
                });
                if(optionsToMix.length > 0) this.arrVariantsToMix.push({id:e.id,name:e.name,options:optionsToMix});
            }
            let result = [];
            if(this.arrVariantsToMix.length>0){  
                function addOption( oldOptionsMixed, newOptions){
                    let newMix = [];
                    oldOptionsMixed.forEach(ol=>{
                        newOptions.forEach(ne =>{
                            newMix.push([...ol,ne]);
                        })
                    })
                    return newMix;
                }
                result = this.arrVariantsToMix[0].options.map(option => [option]);
                for (let i = 1; i < this.arrVariantsToMix.length; i++) {
                    result = addOption(result,this.arrVariantsToMix[i].options);
                }
            }
            this.arrVariantsMixed = result;
            this.arrVariantsMixed.forEach(function(e){
                const name = e.join("-");
                let existing = {};
                for (const key in existingCombos) {
                    const parts = key.split("|");
                    if (parts.every(p => e.includes(p))) {
                        existing = existingCombos[key];
                        break;
                    }
                }
                arrComb.push({
                    name,
                    price_purchase: existing.status != undefined ? existing.price_purchase : 0,
                    price_sell: existing.status != undefined ? existing.price_sell : 0,
                    price_offer: existing.status != undefined ? existing.price_offer : 0,
                    stock: existing.status != undefined ? existing.stock : 0,
                    min_stock: existing.status != undefined ? existing.min_stock : 0,
                    sku: existing.status != undefined ? existing.sku : "",
                    status: existing.status != undefined ? existing.status : false,
                });
            });
            this.arrCombination = arrComb;
        },

        changeCategory:function (type){
            if(type == "subcategory"){
                this.subcategory.modalType=type;
                this.subcategory.showModalPaginationSubcategory=true;
            }else{
                this.subcategory.modalType='category';
                this.category.modalType=type;
                this.category.showModalPaginationCategory=true;
            }
            this.search();
        },

        uploadMultipleImage:function(e){
            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                const f = files[i];
                if(f.type != "image/png" && f.type != "image/jpg" && f.type != "image/jpeg" && f.type != "image/gif"){
                    Swal.fire("Error","Solo se permite imágenes","error");
                    return false;
                }else{
                    let objectUrl = window.URL || window.webkitURL;
                    let route = objectUrl.createObjectURL(f);
                    f.route =route;
                    this.arrImages.push(f);
                }   
            }
        },

        uploadImagen:function(e){
            this.strImage = e.target.files[0];
            let type = this.strImage.type;
            if(type != "image/png" && type != "image/jpg" && type != "image/jpeg" && type != "image/gif"){
                Swal.fire("Error","Solo se permite imágenes.","error");
            }else{
                let objectUrl = window.URL || window.webkitURL;
                let route = objectUrl.createObjectURL(this.strImage);
                this.strImgUrl = route;
            }
        },

        updateIngredient:function(data){
            let qty = parseFloat(data.qty);
            let price = parseFloat(data.price_purchase);
            let subtotal = qty*price;
            data.subtotal = subtotal;

            const index =this.arrIngredientsAdded.findIndex(function(e){
                if(e.variant_name!=""){
                    return e.variant_name==data.variant_name && e.id == data.id;
                }else{
                    return e.id==data.id;
                }
            });
            this.arrIngredientsAdded[index]=data;
        },

        formatNum:function(num,mil="."){
            let numero = num;
            let format = mil;

            const noTruncarDecimales = {maximumFractionDigits: 20};
            

            if(format == ","){
                format = numero.toLocaleString('en-US', noTruncarDecimales);
            }else if(mil == "."){
                format  = numero.toLocaleString('es', noTruncarDecimales);
            }
            return format;   
        }
    },
    computed:{
        totalIngredients:function(){
            let total = 0;
            this.arrIngredientsAdded.forEach(e => {
                total+=e.subtotal;
            });
            return total;
        }
    }
};
const app = Vue.createApp(App);
app.mount("#app");